<?php

namespace App\Notifications\Ticketing\Assigned;

use App\Notifications\Ticketing\TicketingNotification;
use App\Services\UrlGenerator;
use App\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class TicketAssignedNotif extends TicketingNotification
{
    /**
     * @var Collection
     */
    protected $tickets;

    /**
     * @var User
     */
    protected $assigner;

    /**
     * @var ?User
     */
    protected $assignee;

    const NOTIF_ID = '02';

    public function __construct(
        Collection $tickets,
        User $assigner,
        User $assignee = null
    ) {
        $this->tickets = $tickets;
        $this->assigner = $assigner;
        $this->assignee = $assignee;
    }

    protected function image(): string
    {
        return $this->assigner->avatar;
    }

    protected function lines(): array
    {
        return [$this->firstLine(), $this->secondLine()];
    }

    protected function firstLine(): string
    {
        $line =
            $this->tickets->count() === 1
                ? '<strong>:assigner</strong> assigned :assignee conversion #:ticketId'
                : '<strong>:assigner</strong> assigned :assignee :count conversions';

        return __($line, [
            'assigner' => $this->assigner->display_name,
            'assignee' => $this->assignee
                ? $this->assignee->display_name
                : __('you'),
            'ticketId' => $this->tickets->first()->id,
            'count' => $this->tickets->count(),
        ]);
    }

    protected function secondLine(): string
    {
        return Str::limit(
            strip_tags($this->tickets->first()->latest_reply->body),
            150,
        );
    }

    protected function mainAction(): array
    {
        return [
            'label' => 'View Conversation',
            'action' => app(UrlGenerator::class)->ticket(
                $this->tickets->first(),
            ),
        ];
    }
}
