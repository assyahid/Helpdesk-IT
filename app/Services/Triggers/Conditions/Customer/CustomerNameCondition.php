<?php namespace App\Services\Triggers\Conditions\Customer;

use App\Services\Triggers\Conditions\BaseCondition;
use App\Ticket;

class CustomerNameCondition extends BaseCondition
{
    /**
     * Check if ticket customer condition should be met based on specified values.
     *
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
            $updatedTicket->user->display_name,
            $conditionValue,
            $operatorName,
        );
    }
}
