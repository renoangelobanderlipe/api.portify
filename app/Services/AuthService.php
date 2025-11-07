<?php

namespace App\Services;

use App\Models\User;
use App\DTO\Auth\LoginDTO;
use Illuminate\Support\Str;
use App\DTO\Auth\RegisterDTO;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\DTO\Auth\UpdateAccountDTO;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * Handle user login.
     */
    public function login(LoginDTO $loginDTO): array
    {
        $this->authenticate($loginDTO->toArray());

        $user = User::where('email', $loginDTO->email)->first();

        $token = $user->createToken(config('sanctum.token_prefix') . '_token')->plainTextToken;


        return [
            'access_token' => $token,
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'first_name' => $user->first_name ?? null,
                'last_name' => $user->last_name ?? null,
            ],
        ];
    }

    /**
     * Handle user logout.
     */
    public function logout(): Response
    {
        Auth::user()->currentAccessToken()->delete();

        return response()->noContent();
    }

    /**
     * List the authenticated user's details.
     */
    public function list(): JsonResponse
    {
        $user = Auth::user()->load('roles:id,name');
        $user->roles->makeHidden('pivot');

        return response()->json([
            'id' => $user->id,
            'first_name' => $user->first_name,
            'middle_name' => $user->middle_name,
            'last_name' => $user->last_name,
            'suffix' => $user->suffix,
            'email' => $user->email,
            'avatar' => $user->avatar,
            'contact_number' => $user->contact_number,
            'headline' => $user->headline,
            'bio' => $user->bio,
            'roles' => $user->roles->pluck('name'),
        ]);
    }

    /**
     * Register a new user.
     */
    public function register(RegisterDTO $registerDTO): Response
    {
        DB::transaction(function () use ($registerDTO) {
            $user = User::query()->create($registerDTO->toArray());
            $user->assignRole('user');
        });

        return response()->noContent();
    }

    public function updateAccount(UpdateAccountDTO $updateAccountDTO, int $id)
    {
        $user = User::findOrFail($id);

        if ($updateAccountDTO->avatar) {
            if ($user->avatar) {
                // Delete the old avatar file
                Storage::disk('public')->delete($user->avatar);
            }

            // Generate a unique filename
            $fileName = Str::uuid() . '.' . $updateAccountDTO->avatar->getClientOriginalExtension();

            // Save file in 'avatars' directory on public disk
            $path = Storage::disk('public')->putFileAs(
                'avatars',               // directory
                $updateAccountDTO->avatar, // UploadedFile instance
                $fileName                // filename
            );

            $user->update(['avatar' => $path]);

            $avatarUrl = Storage::url($path);

            return response()->json(['avatar_url' => $avatarUrl]);
        } else {
            $updateAccountDTO->avatar = null;
        }

        $data = collect($updateAccountDTO)->except('avatar')->toArray();

        $user->update($data);

        return response()->noContent();
    }

    public function destroy(int $id)
    {
        // But Add a validation here
        if (Auth::id() !== $id) {
            throw ValidationException::withMessages([
                'user' => 'You can only delete your own account.',
            ]);
        }

        // We need to transaction since we are deleting from multiple tables
        // to ensure that we don't leave orphaned records
        // in case of an error
        DB::transaction(function () use ($id) {
            // Check the user in the record
            $user = User::query()->findOrFail($id);

            // Delete the user account
            $user->delete();

            // Revoke all tokens
            $user->tokens()->delete();
        });

        return response()->noContent();
    }

    /**
     * Attempt to authenticate with rate-limit protection.
     */
    private function authenticate(array $credentials): void
    {
        $this->ensureIsNotRateLimited($credentials['email']);

        if (! Auth::attempt($credentials)) {
            RateLimiter::hit($this->throttleKey($credentials['email']));

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey($credentials['email']));
    }

    /**
     * Ensure the login is not rate limited.
     */
    private function ensureIsNotRateLimited(string $email): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey($email), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey($email));

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Build the throttle key.
     */
    private function throttleKey(string $email): string
    {
        return Str::transliterate(Str::lower($email) . '|' . request()->ip());
    }
}
