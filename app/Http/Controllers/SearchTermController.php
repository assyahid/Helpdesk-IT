<?php

namespace App\Http\Controllers;

use App\Activity;
use App\SearchTerm;
use App\Services\Search\SearchTerms\AggregateSearchTerms;
use Auth;
use Common\Core\BaseController;

class SearchTermController extends BaseController
{
    public function storeSearchSession()
    {
        if (
            Auth::check() &&
            Auth::user()->isAgent() &&
            config('app.env') === 'production'
        ) {
            return $this->success();
        }

        if (request()->has('searchSession')) {
            $sessionData = request()->get('searchSession');
        // data might be sent via beacon API, need get it from raw post data
        } else {
            $sessionData = json_decode(request()->getContent(), true)['searchSession'];
        }

        app(AggregateSearchTerms::class)
            ->execute($sessionData)
            ->each(function ($item) {
                $searchTerm = SearchTerm::create([
                    'term' => $item['term'],
                    'normalized_term' => $item['normalized_term'],
                    'result_count' => count($item['results']),
                    'article_clicks' => (bool) $item['clickedArticle'],
                    'created_ticket' => (bool) $item['createdTicket'],
                    'category_id' => $item['categoryId'] ?? null,
                    'user_id' => Auth::id(),
                    'ip' => ip2long(getIp()),
                ]);

                if (Auth::id()) {
                    Activity::helpCenterSearched($searchTerm->id, Auth::id());
                }
            });

        return $this->success();
    }
}
