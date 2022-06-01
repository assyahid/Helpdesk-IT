<?php namespace App\Services\Mail\Transformers;

use Illuminate\Support\Arr;

class NullMailTransformer implements MailTransformer
{
    public function transform($emailData): array
    {
        if (is_array($emailData) && Arr::get($emailData, 'mime')) {
            return app(MimeMailTransformer::class)->transform(
                $emailData['mime'],
            );
        } else {
            return $emailData;
        }
    }
}
