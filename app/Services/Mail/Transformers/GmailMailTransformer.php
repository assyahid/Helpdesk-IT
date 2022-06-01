<?php namespace App\Services\Mail\Transformers;

use Google\Service\Gmail\Message;

class GmailMailTransformer implements MailTransformer
{
    /**
     * @param Message $emailData
     */
    public function transform($emailData): array
    {
        $decoded = base64_decode(
            str_replace(['-', '_'], ['+', '/'], $emailData->getRaw()),
        );
        return app(MimeMailTransformer::class)->transform($decoded);
    }
}
