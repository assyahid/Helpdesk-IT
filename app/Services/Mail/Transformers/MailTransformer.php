<?php namespace App\Services\Mail\Transformers;

interface MailTransformer
{
    /**
     * Transform email data into format usable by the app.
     */
    public function transform($emailData): array;
}
