<?php

namespace App\Services\Search;

use App\Article;
use App\Category;
use Arr;
use Auth;
use Common\Database\Datasource\Datasource;
use Common\Database\Datasource\DatasourceFilters;
use Common\Settings\Settings;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class SearchArticles
{
    public function execute(array $params): LengthAwarePaginator
    {
        $bodyLimit = Arr::get($params, 'bodyLimit') ?? 200;
        $categories = Arr::get($params, 'categories');
        $builder = app(Article::class)->newQuery();

        $filters = new DatasourceFilters(Arr::get($params, 'filters'));
        $filters->where('draft', '=', 0);

        if ($categories) {
            $catIds = Str::of($categories)
                ->explode(',')
                ->map(function (string $cat) {
                    return (int) trim($cat);
                });
            $filters->where('categories', 'has', $catIds);
        }

        // filter by user envato purchases
        if (
            app(Settings::class)->get('envato.filter_search') &&
            Auth::check() &&
            !Auth::user()->isSuperAdmin()
        ) {
            $itemNames = Auth::user()->purchase_codes->pluck('item_name');
            $catIds = Category::whereIn('name', $itemNames)->pluck('id');
            if ($catIds->isNotEmpty()) {
                $filters->where('categories', 'has', $catIds);
            }
        }

        $datasource = new Datasource(
            $builder,
            $params,
            $filters,
            config('scout.driver'),
        );

        $pagination = $datasource->paginate();
        $pagination->load(['categories']);
        $pagination->transform(function (Article $article) use ($bodyLimit) {
            return [
                'id' => $article->id,
                'title' => $article->title,
                'slug' => $article->slug,
                'body' => Str::limit(
                    strip_tags(html_entity_decode($article->body)),
                    $bodyLimit,
                ),
                'description' => $article->description,
                'categories' => $article->categories,
            ];
        });
        return $pagination;
    }
}
