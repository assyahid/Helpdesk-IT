<?php

namespace App\Notifications\Ticketing\Replies;

use App\Notifications\Ticketing\TicketingNotification;
use App\Reply;
use App\Services\UrlGenerator;
use App\Ticket;
use Auth;
use Str;

abstract class NewReplyCreatedNotif extends TicketingNotification
{
    /**
     * @var Reply
     */
    protected $reply;

    /**
     * @var Ticket
     */
    protected $ticket;

    public function __construct(Ticket $ticket, Reply $reply)
    {
        $this->reply = $reply;
        $this->ticket = $ticket;
    }
    protected function mainAction(): array
    {
        return [
            'label' => 'View Conversation',
            'action' => app(UrlGenerator::class)->ticket($this->ticket),
        ];
    }

    protected function lines(): array
    {
        return [$this->firstLine(), Str::limit($this->reply->body, 150)];
    }

    protected function firstLine(): string
    {
        $action =
            $this->reply->type === Reply::NOTE_TYPE
                ? 'added a note'
                : 'replied';

        // TODO: Auth::id will not work while in queue, need to pass it when dispatching a job
        if ($this->ticket->assigned_to === Auth::id()) {
            $line = "<strong>:user</strong> $action to your conversation #:ticketId";
        } elseif ($this->ticket->assigned_to) {
            $line = "<strong>:user</strong> $action to <strong>:assignee</strong> conversation #:ticketId";
        } else {
            $line = "<strong>:user</strong> $action to unassigned conversation #:ticketId";
        }

        return __($line, [
            'user' => $this->reply->user->display_name,
            'assignee' => $this->ticket->assigned_to
                ? $this->ticket->assignee->display_name
                : null,
            'ticketId' => $this->ticket->id,
        ]);
    }

    protected function image(): string
    {
        return $this->ticket->user->avatar;
    }
}
