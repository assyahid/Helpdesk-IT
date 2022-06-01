<?php namespace App\Services\Mail\Parsing;

use HTMLPurifier;
use HTMLPurifier_Config;

class EmailBodyParser
{
    const REPLY_ABOVE_ID = 'bedeskReplyAboveThisLine';

    public function parse(string $htmlBody): string
    {
        $htmlBody = html_entity_decode($htmlBody);

        $htmlBody = app(StripQuotedEmailText::class)->execute($htmlBody);
        $htmlBody = app(StripEmailSignature::class)->execute($htmlBody);

        // remove reference if it got through
        $htmlBody = preg_replace(
            '/\|reference=[a-zA-Z0-9]{30}\|/',
            '',
            $htmlBody,
        );

        // purify email body
        $htmlBody = $this->getPurifier()->purify($htmlBody);

        // replace all newlines with "br" tag
        $htmlBody = str_replace(["\r\n", "\r", "\n"], '<br>', $htmlBody);

        // remove all whitespace/newlines from start and end of email body
        $htmlBody = trim($htmlBody);
        $htmlBody = preg_replace('/(?:<br\s*\/?>\s*)+$/', '', $htmlBody);
        $htmlBody = preg_replace('/^(?:<br\s*\/?>\s*)+/', '', $htmlBody);

        // replace 3 or more br tags with 2 to avoid excessive white space
        $htmlBody = preg_replace(
            '/(?:<br\s*\/?>\s*){3,}/',
            '<br><br>',
            $htmlBody,
        );

        return $htmlBody;
    }

    private function getPurifier(): HTMLPurifier
    {
        $config = HTMLPurifier_Config::createDefault();

        $config->set('Core.Encoding', 'UTF-8');
        $config->set('HTML.Doctype', 'HTML 4.01 Transitional');
        $config->set('Cache.SerializerPath', storage_path('app/purifier'));
        $config->set(
            'HTML.Allowed',
            'b,strong,i,u,a[href],ul,ol,li,br,img[src|width|height],font[color]',
        );
        $config->set('HTML.TargetBlank', true);
        $config->set('AutoFormat.Linkify', true);
        $config->set('AutoFormat.RemoveEmpty', true);
        $config->set('AutoFormat.RemoveEmpty.RemoveNbsp', true);

        return new HTMLPurifier($config);
    }
}
