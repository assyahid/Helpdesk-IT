<?php namespace App\Http\Controllers;

use App\Condition;
use App\Trigger;
use Common\Core\BaseController;

class ConditionsController extends BaseController
{
    public function index()
    {
        $this->authorize('store', Trigger::class);

        return Condition::with('operators')->get();
    }
}
