<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)
    ->prefix('auth/user')
    ->group(function () {
        Route::post('register', 'store');
        Route::post('login', LoginController::class);
    });

Route::controller(AuthController::class)
    ->middleware(['auth:sanctum'])
    ->prefix('auth/user')
    ->group(function () {
        Route::get('/', 'index');
        Route::get('{id}', 'show');
        Route::post('logout', LogoutController::class);
        Route::post('{id}', 'update');
        Route::patch('{id}', 'update');
        Route::delete('{id}', 'destroy');
    });

Route::controller(ProjectController::class)
    ->middleware('auth:sanctum')
    ->prefix('projects')
    ->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('{project}', 'show');
        Route::post('{project}', 'update');
        Route::patch('{project}', 'update');
        Route::delete('{project}', 'destroy');
    });
