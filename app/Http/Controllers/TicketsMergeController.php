<?php namespace App\Http\Controllers;

use App\Services\Ticketing\TicketRepository;
use App\Ticket;
use Common\Core\BaseController;

class TicketsMergeController extends BaseController
{
    /**
     * @var TicketRepository
     */
    private $ticketRepository;

    public function __construct(TicketRepository $ticketRepository)
    {
        $this->ticketRepository = $ticketRepository;
    }

    public function merge(int $ticket1, int $ticket2)
    {
        $this->authorize('update', Ticket::class);

        return $this->success([
            'ticket' => $this->ticketRepository->merge($ticket1, $ticket2),
        ]);
    }
}
