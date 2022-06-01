<?php namespace App\Http\Controllers;

use App\Action;
use App\Trigger;
use Common\Core\BaseController;

class ActionsController extends BaseController
{
    public function index()
    {
        $this->authorize('store', Trigger::class);

        return Action::orderBy('name', 'asc')->get();
    }
}
