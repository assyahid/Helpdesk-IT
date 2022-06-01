<?php namespace App\Services\Mail\Parsing;

use App;
use App\Services\Mail\Transformers\GmailMailTransformer;
use App\Services\Mail\Transformers\MailgunMailTransformer;
use App\Services\Mail\Transformers\MailTransformer;
use App\Services\Mail\Transformers\MimeMailTransformer;
use App\Services\Mail\Transformers\NullMailTransformer;
use Arr;
use Common\Settings\Settings;
use EmailReplyParser\Parser\EmailParser;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use Str;
use ZBateson\MailMimeParser\Message;

class ParsedEmail implements Jsonable
{
    /**
     * @var array
     */
    private $email;

    /**
     * @var EmailBodyParser
     */
    private $emailBodyParser;

    public function __construct(EmailBodyParser $emailBodyParser)
    {
        $this->emailBodyParser = $emailBodyParser;
    }

    public function setEmailData($data, ?string $forcedTransformer = null): self
    {
        $transformer = $forcedTransformer
            ? app($forcedTransformer)
            : $this->getMailTransformer();
        $this->email = $transformer->transform($data);
        return $this;
    }

    /**
     * This will strip quoted replies from email and
     * remove any not allowed html tags.
     */
    public function getNormalizedBody(array $cidMap = []): string
    {
        $plain = (new EmailParser())
            ->parse($this->getBody('plain'))
            ->getVisibleText();

        // remove quoted text from email, if not already removed
        if (!$this->hasBody('stripped-html')) {
            $body = $this->getBody('html') ?: $plain;
            $this->email['body']['stripped-html'] = $body;
        }

        $body = $this->email['body']['stripped-html'];

        // replace CIDs in img src with actual image urls
        foreach ($cidMap as $cid => $url) {
            $body = str_replace("cid:$cid", $url, $body);
        }

        $parsedBody = $this->emailBodyParser->parse($body);
        if (!$parsedBody) {
            $parsedBody = $this->emailBodyParser->parse($plain);
        }

        return $parsedBody;
    }

    public function getSubject(): string
    {
        return $this->getHeader('Subject') ?: '(no subject)';
    }

    public function getSenderEmail(): string
    {
        $header = $this->getHeader('Reply-To') ?: $this->getHeader('From');

        $email = Message::from("From: $header", false)
            ->getHeader('From')
            ->getEmail();

        if ($email) {
            return $email;
        }

        throw new InvalidArgumentException(
            "Could not extract email address from [$header]",
        );
    }

    public function getHeader(string $name): ?string
    {
        return Arr::get($this->email, "headers.$name");
    }

    public function hasHeader(string $name): bool
    {
        return Arr::has($this->email, "headers.$name");
    }

    public function getHeaders(): array
    {
        return Arr::get($this->email, 'headers', []);
    }

    public function getBody(string $type): ?string
    {
        return Arr::get($this->email, "body.$type");
    }

    public function hasBody(string $type): bool
    {
        return Arr::get($this->email, "body.$type") !== null;
    }

    public function getAttachments(string $type): Collection
    {
        $attachments = Arr::get($this->email, 'attachments', []);

        // if attachment has a CID then it's inline, otherwise it's 'regular'
        return collect($attachments)->filter(function ($attachment) use (
            $type
        ) {
            $cidEmbedded =
                $attachment['cid'] &&
                Str::contains($this->getBody('html'), $attachment['cid']);

            //if email body does not have attachment CID embedded, treat attachment as 'regular'
            if ($type === 'inline') {
                return $cidEmbedded;
            } else {
                return !$cidEmbedded;
            }
        });
    }

    public function toJson($options = 0): string
    {
        return json_encode(
            [
                'headers' => $this->getHeaders(),
                'body' => [
                    'plain' => $this->getBody('plain'),
                    'html' => $this->getBody('html'),
                ],
            ],
            $options,
        );
    }

    private function getMailTransformer(): MailTransformer
    {
        switch (app(Settings::class)->get('mail.handler')) {
            case 'mailgun':
                return app(MailgunMailTransformer::class);
            case 'gmailApi':
                return app(GmailMailTransformer::class);
            case 'mime':
                return app(MimeMailTransformer::class);
            default:
                return app(NullMailTransformer::class);
        }
    }
}
