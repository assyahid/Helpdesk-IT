<?php namespace App\Services\Triggers\Actions;

use App\Action;
use App\Notifications\TriggerEmailAction;
use App\Ticket;
use App\Trigger;
use Common\Auth\UserRepository;

class SendEmailToUserAction implements TriggerActionInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function execute(
        Ticket $ticket,
        Action $action,
        Trigger $trigger
    ): Ticket {
        $data = json_decode($action['pivot']['action_value'], true);

        $user = $this->userRepository->findOrFail($data['agent_id']);
        $ticket->load('latest_replies');
        $data['ticket'] = $ticket->toArray();
        $data['user'] = $user->toArray();

        $user->notify(new TriggerEmailAction($data));

        return $ticket;
    }
}
