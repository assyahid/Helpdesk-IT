<?php

namespace App\Services;

use App\Article;
use App\Category;
use App\Ticket;
use Arr;
use Common\Core\Prerender\BaseUrlGenerator;

class UrlGenerator extends BaseUrlGenerator
{
    /**
     * @param Ticket|array $ticket
     * @return string
     */
    public function ticket($ticket)
    {
        $tag = Arr::get($ticket, 'status') ?? 1;
        return url("mailbox/tickets/{$ticket['id']}?tagId={$tag}");
    }

    /**
     * @param array|Article $article
     */
    public function article($article): string
    {
        return url('help-center/articles') .
            "/{$article['id']}/" .
            slugify($article['title']);
    }

    /**
     * @param Category|array $category
     * @return string
     */
    public function category($category)
    {
        return url('help-center/categories') .
            "/{$category['id']}/" .
            slugify($category['name']);
    }

    /**
     * @param array $data
     * @return string
     */
    public function search($data)
    {
        return url('help-center/search') . "/{$data['query']}";
    }
}
