<?php namespace App\Services\Triggers\Conditions\Ticket;

use App\Services\Triggers\Conditions\BaseCondition;
use App\Ticket;

class TicketMailboxAddressCondition extends BaseCondition
{
    /**
     * @param Ticket $updatedTicket
     * @param array|null $originalTicket
     * @param string $operatorName
     * @param mixed $conditionValue
     *
     * @return bool
     */
    public function isMet(
        Ticket $updatedTicket,
        $originalTicket,
        $operatorName,
        $conditionValue
    ) {
        return $this->comparator->compare(
            $updatedTicket->received_at_email,
            $conditionValue,
            $operatorName,
        );
    }
}
