<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->prefix('auth/user')->group(function () {
    Route::post('register', 'store');
    Route::post('login', LoginController::class);
});

Route::middleware(['auth:sanctum'])->controller(AuthController::class)->prefix('auth/user')->group(function () {
    Route::get('/', 'index');
    Route::get('{id}', 'show');
    Route::post('logout', LogoutController::class);
    Route::post('{id}', 'update');
    Route::patch('{id}', 'update');
    Route::delete('{id}', 'destroy');
});
