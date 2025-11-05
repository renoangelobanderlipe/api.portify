<?php

namespace App\Services;

use App\Models\User;
use App\DTO\User\UpdateUserDTO;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class UserService
{

    public function index()
    {
        return PersonalAccessToken::where('tokenable_id', Auth::id())
            ->where('tokenable_type', User::class)
            ->where('name', config('sanctum.token_prefix') . '_token')
            ->orderBy('created_at', 'desc')
            ->paginate(5)
        ;
    }

    public function update(UpdateUserDTO $updateUserDTO)
    {
        $user = User::findOrFail(Auth::id());

        if ($updateUserDTO->email !== null) {
            $user->email = $updateUserDTO->email;
        }

        if ($updateUserDTO->password !== null) {
            $user->password = Hash::make($updateUserDTO->password);
            $user->tokens()->delete();
        }

        $user->save();

        return response()->noContent();
    }

    public function destroy(string $id)
    {
        $personalAccessToken = PersonalAccessToken::find($id)
            ->where('tokenable_id', Auth::id())
            ->where('tokenable_type', User::class)
            ->firstOrFail();

        $personalAccessToken->delete();
    }
}
