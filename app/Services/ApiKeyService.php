<?php

namespace App\Services;

use Carbon\Carbon;
use GuzzleHttp\Promise\Create;
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
        $user = Auth::user();

        $token = $user->createToken(
            $createApiKeyDTO->name,
            ['*'],
            Carbon::now()->addDays($createApiKeyDTO->expiresAt)
        )->plainTextToken;

        return response()->json([
            'token' => $token,
        ], 201);
    }

    public function delete(string $id)
    {
        //
    }
}
