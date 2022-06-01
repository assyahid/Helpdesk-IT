<?php

namespace App\Services\Search\SearchTerms;

use App\SearchTerm;
use Axisofstevil\StopWords\Filter;
use Illuminate\Support\Collection;
use Str;
use webd\language\StringDistance;

class AggregateSearchTerms
{
    /**
     * @var Filter
     */
    private $stopWords;

    public function __construct(Filter $stopWords)
    {
        $this->stopWords = $stopWords;
    }

    public function execute(array $searchSession = []): Collection
    {
        $searchSession = collect($searchSession)
            ->map(function ($sessionItem) {
                if ( ! isset($sessionItem['normalized_term'])) {
                    $sessionItem['normalized_term'] = slugify(
                        $this->stopWords->cleanText($sessionItem['term']),
                        ' ',
                    );
                }
                return $sessionItem;
            })
            ->filter(function ($sessionItem) {
                $len = strlen($sessionItem['normalized_term']);
                return $len > 3 && $len <= 100;
            });

        $searchSession->each(function ($sessionItem, $key) use (
            $searchSession
        ) {
            $sessionWithoutItem = $searchSession->except($key);
            if (!$this->termIsUnique($sessionWithoutItem, $sessionItem)) {
                unset($searchSession[$key]);
            }
        });

        return $searchSession;
    }

    /**
     * @param array|SearchTerm $currentItem
     */
    private function termIsUnique(iterable $searchSession, $currentItem): bool
    {
        foreach ($searchSession as $nextItem) {
            if (
                $currentItem['term'] === $nextItem['term'] ||
                Str::startsWith($currentItem['term'], $nextItem['term']) ||
                $this->termsAreTooSimilar(
                    $currentItem['term'],
                    $nextItem['term'],
                )
            ) {
                return false;
            }
        }

        return true;
    }

    private function termsAreTooSimilar(string $newest, string $oldest): bool
    {
        return StringDistance::JaroWinkler($newest, $oldest) > 0.8;
    }
}
