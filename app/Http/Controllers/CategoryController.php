<?php namespace App\Http\Controllers;

use App\Article;
use App\Category;
use App\Http\Requests\ModifyCategories;
use Common\Core\BaseController;
use Common\Database\Datasource\Datasource;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var Category
     */
    private $category;

    public function __construct(Request $request, Category $category)
    {
        $this->request = $request;
        $this->category = $category;
    }

    public function index()
    {
        $this->authorize('index', Article::class);

        $builder = $this->category
            ->rootOnly()
            ->withCount('articles')
            ->with([
                'children' => function ($query) {
                    $query->withCount('articles');
                },
            ])
            ->orderByPosition();

        $datasource = new Datasource($builder, $this->request->all());
        $datasource->order = false;

        return $this->success(['pagination' => $datasource->paginate()]);
    }

    public function show(int $id)
    {
        $this->authorize('show', Article::class);

        $category = $this->category
            ->with('children', 'parent.children')
            ->findOrFail($id);

        return $this->success(['category' => $category]);
    }

    public function store(ModifyCategories $request)
    {
        $this->authorize('store', Article::class);

        $last = $this->category->orderBy('position', 'desc')->first();

        $category = $this->category->create([
            'name' => $this->request->get('name'),
            'description' => $this->request->get('description'),
            'parent_id' => $this->request->get('parent_id', null),
            'position' => $last ? $last->position + 1 : 1,
        ]);

        cache()->forget(HelpCenterController::HC_HOME_CACHE_KEY);

        return $this->success(['category' => $category]);
    }

    public function update(int $id, ModifyCategories $request)
    {
        $this->authorize('update', Article::class);

        $category = $this->category->findOrFail($id);

        $category->fill($this->request->all())->save();

        cache()->forget(HelpCenterController::HC_HOME_CACHE_KEY);

        return $this->success(['category' => $category]);
    }

    public function destroy(int $id)
    {
        $this->authorize('destroy', Article::class);

        $category = $this->category->findOrFail($id);

        $category
            ->where('parent_id', $category->id)
            ->update(['parent_id' => null]);
        $category->delete();

        cache()->forget(HelpCenterController::HC_HOME_CACHE_KEY);

        return $this->success();
    }
}
