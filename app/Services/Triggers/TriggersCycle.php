<?php namespace App\Services\Triggers;

use App\Services\Triggers\Actions\Actions;
use App\Services\Triggers\Conditions\Conditions;
use App\Ticket;
use App\Trigger;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class TriggersCycle
{
    /**
     * @var TriggerRepository
     */
    private $repository;

    /**
     * @var Actions
     */
    private $actions;

    /**
     * @var Conditions
     */
    private $conditions;

    /**
     * @var array
     */
    private $alreadyFiredTriggers = [];

    /**
     * @var int
     */
    private $timesLooped = 0;

    /**
     * @var int
     */
    private $triggersFired = 0;

    public function __construct(
        TriggerRepository $repository,
        Actions $actions,
        Conditions $conditions
    ) {
        $this->repository = $repository;
        $this->actions = $actions;
        $this->conditions = $conditions;
    }

    public function runAgainstTicket(
        Ticket $updatedTicket,
        ?array $originalTicket = null,
        ?Collection $triggers = null
    ): array {
        $triggers = $triggers ?? $this->repository->all();

        $this->runCycle($triggers, $updatedTicket, $originalTicket);

        $response = [
            'timesFired' => $this->triggersFired,
            'timesLooped' => $this->timesLooped,
        ];

        $this->alreadyFiredTriggers = [];
        $this->timesLooped = 0;
        $this->triggersFired = 0;

        return $response;
    }

    public function executeTimeBasedTriggers()
    {
        $triggers = $this->repository
            ->all()
            ->filter(function (Trigger $trigger) {
                return $trigger->conditions->some('time_based', '=', true);
            });

        Ticket::with('latest_reply')
            ->whereDoesntHave('tags', function (Builder $builder) {
                $builder->where('tags.name', 'locked');
            })
            ->eachById(function (Ticket $ticket) use ($triggers) {
                $this->runAgainstTicket($ticket, null, $triggers);
            }, 500);
    }

    /**
     * Triggers cycle will run every trigger against a ticket.
     * If trigger fires and "trigger action" updates ticket, the cycle will run again
     * skipping triggers that were already checked (regardless of them actually firing)
     */
    private function runCycle(
        Collection $triggers,
        Ticket $updatedTicket,
        ?array $originalTicket = null
    ) {
        foreach ($triggers as $trigger) {
            $this->timesLooped++;

            if (
                $this->triggerShouldFire(
                    $trigger,
                    $updatedTicket,
                    $originalTicket,
                )
            ) {
                $result = $this->fireTrigger($trigger, $updatedTicket);

                if ($result['command'] === 'abort') {
                    break;
                } elseif ($result['command'] === 'continue') {
                    continue;
                } elseif ($result['command'] === 'restart') {
                    $this->runCycle(
                        $triggers,
                        $result['ticket'],
                        $originalTicket,
                    );
                    break;
                }
            }
        }
    }

    private function fireTrigger(Trigger $trigger, Ticket $updatedTicket): array
    {
        $trigger->increment('times_fired');

        $updatedTicket = $this->actions->execute($updatedTicket, $trigger);

        //mark this trigger as already 'fired' so we don't fire same triggers twice
        $this->alreadyFiredTriggers[] = $trigger->id;

        //if one of this trigger's actions updates ticket or
        //one of its relationships, we need to run all triggers
        //against updated ticket again, except triggers that already fired
        if ($this->actions->updatesTicket($trigger->actions)) {
            $command = 'restart';
        }

        //if one of this trigger's actions aborts trigger
        //cycle (for example 'delete_ticket' action), bail
        if ($this->actions->abortsCycle($trigger->actions)) {
            $command = 'abort';
        }

        $this->triggersFired++;

        return [
            'command' => $command ?? 'continue',
            'ticket' => $updatedTicket,
        ];
    }

    /**
     * Determine if given trigger should fire based on specified arguments.
     *
     * @param Trigger     $trigger
     * @param Ticket      $updatedTicket
     * @param array|null $originalTicket
     * @return bool
     */
    private function triggerShouldFire(
        Trigger $trigger,
        Ticket $updatedTicket,
        $originalTicket
    ) {
        //if this trigger has already been fired, continue to next trigger
        if (in_array($trigger->id, $this->alreadyFiredTriggers)) {
            return false;
        }

        return $this->conditions->areMet(
            $trigger,
            $updatedTicket,
            $originalTicket,
        );
    }
}
