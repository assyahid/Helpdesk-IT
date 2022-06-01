<?php namespace App\Services;

use App;
use App\Article;
use App\Category;
use Common\Admin\Sitemap\BaseSitemapGenerator;

class SitemapGenerator extends BaseSitemapGenerator {

    protected function getAppQueries(): array {
        return [
            app(Article::class)->where('draft', false)->where('visibility', 'public')->select(['id', 'title']),
            app(Category::class)->where('hidden', false)->select(['id', 'name']),
        ];
    }

    protected function getAppStaticUrls(): array
    {
        return [
            'help-center',
        ];
    }
}
