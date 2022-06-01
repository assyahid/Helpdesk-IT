<?php namespace App\Http\Controllers;

use App\Services\Ticketing\Actions\AssignTicketsToAgent;
use App\Ticket;
use Common\Core\BaseController;
use Illuminate\Http\Request;

class TicketAssigneeController extends BaseController
{
    /**
     * @var Request
     */
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function change()
    {
        $this->authorize('update', Ticket::class);

        $this->validate($this->request, [
            'tickets' => 'required|array|min:1',
            'tickets.*' => 'required|integer',
        ]);

        app(AssignTicketsToAgent::class)->execute(
            $this->request->get('tickets'),
            $this->request->get('user_id'),
        );

        return $this->success();
    }
}
