<?php namespace App\Http\Controllers;

use App\Activity;
use App\Events\TicketCreated;
use App\Events\TicketUpdated;
use App\Services\Search\SearchTerms\AggregateSearchTerms;
use App\Services\Ticketing\Actions\PaginateTickets;
use App\Services\Ticketing\Actions\SendTicketReplyEmail;
use App\Services\Ticketing\TicketRepository;
use App\Ticket;
use Auth;
use Common\Core\BaseController;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;

class TicketController extends BaseController
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var TicketRepository
     */
    private $ticketRepository;

    public function __construct(
        Request $request,
        TicketRepository $ticketRepository
    ) {
        $this->request = $request;
        $this->ticketRepository = $ticketRepository;
    }

    public function index()
    {
        $this->authorize('index', [
            Ticket::class,
            $this->request->get('userId'),
        ]);

        $this->validate($this->request, [
            'tags' => 'string|min:1',
            'assigned_to' => 'integer',
        ]);

        $pagination = app(PaginateTickets::class)->execute(
            $this->request->all(),
        );

        return $this->success(['pagination' => $pagination]);
    }

    public function show(int $id)
    {
        $ticket = $this->ticketRepository->findOrFail($id);

        $this->authorize('show', $ticket);

        $ticket = $this->ticketRepository->loadConversation($ticket);

        return $this->success(['ticket' => $ticket]);
    }

    public function store()
    {
        $this->authorize('store', Ticket::class);

        $this->validate($this->request, [
            'user_id' => 'integer|exists:users,id',
            'subject' => 'required|min:3|max:255',
            'category' => 'required|integer|min:1|envatoSupportActive',
            'body' => 'required|min:3',
            'uploads' => 'array|max:10|exists:file_entries,id',
            'tags' => 'array|min:1|max:10',
            'tags.*' => 'integer|min:1',
            'created_by_agent' => 'boolean',
        ]);

        $ticket = $this->ticketRepository->create($this->request->all());
        $createdByAgent = $this->request->get('created_by_agent');

        event(
            new TicketCreated(
                $ticket,
                $createdByAgent,
                $this->request->get('suggestionLog'),
            ),
        );

        // send ticket reply email, if ticket was created by agent
        if ($createdByAgent) {
            app(SendTicketReplyEmail::class)->execute(
                $ticket,
                $ticket->latest_reply,
                request()->user(),
            );
        }

        return response($ticket, 201);
    }

    public function update(int $id)
    {
        $ticket = $this->ticketRepository->findOrFail($id);
        $this->authorize('update', $ticket);

        $this->validate($this->request, [
            'subject' => 'min:3|max:255',
            'category' => 'integer|min:1',
            'tags' => 'array|min:1|max:10',
            'tags.*' => 'integer|min:1',
        ]);

        $ticket = $this->ticketRepository->update(
            $ticket,
            $this->request->all(),
        );

        event(new TicketUpdated($ticket));

        return $this->success(['ticket' => $ticket]);
    }

    public function destroy(string $ids)
    {
        $ticketIds = explode(',', $ids);
        $this->authorize('destroy', Ticket::class);

        $this->ticketRepository->deleteTickets($ticketIds);

        return $this->success([], 204);
    }

    public function nextActiveTicket($tagId)
    {
        $this->authorize('index', Ticket::class);

        $query = app(Ticket::class)
            ->join('taggables', 'taggables.taggable_id', '=', 'tickets.id')
            ->where('taggables.taggable_type', Ticket::class)
            ->join('tags', function (JoinClause $join) {
                $join->on('tags.id', '=', 'taggables.tag_id');
                $join->on('tags.type', '=', DB::raw("'status'"));
            })
            ->select('tickets.*', 'tags.name as status')
            ->where(function (Builder $builder) {
                $builder
                    ->whereNull('assigned_to')
                    ->orWhere('assigned_to', Auth::id());
            });

        if ($tagId !== 'closed') {
            $query->whereNull('closed_at');
        }

        app(PaginateTickets::class)->filterByTag($tagId, $query);

        $ticket = $query->orderByStatus()->first();

        return $this->success(['ticket' => $ticket]);
    }
}
