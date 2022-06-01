<?php namespace App\Services\Triggers\Actions;

use App\Action;
use App\Services\TagRepository;
use App\Ticket;
use App\Services\Ticketing\TicketRepository;
use App\Trigger;

class MoveTicketToCategoryAction implements TriggerActionInterface {

    /**
     * @var TicketRepository
     */
    private $ticketRepository;

    /**
     * @var TagRepository
     */
    private $tagRepository;

    public function __construct(TicketRepository $ticketRepository, TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
        $this->ticketRepository = $ticketRepository;
    }

    public function execute(Ticket $ticket, Action $action, Trigger $trigger): Ticket
    {
        $categoryName = json_decode($action['pivot']['action_value'])->category_name;

        $category = $this->tagRepository->updateOrCreate(['name' => $categoryName, 'type' => 'category']);

        $this->ticketRepository->addTagToTickets([$ticket->id], $category['id']);

        //'unload' tags relationship in case it was already loaded
        //on passed in ticket so removed tags are properly removed
        //the next time tags relationship is accessed on this ticket
        unset($ticket->tags);

        return $ticket;
    }
}
