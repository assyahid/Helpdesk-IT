<?php namespace App\Services\Mail\Verifiers;

use App\User;
use Auth;

class NullWebhookVerifier
{
    public function verify(array $data): bool
    {
        /** @var ?User $user */
        $user = Auth::guard('sanctum')->user();
        return $user && $user->hasPermission('tickets.update');
    }
}
