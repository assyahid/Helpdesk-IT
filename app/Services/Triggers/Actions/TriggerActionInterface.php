<?php namespace App\Services\Triggers\Actions;

use App\Action;
use App\Ticket;
use App\Trigger;

interface TriggerActionInterface {
    public function execute(Ticket $ticket, Action $action, Trigger $trigger): Ticket;
}
