<?php

namespace App\Http\Controllers\Auth;

use App\Services\AuthService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;

class LoginController extends Controller
{
    public function __construct(private readonly AuthService $authService) {}

    public function __invoke(LoginRequest $request)
    {
        return $this->authService->login($request->toDTO());
    }
}
