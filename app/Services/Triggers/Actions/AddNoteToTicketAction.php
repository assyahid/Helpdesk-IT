<?php namespace App\Services\Triggers\Actions;

use App\Action;
use App\Reply;
use App\Ticket;
use App\Services\Ticketing\ReplyRepository;
use App\Trigger;

class AddNoteToTicketAction implements TriggerActionInterface {

    /**
     * @var ReplyRepository
     */
    private $replyRepository;

    /**
     * @param ReplyRepository $replyRepository
     */
    public function __construct(ReplyRepository $replyRepository)
    {
        $this->replyRepository = $replyRepository;
    }

    public function execute(Ticket $ticket, Action $action, Trigger $trigger): Ticket
    {
        $body = json_decode($action->pivot['action_value'])->note_text;

        $this->replyRepository->create([
            'body' => $body,
            'user_id' => $trigger->user_id
        ], $ticket, Reply::NOTE_TYPE);

        return $ticket;
    }
}
