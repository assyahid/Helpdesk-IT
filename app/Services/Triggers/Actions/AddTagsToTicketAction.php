<?php namespace App\Services\Triggers\Actions;

use App\Action;
use App\Tag;
use App\Ticket;
use App\Services\TagRepository;
use App\Trigger;

class AddTagsToTicketAction implements TriggerActionInterface {

    /**
     * @var TagRepository
     */
    private $tagRepository;

    /**
     * @param TagRepository $tagRepository
     */
    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    public function execute(Ticket $ticket, Action $action, Trigger $trigger): Ticket
    {
        $tags = json_decode($action->pivot['action_value'])->tags_to_add;
        $tags = explode(',', $tags);

        $tags = app(Tag::class)->insertOrRetrieve($tags);

        $this->tagRepository->attachById($ticket, $tags->pluck('id')->toArray());

        //'unload' tags relationship in case it was already loaded
        //on passed in ticket so removed tags are properly removed
        //the next time tags relationship is accessed on this ticket
        unset($ticket->tags);

        return $ticket;
    }
}
