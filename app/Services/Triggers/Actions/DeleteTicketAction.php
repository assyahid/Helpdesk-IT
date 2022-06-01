<?php namespace App\Services\Triggers\Actions;

use App\Action;
use App\Ticket;
use App\Services\Ticketing\TicketRepository;
use App\Trigger;

class DeleteTicketAction implements TriggerActionInterface {

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
        $this->ticketRepository->deleteTickets([$ticket->id]);

        return $ticket;
    }
}
