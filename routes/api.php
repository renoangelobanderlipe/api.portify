<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\TechStackController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;

Route::controller(AuthController::class)
    ->prefix('auth/user')
    ->group(function () {
        Route::post('register', 'store');
        Route::post('login', LoginController::class);
    });

Route::controller(UserController::class)
    ->middleware(['auth:sanctum'])
    ->prefix('user')
    ->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::patch('/', [UserController::class, 'update']);
        Route::delete('{id}', [UserController::class, 'destroy']);
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

Route::controller(TechStackController::class)
    ->middleware('auth:sanctum')
    ->prefix('tech-stacks')
    ->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::delete('{techStack}', 'destroy');
    });
