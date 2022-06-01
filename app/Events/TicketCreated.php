<?php namespace App\Events;

use App\Ticket;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;

class TicketCreated implements ShouldQueue, ShouldBroadcast
{
    use SerializesModels, InteractsWithSockets;

    /**
     * @var Ticket
     */
    public $ticket;

    /**
     * @var bool
     */
    public $createdByAgent;

    /**
     * @var array|null
     */
    public $suggestionLog;

    public function __construct(Ticket $ticket, ?bool $createdByAgent = false, ?array $suggestionLog = [])
    {
        $this->dontBroadcastToCurrentUser();

        $this->ticket = $ticket;
        $this->createdByAgent = $createdByAgent;
        $this->suggestionLog = $suggestionLog;
    }

    public function broadcastOn()
    {
        return new Channel('tickets');
    }
}
