<?php

namespace App\Services\Search;

use App\Ticket;
use Arr;
use Common\Database\Datasource\Datasource;
use Illuminate\Pagination\LengthAwarePaginator;

class SearchTickets
{
    public function execute(array $params): LengthAwarePaginator
    {
        $builder = app(Ticket::class)->newQuery();

        if (Arr::get($params, 'detailed')) {
            $builder->with(['user', 'tags']);
            $builder->withCount(['replies']);
        }

        $datasource = new Datasource(
            $builder,
            $params,
            null,
            config('scout.driver'),
        );

        $pagination = $datasource->paginate();

        $pagination->load(['latest_reply']);
        $pagination->each(function (Ticket $ticket) {
            if ($ticket->latest_reply) {
                $ticket->latest_reply->stripBody();
            }
        });

        return $pagination;
    }
}
