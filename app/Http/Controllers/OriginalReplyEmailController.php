<?php namespace App\Http\Controllers;

use App\Services\Files\EmailStore;
use App\Services\Ticketing\ReplyRepository;
use Common\Core\BaseController;
use Illuminate\Http\JsonResponse;

class OriginalReplyEmailController extends BaseController
{
    /**
     * @var ReplyRepository
     */
    private $repository;

    /**
     * @var EmailStore
     */
    private $emailStore;

    public function __construct(EmailStore $emailStore, ReplyRepository $repository)
    {
        $this->repository = $repository;
        $this->emailStore = $emailStore;
    }

    public function show(int $replyId)
    {
        $reply = $this->repository->findOrFail($replyId);

        $this->authorize('show', $reply);

        $original = $this->emailStore->getEmailForReply($reply);

        if ( ! $original) {
            return $this->error(__('Could not find original reply.'), [], 404);
        }

        return $this->success(['email' => $original]);
    }
}
