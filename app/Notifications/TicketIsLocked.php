<?php

namespace App\Notifications;

use App\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketIsLocked extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var Ticket
     */
    public $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject(__('Ticket is locked'))
            ->line(
                __(
                    "Ticket your replied to ({$this->ticket->id}) was locked due to inactivity. Please create a new ticket on our support site.",
                ),
            )
            ->action(__('Create Ticket'), url('/help-center/tickets/new'));
    }
}
