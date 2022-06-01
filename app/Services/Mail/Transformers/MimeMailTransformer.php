<?php namespace App\Services\Mail\Transformers;

use ZBateson\MailMimeParser\Message;

class MimeMailTransformer implements MailTransformer
{
    /**
     * @param string $emailData
     */
    public function transform($emailData): array
    {
        $message = Message::from($emailData, false);

        $parsed = [
            'headers' => [],
            'body' => [
                'html' => '',
                'plain' => '',
            ],
            'attachments' => [],
        ];

        foreach ($message->getAllHeaders() as $header) {
            $parsed['headers'][$header->getName()] = $header->getValue();
        }

        $parsed['body']['html'] = $message->getHtmlContent();
        $parsed['body']['plain'] = $message->getTextContent();

        foreach ($message->getAllAttachmentParts() as $attachment) {
            $content = $attachment->getContent();
            $parsed['attachments'][] = [
                'contents' => $content,
                'original_name' => $attachment->getFilename(),
                'mime_type' => $attachment->getContentType(),
                'size' => strlen($content),
                'extension' => pathinfo(
                    $attachment->getFilename(),
                    PATHINFO_EXTENSION,
                ),
                'cid' => $attachment->getContentId(),
            ];
        }

        return $parsed;
    }
}
