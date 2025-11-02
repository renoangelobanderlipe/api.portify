<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Response;

class LogoutController extends Controller
{
    public function __construct(private readonly AuthService $authService) {}

    public function __invoke(): Response
    {
        return $this->authService->logout();
    }
}
