<?php namespace App\Services\Ticketing;

use App\Activity;
use App\Events\TicketReplyCreated;
use App\Events\TicketUpdated;
use App\Reply;
use App\Services\Ticketing\Actions\SendTicketReplyEmail;
use App\Ticket;

class TicketReplyCreator
{
    /**
     * @var TicketRepository
     */
    private $ticketRepository;

    /**
     * @var ReplyRepository
     */
    private $replyRepository;

    public function __construct(
        TicketRepository $ticketRepository,
        ReplyRepository $replyRepository
    ) {
        $this->replyRepository = $replyRepository;
        $this->ticketRepository = $ticketRepository;
    }

    public function create(Ticket $ticket, array $data, string $type, string $source): Reply
    {
        $reply = $this->replyRepository->create($data, $ticket, $type);
        $creator = request()->user();

        if ($type === 'replies') {
            $statusName = $data['status']['name'] ?? 'open';

            //change ticket status to specified one or "open"
            $this->ticketRepository->changeStatus([$ticket->id], $statusName);

            if ($creator && !$creator->isAgent()) {
                Activity::replyCreated($reply, $source);
            }

            app(SendTicketReplyEmail::class)->execute(
                $ticket,
                $reply,
                $creator,
            );
        }

        if ($type !== 'drafts') {
            event(new TicketReplyCreated($ticket, $reply));
            event(new TicketUpdated($ticket));
        }

        return $reply->load('user', 'uploads');
    }
}
