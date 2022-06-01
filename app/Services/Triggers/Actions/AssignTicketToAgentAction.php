<?php namespace App\Services\Triggers\Actions;

use App\Action;
use App\Services\Ticketing\Actions\AssignTicketsToAgent;
use App\Ticket;
use App\Trigger;

class AssignTicketToAgentAction implements TriggerActionInterface
{
    public function execute(
        Ticket $ticket,
        Action $action,
        Trigger $trigger
    ): Ticket {
        $agentId = json_decode($action->pivot['action_value'])->agent_id;
        return app(AssignTicketsToAgent::class)
            ->execute([$ticket->id], $agentId)
            ->first();
    }
}
