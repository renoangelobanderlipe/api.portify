<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\UpdateAccountRequest;
use App\Services\AuthService;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->authService->list();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RegisterRequest $request)
    {
        return $this->authService->register($request->toDTO());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAccountRequest $request, string $id)
    {
        return $this->authService->updateAccount($request->toDTO(), (int) $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->authService->destroy((int) $id);
    }
}
