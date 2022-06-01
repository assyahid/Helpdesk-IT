<?php namespace App\Listeners;

use App\Activity;
use App\Events\TicketCreated;
use App\Events\TicketUpdated;
use App\Notifications\TicketReceived;
use App\Services\Search\SearchTerms\AggregateSearchTerms;
use App\Services\Triggers\TriggersCycle;
use App\Ticket;
use Carbon\Carbon;
use Common\Settings\Settings;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Events\Dispatcher;

class TicketEventSubscriber implements ShouldQueue
{
    /**
     * @var TriggersCycle
     */
    private $triggersCycle;

    /**
     * @var Settings
     */
    private $settings;

    public function __construct(
        TriggersCycle $triggersCycle,
        Settings $settings
    ) {
        $this->settings = $settings;
        $this->triggersCycle = $triggersCycle;
    }

    public function onTicketCreated(TicketCreated $e)
    {
        $ticket = app(Ticket::class)->find($e->ticket->id);
        $this->triggersCycle->runAgainstTicket($ticket);
        if (!$e->createdByAgent) {
            app(AggregateSearchTerms::class)
                ->execute($e->suggestionLog)
                ->each(function ($term) use ($ticket) {
                    Activity::articlesSuggested(
                        $ticket,
                        $term['term'],
                        $term['results'],
                        isset($term['timestampMs'])
                            ? Carbon::createFromTimestamp($term['timestampMs'])
                            : null,
                    );
                });
            Activity::ticketCreated($ticket);
            if (
                $this->settings->get('tickets.send_ticket_created_notification')
            ) {
                $ticket->user->notify(new TicketReceived($ticket));
            }
        }
    }

    public function onTicketUpdated(TicketUpdated $event)
    {
        $this->triggersCycle->runAgainstTicket(
            $event->updatedTicket,
            $event->originalTicket,
        );
    }

    public function subscribe(Dispatcher $events)
    {
        $events->listen(TicketCreated::class, [self::class, 'onTicketCreated']);
        $events->listen(TicketUpdated::class, [self::class, 'onTicketUpdated']);
    }
}
