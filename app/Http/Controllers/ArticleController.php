<?php namespace App\Http\Controllers;

use App\Article;
use App\Http\Requests\ModifyArticles;
use App\Jobs\IncrementArticleViews;
use App\Services\HelpCenter\Actions\GenerateArticleContentNav;
use App\Services\HelpCenter\ArticleRepository;
use Auth;
use Common\Core\BaseController;
use Common\Database\Datasource\Datasource;
use Common\Settings\Settings;
use Illuminate\Http\Request;
use Str;

class ArticleController extends BaseController
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var ArticleRepository
     */
    private $repository;

    public function __construct(Request $request, ArticleRepository $repository)
    {
        $this->request = $request;
        $this->repository = $repository;
    }

    public function index()
    {
        $this->authorize('index', Article::class);

        /** @var Article $builder */
        $builder = Article::with(['categories.parent', 'tags']);

        if ($categories = $this->request->get('categories')) {
            $builder->filterByCategories($categories);
        }

        if ($tags = $this->request->get('tags')) {
            $builder->filterByTags($tags);
        }

        if ($draft = $this->request->get('draft')) {
            $builder->where('draft', (int) $draft);
        }

        //order
        $defaultOrder = app(Settings::class)->get(
            'articles.default_order',
            'position|desc',
        );
        $order = explode('|', $this->request->get('order') ?? $defaultOrder);
        $column =
            isset($order[0]) && in_array($order[0], Article::$orderFields)
                ? $order[0]
                : 'views';
        $direction = $order[1] ?? 'desc';

        // order articles by the amount of 'was helpful' user
        // feedback they have in article_feedback table
        if ($order[0] === 'was_helpful') {
            $builder->orderByFeedback($direction);
        } elseif ($order[0] === 'position') {
            $builder->orderByPosition();
        }

        // do a regular order, by a column in main articles table
        else {
            $builder->orderBy($column, $direction);
        }

        $datasource = new Datasource($builder, $this->request->all());
        $datasource->order = false;
        $pagination = $datasource->paginate();

        $pagination->transform(function ($article) {
            $article['body'] = Str::limit(
                strip_tags(html_entity_decode($article['body'])),
                200,
            );
            return $article;
        });

        return $this->success(['pagination' => $pagination]);
    }

    public function show()
    {
        $this->authorize('show', Article::class);

        $params = func_get_args();
        if (count($params) === 1) {
            $articleId = $params[0];
        } else {
            // can have child and parent category ids in params, but article
            // slug will always be last and article id the one before slug
            $articleId = (int) $params[count($params) - 2];
        }

        $article = Article::with('tags', 'uploads')
            ->withCategories($this->request->get('categories'))
            ->findOrFail($articleId);

        dispatch(
            new IncrementArticleViews(
                $article->id,
                Auth::id(),
                now()->timestamp,
            ),
        );

        return $this->success([
            'article' => $article,
            'contentNav' => app(GenerateArticleContentNav::class)->execute(
                $article,
            ),
        ]);
    }

    public function update(int $id, ModifyArticles $request)
    {
        $this->authorize('update', Article::class);

        $article = $this->repository->update($id, $this->request->all());

        cache()->forget(HelpCenterController::HC_HOME_CACHE_KEY);

        return $this->success(['article' => $article]);
    }

    public function store(ModifyArticles $request)
    {
        $this->authorize('store', Article::class);

        $article = $this->repository->create($this->request->all());

        cache()->forget(HelpCenterController::HC_HOME_CACHE_KEY);

        return $this->success(['article' => $article], 201);
    }

    public function destroy(string $ids)
    {
        $articleIds = explode(',', $ids);
        $this->authorize('destroy', Article::class);

        $this->repository->deleteMultiple($articleIds);

        cache()->forget(HelpCenterController::HC_HOME_CACHE_KEY);

        return $this->success();
    }
}
