<?php namespace App\Policies;

use App\Reply;
use App\Ticket;
use App\User;
use Common\Core\Policies\BasePolicy;

class ReplyPolicy extends BasePolicy
{
    public function show(User $user, Reply $reply)
    {
        $replyBelongsToUserTicket =
            $reply->ticket &&
            $reply->ticket->user_id === $user->id &&
            $reply->type === Reply::REPLY_TYPE;
        return $replyBelongsToUserTicket ||
            $reply->user_id === $user->id ||
            $user->hasPermission('tickets.view');
    }

    public function index(User $user, Ticket $ticket)
    {
        return $ticket->user_id === $user->id ||
            $user->hasPermission('tickets.view');
    }

    public function store(User $user, Ticket $ticket)
    {
        return $ticket->user_id === $user->id ||
            $user->hasPermission('tickets.create');
    }

    public function update(User $user, ?Reply $reply)
    {
        $isDraft =
            $reply &&
            $reply->user_id === $user->id &&
            $reply->type === Reply::DRAFT_TYPE;
        return $isDraft || $user->hasPermission('tickets.update');
    }

    public function destroy(User $user, Reply $reply)
    {
        if ($user->hasPermission('tickets.delete')) {
            return true;
        }

        //if draft type is specified we should only
        //allow current user drafts to be deleted.
        return $reply->type === Reply::DRAFT_TYPE &&
            $user->id == $reply->user_id;
    }
}
