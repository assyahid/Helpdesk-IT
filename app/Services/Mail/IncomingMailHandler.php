<?php namespace App\Services\Mail;

use App;
use App\Events\TicketCreated;
use App\Notifications\TicketIsLocked;
use App\Notifications\TicketRejected;
use App\Reply;
use App\Services\Files\EmailStore;
use App\Services\Mail\Parsing\ParsedEmail;
use App\Services\Ticketing\ReplyRepository;
use App\Services\Ticketing\TicketReplyCreator;
use App\Services\Ticketing\TicketRepository;
use App\Ticket;
use Common\Auth\UserRepository;
use Common\Files\Actions\UploadFile;
use Common\Files\Traits\GetsEntryTypeFromMime;
use Common\Settings\Settings;
use Notification;
use Str;

class IncomingMailHandler
{
    use GetsEntryTypeFromMime;

    /**
     * @var ReplyRepository
     */
    private $replyRepository;

    /**
     * @var EmailStore
     */
    private $emailStore;

    /**
     * @var TicketRepository
     */
    private $ticketRepository;

    /**
     * @var Settings
     */
    private $settings;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var ParsedEmail
     */
    private $parsedEmail;

    /**
     * @var TicketReplyCreator
     */
    private $ticketReplyCreator;

    /**
     * @var TicketReferenceHash
     */
    private $referenceHash;

    public function __construct(
        ReplyRepository $replyRepository,
        TicketRepository $ticketRepository,
        TicketReplyCreator $ticketReplyCreator,
        EmailStore $emailStore,
        Settings $settings,
        UserRepository $userRepository,
        ParsedEmail $parsedEmail,
        TicketReferenceHash $referenceHash
    ) {
        $this->settings = $settings;
        $this->emailStore = $emailStore;
        $this->parsedEmail = $parsedEmail;
        $this->referenceHash = $referenceHash;
        $this->userRepository = $userRepository;
        $this->replyRepository = $replyRepository;
        $this->ticketRepository = $ticketRepository;
        $this->ticketReplyCreator = $ticketReplyCreator;
    }

    public function parseEmailIntoTicketOrReply(
        $data,
        ?string $transformer = null
    ): void {
        $this->parsedEmail->setEmailData($data, $transformer);
        $ticket = $this->getTicketEmailIsInReplyTo();

        // prevent replies from the same email from being created
        $emailId = $this->parsedEmail->getHeader('Message-ID');
        if ($emailId && Reply::where('email_id', $emailId)->exists()) {
            return;
        }

        if ($ticket && $ticket->status === 'locked') {
            Notification::route(
                'mail',
                $this->parsedEmail->getSenderEmail(),
            )->notify(new TicketIsLocked($ticket));
            return;
        }

        //create new ticket from email
        if (!$ticket && $this->settings->get('tickets.create_from_emails')) {
            $newTicket = $this->createTicketFromEmail();
            $reply = $newTicket->replies->first();
        }

        //create reply for existing ticket from email
        if ($ticket && $this->settings->get('replies.create_from_emails')) {
            $reply = $this->createReplyFromEmail($ticket);
        }

        if (!$ticket) {
            $this->maybeSendTicketRejectedNotification();
        }

        $this->storeOriginalEmail($reply ?? null);
    }

    private function getTicketEmailIsInReplyTo(): ?Ticket
    {
        $reply = null;

        if ($this->parsedEmail->hasHeader('In-Reply-To')) {
            $uuid = $this->referenceHash->extractFromMessageId(
                $this->parsedEmail->getHeader('In-Reply-To'),
            );
            if ($uuid) {
                $reply = $this->replyRepository->findByUuid($uuid);
            }
        }

        if (!$reply && $this->parsedEmail->hasBody('plain')) {
            $uuid = $this->referenceHash->extractFromString(
                $this->parsedEmail->getBody('plain'),
            );
            if ($uuid) {
                $reply = $this->replyRepository->findByUuid($uuid);
            }
        }

        if (!$reply && $this->parsedEmail->hasBody('html')) {
            $uuid = str_replace(
                '<wbr>',
                '',
                $this->referenceHash->extractFromString(
                    $this->parsedEmail->getBody('html'),
                ),
            );
            if ($uuid) {
                $reply = $this->replyRepository->findByUuid($uuid);
            }
        }

        return $reply ? $reply->ticket : null;
    }

    private function createTicketFromEmail(): Ticket
    {
        $email = $this->parsedEmail->getSenderEmail();
        $user = $this->userRepository->firstOrCreate(['email' => $email]);

        $cidMap = $this->generateCidMap($user->id);

        $ticket = $this->ticketRepository->create([
            'body' => $this->parsedEmail->getNormalizedBody($cidMap),
            'subject' => $this->parsedEmail->getSubject(),
            'user_id' => $user->id,
            'uploads' => $this->createUploadsFromAttachments($user->id),
            'received_at_email' =>
                $this->parsedEmail->getHeader('Delivered-To') ??
                $this->parsedEmail->getHeader('To'),
        ]);

        event(new TicketCreated($ticket));

        return $ticket;
    }

    private function createReplyFromEmail(Ticket $ticket): Reply
    {
        $cidMap = $this->generateCidMap($ticket->user_id);

        return $this->ticketReplyCreator->create(
            $ticket,
            [
                'body' => $this->parsedEmail->getNormalizedBody($cidMap),
                'user_id' => $ticket->user_id,
                'uploads' => $this->createUploadsFromAttachments(
                    $ticket->user_id,
                ),
                'email_id' => $this->parsedEmail->getHeader('Message-ID'),
            ],
            Reply::REPLY_TYPE,
            Reply::SOURCE_EMAIL,
        );
    }

    /**
     * Store inline images and generate CID map for them.
     */
    private function generateCidMap(int $userId): array
    {
        $inlineAttachments = $this->parsedEmail->getAttachments('inline');

        return $inlineAttachments
            ->mapWithKeys(function ($attachment) use ($userId) {
                $data = $this->transformAttachmentData($attachment);
                $fileEntry = app(UploadFile::class)->execute('public', $data, [
                    'diskPrefix' => 'ticket_images',
                    'userId' => $userId,
                ]);
                return [$attachment['cid'] => url($fileEntry->url)];
            })
            ->toArray();
    }

    private function createUploadsFromAttachments(int $userId): array
    {
        $attachments = $this->parsedEmail->getAttachments('regular');

        $uploadIds = $attachments->map(function ($attachment) use ($userId) {
            $data = $this->transformAttachmentData($attachment);
            $fileEntry = app(UploadFile::class)->execute('private', $data, [
                'userId' => $userId,
            ]);
            return $fileEntry->id;
        });

        return $uploadIds->toArray();
    }

    private function transformAttachmentData(array $data): array
    {
        return [
            'name' => $data['original_name'],
            'file_name' => Str::random(40),
            'mime' => $data['mime_type'],
            'type' => $this->getTypeFromMime($data['mime_type']),
            'file_size' => $data['size'],
            'extension' => $data['extension'],
            'contents' => $data['contents'],
        ];
    }

    private function storeOriginalEmail(Reply $reply = null)
    {
        if (!$reply && !$this->settings->get('mail.store_unmatched_emails')) {
            return;
        }
        $this->emailStore->storeEmail($this->parsedEmail, $reply);
    }

    /**
     * Send rejected notification to sender if
     * ticket creation via email channel is disabled.
     */
    private function maybeSendTicketRejectedNotification()
    {
        if (
            !$this->settings->get('tickets.create_from_emails') &&
            $this->settings->get('tickets.send_ticket_rejected_notification')
        ) {
            Notification::route(
                'mail',
                $this->parsedEmail->getSenderEmail(),
            )->notify(new TicketRejected());
        }
    }
}
