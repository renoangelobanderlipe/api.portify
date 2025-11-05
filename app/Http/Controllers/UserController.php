<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;

class UserController extends Controller
{
    public function __construct(private readonly UserService $userService) {}

    public function index()
    {
        return $this->userService->index();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request)
    {
        return $this->userService->update($request->toDTO());
    }

    public function destroy(string $id)
    {
        return $this->userService->destroy($id);
    }
}
