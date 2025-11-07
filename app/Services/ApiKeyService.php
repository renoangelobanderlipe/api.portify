<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use App\Actions\GenerateToken;
use App\DTO\ApiKey\CreateApiKeyDTO;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class ApiKeyService
{
    public function list($limit = 5)
    {
        $query = PersonalAccessToken::query();
        $tokens = $query
            ->where('tokenable_id', Auth::id())
            ->where('name', '!=', config('sanctum.token_prefix') . '_token')
            ->paginate($limit);

        return $tokens;
    }

    public function create(CreateApiKeyDTO $createApiKeyDTO)
    {
        $user = User::find(Auth::id());

        $token = app(GenerateToken::class)->execute(
            id: $user->id,
            name: $createApiKeyDTO->name,
            expiresAt: $createApiKeyDTO->expires_at
        );

        return response()->json([
            'token' => $token,
        ], 201);
    }

    public function delete(string $id)
    {
        //
    }
}
