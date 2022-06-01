<?php

namespace App\Services\Triggers\Conditions\Timeframe;

use App\Services\Triggers\Conditions\BaseCondition;
use App\Ticket;
use Carbon\Carbon;

class TimeframeHoursSinceLastReplyCondition extends BaseCondition
{
    public function isMet(
        Ticket $updatedTicket,
        $originalTicket,
        $operatorName,
        $conditionValue
    ): bool {
        $hours = (int) $conditionValue;
        return $updatedTicket->latest_reply->created_at->lte(
            Carbon::now()->subHours($hours),
        );
    }
}
