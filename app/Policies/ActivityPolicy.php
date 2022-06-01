<?php namespace App\Policies;

use App\Ticket;
use App\User;
use Common\Core\Policies\BasePolicy;
use Illuminate\Database\Eloquent\Collection;

class ActivityPolicy extends BasePolicy
{
    public function index(User $user)
    {
        return $user->hasPermission('tickets.view');
    }

    public function store(User $user)
    {
        return true;
    }

    public function destroy(User $user)
    {
        return $user->hasPermission('tickets.delete');
    }
}
