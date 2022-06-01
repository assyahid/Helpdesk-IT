<?php namespace App\Http\Controllers;

use App\Services\Mail\FailedMailHandler;
use App\Services\Mail\IncomingMailHandler;
use App\Services\Mail\Verifiers\MailWebhookVerifier;
use Common\Core\BaseController;
use Illuminate\Http\Request;

class TicketsMailController extends BaseController
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var IncomingMailHandler
     */
    private $mailHandler;

    /**
     * @var FailedMailHandler
     */
    private $failedMailHandler;

    /**
     * @var MailWebhookVerifier
     */
    private $mailWebhookVerifier;

    public function __construct(
        Request $request,
        IncomingMailHandler $mailHandler,
        FailedMailHandler $failedMailHandler,
        MailWebhookVerifier $mailWebhookVerifier
    ) {
        $this->request = $request;
        $this->mailHandler = $mailHandler;
        $this->failedMailHandler = $failedMailHandler;
        $this->mailWebhookVerifier = $mailWebhookVerifier;
    }

    public function handleIncoming()
    {
        if (!$this->mailWebhookVerifier->isValid($this->request->all())) {
            return response('', 406);
        }

        $this->mailHandler->parseEmailIntoTicketOrReply($this->request->all());

        return $this->success();
    }

    public function handleFailed()
    {
        if (!$this->mailWebhookVerifier->isValid($this->request->all())) {
            return response('', 406);
        }

        $this->failedMailHandler->createTicketForFailedEmail(
            $this->request->all(),
        );

        return $this->success();
    }
}
