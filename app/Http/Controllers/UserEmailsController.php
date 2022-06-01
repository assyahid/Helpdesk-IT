<?php namespace App\Http\Controllers;

use App\User;
use App\Email;
use Illuminate\Http\Request;
use Common\Core\BaseController;

class UserEmailsController extends BaseController
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var User
     */
    private $user;

    public function __construct(Request $request, User $user)
    {
        $this->request = $request;
        $this->user = $user;
    }

    public function attach(int $userId)
    {
        $this->authorize('update', User::class);

        $user = $this->user->findOrFail($userId);

        $this->validate($this->request, [
            'emails'   => 'required|array|min:1',
            'emails.*' => 'required|string|email|unique:emails,address|unique:users,email',
        ]);

        collect($this->request->get('emails'))->each(function($email) use($user) {
            $user->secondary_emails()->create(['address' => $email]);
        });

        return $this->success(['user' => $user]);
    }

    public function detach(int $userId)
    {
        $this->authorize('update', User::class);

        $this->validate($this->request, [
            'emails'   => 'required|array|min:1',
            'emails.*' => 'required|string|email|exists:emails,address',
        ]);

        $user = $this->user->with('secondary_emails')->findOrFail($userId);

        $user->secondary_emails->each(function($email) {
            if (collect($this->request->get('emails'))->contains($email->address)) {
                $email->delete();
            }
        });

        return $this->success(['user' => $user]);
    }
}
