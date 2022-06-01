<?php namespace App\Http\Controllers;

use App\Http\Requests\ModifyReplies;
use App\Reply;
use App\Services\Ticketing\ReplyRepository;
use App\Services\Ticketing\TicketReplyCreator;
use App\Services\Ticketing\TicketRepository;
use App\Ticket;
use Common\Core\BaseController;
use Illuminate\Http\Request;

class TicketRepliesController extends BaseController
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var ReplyRepository
     */
    private $replyRepository;

    /**
     * @var TicketRepository
     */
    private $ticketRepository;

    public function __construct(
        Request $request,
        ReplyRepository $replyRepository,
        TicketRepository $ticketRepository
    ) {
        $this->request = $request;
        $this->replyRepository = $replyRepository;
        $this->ticketRepository = $ticketRepository;
    }

    public function index(int $ticketId)
    {
        $ticket = Ticket::findOrFail($ticketId);

        $this->authorize('index', [Reply::class, $ticket]);

        $params = $this->request->all();
        $params['ticket_id'] = $ticketId;

        $pagination = $this->replyRepository->paginate($params);

        return $this->success(['pagination' => $pagination]);
    }

    public function store(
        int $ticketId,
        string $type,
        ModifyReplies $request,
        TicketReplyCreator $replyCreator
    ) {
        $ticket = $this->ticketRepository->find($ticketId);

        $this->authorize('store', [Reply::class, $ticket]);

        if ($ticket->status === 'locked') {
            return $this->error(
                __('This ticket is locked. To reply, create a new ticket.'),
            );
        }

        $reply = $replyCreator->create(
            $ticket,
            $request->all(),
            $type,
            Reply::SOURCE_EMAIL,
        );

        return $this->success(['reply' => $reply->toArray()], 201);
    }
}
