<?php namespace App\Services\Triggers\Actions;

use App;
use App\Ticket;
use App\Trigger;
use Illuminate\Support\Str;

class Actions {

    public function execute(Ticket $ticket, Trigger $trigger): Ticket
    {
        foreach($trigger->actions as $actionModel) {
            $action = $this->getAction($actionModel->name);

            $ticket = $action->execute($ticket, $actionModel, $trigger);

            //if action aborts triggers cycle (for example deletes ticket)
            //we need to bail instantly and not run any actions after it
            if ($this->abortsCycle([$actionModel])) break;
        }

        return $ticket;
    }

    public function updatesTicket(iterable $actions): bool
    {
        foreach($actions as $action) {
            if ($action['updates_ticket'] === 1) {
                return true;
            }
        }

        return false;
    }

    public function abortsCycle(iterable $actions): bool
    {
        foreach($actions as $action) {
            if ($action['aborts_cycle'] === 1) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get an instance of specified action class.
     *
     * @param string $actionName
     * @return TriggerActionInterface
     */
    protected function getAction($actionName)
    {
        $className = $this->getActionClassName($actionName);

        return App::make('App\Services\Triggers\Actions\\'.$className);
    }

    /**
     * Get class name of specified action.
     *
     * @param string $actionName
     * @return string
     */
    protected function getActionClassName($actionName)
    {
        return ucfirst(Str::camel($actionName)).'Action';
    }
}
