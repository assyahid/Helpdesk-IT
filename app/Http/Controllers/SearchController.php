<?php namespace App\Http\Controllers;

use App;
use App\Article;
use App\Services\Search\SearchArticles;
use App\Services\Search\SearchTickets;
use App\Services\Search\SearchUsers;
use App\Ticket;
use App\User;
use Arr;
use Common\Core\BaseController;
use Illuminate\Http\Request;

class SearchController extends BaseController
{
    /**
     * @var Request
     */
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function all()
    {
        $params = $this->request->all();
        $results = [
            'tickets' => app(SearchTickets::class)->execute($params),
            'users' => app(SearchUsers::class)->execute(
                Arr::except($params, 'filters'),
            ),
            'articles' => app(SearchArticles::class)->execute(
                Arr::except($params, 'filters'),
            ),
        ];

        return $this->success(['results' => $results]);
    }

    public function articles()
    {
        $this->authorize('index', Article::class);

        $pagination = app(SearchArticles::class)->execute(
            $this->request->all(),
        );
        $options = [
            'prerender' => [
                'view' => 'search.index',
                'config' => 'search.index',
            ],
        ];

        return $this->success(
            [
                'pagination' => $pagination,
                'query' => $this->request->get('query'),
            ],
            200,
            $options,
        );
    }

    public function users()
    {
        $this->authorize('index', User::class);

        $pagination = app(SearchUsers::class)->execute($this->request->all());

        return $this->success([
            'pagination' => $pagination,
        ]);
    }

    public function tickets()
    {
        $this->authorize('index', Ticket::class);

        $pagination = app(SearchTickets::class)->execute($this->request->all());

       return $this->success(['pagination' => $pagination]);
    }
}
