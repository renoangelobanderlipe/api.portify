<?php

namespace App\Actions;

use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;

class GenerateToken
{
    public function execute(
        string $id,
        ?string $name,
        ?array $abilities = ['*'],
        ?Carbon $expiresAt = null,
    ) {
        $this->isUserAuthorized();

        $user = User::findOrFail($id);

        $token = $user->createToken(
            $name,
            $abilities,
            $expiresAt
        )->plainTextToken;

        return $token;
    }

    public function isUserAuthorized(): bool
    {
        $authUser = Auth::user();

        $userRoles = $authUser->getRoleNames()->first();

        if (
            ! $authUser ||
            ! $authUser->tokenCan('create-tokens') ||
            $userRoles !== 'admin'
        ) {
            throw new Exception('Unauthorized');
        }

        return true;
    }
}
