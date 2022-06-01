<?php

namespace App\Services\Ticketing\Actions;

use App\Mail\TicketReply;
use App\Reply;
use App\Ticket;
use App\User;
use Common\Settings\Settings;
use Exception;
use Log;
use Mail;

class SendTicketReplyEmail
{
    public function execute(Ticket $ticket, Reply $reply, ?User $creator)
    {
        if (
            app(Settings::class)->get('replies.send_email') &&
            ($creator && $creator->isAgent())
        ) {
            try {
                Mail::send(new TicketReply($ticket, $reply));
            } catch (Exception $e) {
                Log::error($e);
            }
        }
    }
}
