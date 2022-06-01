<?php

namespace App\Services\Mail\Parsing;

use Str;
use Symfony\Component\DomCrawler\Crawler;

class StripEmailSignature
{
    /**
     * @var Crawler
     */
    private $crawler;

    public function execute(string $htmlBody): string
    {
        $this->crawler = new Crawler($htmlBody);

        collect(get_class_methods($this))
            ->filter(function ($methodName) {
                return Str::startsWith($methodName, 'strip');
            })
            ->each(function ($methodName) {
                $this->$methodName();
            });

        $html = $this->crawler->outerHtml();
        $html = str_replace(' Sent from my iPhone', '', $html);
        return $html;
    }

    private function stripAppleMail()
    {
        $match = $this->crawler->filter('#AppleMailSignature');
        if ($match->count()) {
            $node = $match->getNode(0);
            $node->parentNode->removeChild($node);
        }
    }

    private function stripOpenExchange()
    {
        $match = $this->crawler->filter('.io-ox-signature');
        if ($match->count()) {
            $node = $match->getNode(0);
            $node->parentNode->removeChild($node);
        }
    }
}
