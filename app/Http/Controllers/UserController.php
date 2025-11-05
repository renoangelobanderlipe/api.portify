<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Requests\UpdateUserRequest;

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
