<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConveyanceController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'showLoginForm'])
    ->middleware('guest')
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->middleware(['guest', 'throttle:login']);

Route::get('/register', [AuthController::class, 'showRegisterForm'])
    ->middleware('guest')
    ->name('register.show');

Route::post('/register', [AuthController::class, 'register'])
    ->middleware('guest')
    ->name('register');

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth', 'approved'])->group(function () {
    Route::get('/', [ConveyanceController::class, 'create'])
        ->name('conveyances.create');

    Route::post('/conveyances', [ConveyanceController::class, 'store'])
        ->middleware('throttle:conveyance-write')
        ->name('conveyances.store');

    Route::get('/conveyances', [ConveyanceController::class, 'index'])
        ->name('conveyances.index');

    Route::get('/conveyances/{conveyance}/edit', [ConveyanceController::class, 'edit'])
        ->name('conveyances.edit');

    Route::put('/conveyances/{conveyance}', [ConveyanceController::class, 'update'])
        ->middleware('throttle:conveyance-write')
        ->name('conveyances.update');

    Route::get('/conveyances/date/{date}', [ConveyanceController::class, 'showByDate'])
        ->where('date', '\d{4}-\d{2}-\d{2}')
        ->name('conveyances.showByDate');

    Route::get('/conveyances/{conveyance}', [ConveyanceController::class, 'show'])
        ->name('conveyances.show');

    Route::delete('/conveyances/{conveyance}', [ConveyanceController::class, 'destroy'])
        ->middleware('throttle:conveyance-write')
        ->name('conveyances.destroy');
});

Route::middleware(['auth', 'approved', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/users', [\App\Http\Controllers\Admin\UserApprovalController::class, 'index'])
            ->name('users.index');
        Route::post('/users/{user}/approve', [\App\Http\Controllers\Admin\UserApprovalController::class, 'approve'])
            ->name('users.approve');
    });
