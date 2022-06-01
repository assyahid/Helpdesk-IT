<?php

namespace App\Services\Search;

use App\User;
use Common\Database\Datasource\Datasource;
use Illuminate\Pagination\LengthAwarePaginator;

class SearchUsers
{
    public function execute(array $params): LengthAwarePaginator
    {
        $datasource = new Datasource(
            app(User::class)->newQuery(),
            $params,
            null,
            config('scout.driver'),
        );

        return $datasource->paginate();
    }
}
