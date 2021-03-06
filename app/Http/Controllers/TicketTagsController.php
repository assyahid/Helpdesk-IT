<?php namespace App\Http\Controllers;

use App\Tag;
use App\Ticket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\TagRepository;
use App\Services\Ticketing\TicketRepository;
use Common\Core\BaseController;

class TicketTagsController extends BaseController
{
    /**
     * @var TicketRepository
     */
    private $tickets;

    /**
     * @var Request
     */
    private $request;


    public function __construct(TicketRepository $tickets, Request $request)
    {
        $this->tickets = $tickets;
        $this->request = $request;
    }

    public function add()
    {
        $this->authorize('update', Ticket::class);

        $this->validate($this->request, [
            'ids' => 'required|array',
            'tag' => 'required|string|max:255'
        ]);

        $tag = app(Tag::class)
            ->insertOrRetrieve([$this->request->get('tag')], null)[0];

        $this->tickets->addTagToTickets(
            $this->request->input('ids'),
            $tag->id
        );

        return $this->success(['data' => $tag]);
    }

    public function remove()
    {
        $this->authorize('update', Ticket::class);

        $this->validate($this->request, [
            'ids' => 'required|array',
            'tag' => 'required|integer'
        ]);

        $this->tickets->removeTagFromTickets(
            $this->request->input('ids'),
            $this->request->input('tag')
        );

        return $this->success();
    }
}
