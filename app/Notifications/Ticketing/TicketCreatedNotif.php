<?php

namespace App\Notifications\Ticketing;

use App\Services\UrlGenerator;
use App\Ticket;
use Str;

class TicketCreatedNotif extends TicketingNotification
{
    /**
     * @var Ticket
     */
    protected $ticket;

    const NOTIF_ID = '01';

    /**
     * @param Ticket $ticket
     */
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    protected function lines(): array
    {
        $category = $this->ticket->categories->first()->name;
        $subject = $this->ticket->subject;

        return [
            $this->firstLine(),
            __("Category: $category", ['category' => $category]),
            __("Subject: $subject", ['category' => $category]),
            Str::limit(strip_tags($this->ticket->latest_reply->body), 150),
        ];
    }

    protected function firstLine(): string
    {
        return __(':customer has started a new conversion #:ticketId', [
            'customer' => $this->ticket->user->display_name,
            'ticketId' => $this->ticket->id,
        ]);
    }

    protected function image(): string
    {
        return $this->ticket->user->avatar;
    }

    protected function mainAction(): array
    {
        return [
            'label' => 'View Conversation',
            'action' => app(UrlGenerator::class)->ticket($this->ticket),
        ];
    }
}
