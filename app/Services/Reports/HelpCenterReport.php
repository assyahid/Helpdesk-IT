<?php

namespace App\Services\Reports;

use App\Article;
use App\SearchTerm;
use Cache;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DB;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

class HelpCenterReport
{
    /**
     * @var SearchTerm
     */
    private $searchTerm;

    /**
     * @var Article
     */
    private $article;

    /**
     * @var CarbonPeriod
     */
    private $range;

    public function __construct(SearchTerm $searchTerm, Article $article)
    {
        $this->searchTerm = $searchTerm;
        $this->article = $article;
    }

    private function createTimeRange(array $params)
    {
        $period = $params['period'] ?? 'last30Days';
        if ($period === 'today') {
            $this->range = CarbonPeriod::start(now()->subDay())->end(now());
        } elseif ($period === 'last7days') {
            $this->range = CarbonPeriod::start(now()->subWeek())->end(now());
        } elseif ($period === 'last3months') {
            $this->range = CarbonPeriod::start(now()->subMonths(3))->end(now());
        } else {
            $this->range = CarbonPeriod::start(now()->subMonth())->end(now());
        }
    }

    public function generate(array $params): array
    {
        $this->createTimeRange($params);
        $cacheKey = slugify($this->range->toString(), '');

        return Cache::remember(
            "reports.helpCenter.$cacheKey",
            Carbon::now()->addHours(12),
            function () {
                return [
                    'failed_searches' => $this->generateSearchReport([
                        'result_count' => 0,
                    ]),
                    'popular_searches' => $this->generateSearchReport([
                        ['result_count', '>', 0],
                    ]),
                    'popular_articles' => $this->generatePopularArticles(),
                ];
            },
        );
    }

    public function generateSearchReport(
        array $wheres,
        string $orderCol = 'count',
        string $orderDir = 'desc'
    ): Collection {
        if (!$this->range) {
            $this->createTimeRange([]);
        }

        $terms = $this->searchTerm
            ->where($wheres)
            ->whereBetween('search_terms.created_at', [
                $this->range->getStartDate(),
                $this->range->getEndDate(),
            ])
            ->select([
                'id',
                'term',
                DB::raw('max(created_at) as last_seen'),
                DB::raw('count(*) as count'),
                DB::raw('max(category_id) as category_id'),
                DB::raw('sum(created_ticket) as resulted_in_ticket'),
                DB::raw('sum(clicked_article) as clicked_article'),
            ])
            ->groupBy('normalized_term')
            ->orderBy($orderCol, $orderDir)
            ->limit(30)
            ->get();

        $terms->transform(function (SearchTerm $term) {
            $term->ctr = number_format(
                ($term->clicked_article / $term->count) * 100,
                2,
            );
            return $term;
        });

        $terms->load([
            'category' => function (BelongsTo $query) {
                return $query->select('id', 'name', 'image');
            },
        ]);

        return $terms;
    }

    private function generatePopularArticles()
    {
        $prefix = DB::getTablePrefix();
        $positive = "(SELECT count(*) FROM {$prefix}article_feedback WHERE was_helpful = 1 AND article_id = {$prefix}articles.id) as positive_votes";
        $negative = "(SELECT count(*) FROM {$prefix}article_feedback WHERE was_helpful = 0 AND article_id = {$prefix}articles.id) as negative_votes";

        return $this->article
            ->orderBy('views', 'desc')
            ->select([
                'id',
                'views',
                'slug',
                'title',
                DB::raw($positive),
                DB::raw($negative),
            ])
            ->with([
                'categories' => function (BelongsToMany $query) {
                    return $query
                        ->whereNull('parent_id')
                        ->select(['name', 'id', 'image']);
                },
            ])
            ->get()
            ->transform(function (Article $article) {
                $totalLikes =
                    $article->positive_votes + $article->negative_votes;
                $article->score = $totalLikes
                    ? round(($article->positive_votes / $totalLikes) * 100)
                    : null;
                return $article;
            });
    }
}
