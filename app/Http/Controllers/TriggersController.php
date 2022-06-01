<?php namespace App\Http\Controllers;

use App\Http\Requests\ModifyTriggers;
use App\Services\Triggers\TriggerRepository;
use App\Trigger;
use Common\Core\BaseController;
use Common\Database\Datasource\Datasource;
use Illuminate\Http\Request;

class TriggersController extends BaseController
{
    /**
     * @var TriggerRepository $trigger
     */
    private $repository;

    /**
     * @var Request
     */
    private $request;

    public function __construct(TriggerRepository $repository, Request $request)
    {
        $this->repository = $repository;
        $this->request = $request;
    }

    public function index()
    {
        $this->authorize('index', Trigger::class);

        $datasource = new Datasource(
            app(Trigger::class),
            $this->request->all(),
        );

        return $this->success(['pagination' => $datasource->paginate()]);
    }

    public function show(int $id)
    {
        $this->authorize('index', Trigger::class);

        return $this->success(['data' => $this->repository->findOrFail($id)]);
    }

    public function store(ModifyTriggers $request)
    {
        $this->authorize('store', Trigger::class);

        return response($this->repository->create($this->request->all()), 201);
    }

    public function update(int $id, ModifyTriggers $request)
    {
        $this->authorize('update', Trigger::class);

        return $this->repository->update($id, $this->request->all());
    }

    public function destroy()
    {
        $this->authorize('destroy', Trigger::class);

        $this->validate($this->request, [
            'ids' => 'required|array|min:1',
            'ids.*' => 'required|integer',
        ]);

        return response(
            $this->repository->delete($this->request->get('ids')),
            204,
        );
    }
}
