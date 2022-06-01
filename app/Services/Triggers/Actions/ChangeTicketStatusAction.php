<?php namespace App\Services\Triggers\Actions;

use App\Action;
use App\Ticket;
use App\Services\Ticketing\TicketRepository;
use App\Trigger;

class ChangeTicketStatusAction implements TriggerActionInterface {

    /**
     * @var TicketRepository
     */
    private $ticketRepository;

    public function __construct(TicketRepository $ticketRepository)
    {
        $this->ticketRepository = $ticketRepository;
    }

    public function execute(Ticket $ticket, Action $action, Trigger $trigger): Ticket
    {
        $statusName = json_decode($action->pivot['action_value'])->status_name;

        $this->ticketRepository->changeStatus([$ticket->id], $statusName);

        //'unload' tags relationship in case it was already loaded
        //on passed in ticket so removed tags are properly removed
        //the next time tags/status relationship is accessed on this ticket
        unset($ticket->tags);

        return $ticket;
    }
}
