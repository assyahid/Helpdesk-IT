<?php

namespace App\Services\Ticketing\Actions;

use App\Ticket;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class UpdateTicketTimestamps
{
    /**
     * @param array|Collection $ticketIds
     */
    public function execute($ticketIds, string $status)
    {
        $statusIsClosed = $status === 'closed' || $status === 'locked';
        Ticket::whereIn('id', $ticketIds)->update([
            'updated_at' => Carbon::now(),
            'closed_at' => $statusIsClosed ? now() : null,
            'closed_by' => $statusIsClosed ? Auth::id() : null,
        ]);
    }
}
