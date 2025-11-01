<?php

namespace App\Http\Controllers\Auth;

use App\Services\AuthService;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class LogoutController extends Controller
{
    public function __construct(private readonly AuthService $authService) {}


    public function __invoke(): Response
    {
        return $this->authService->logout();
    }
}
