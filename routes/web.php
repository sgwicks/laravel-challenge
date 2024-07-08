<?php

use App\Http\Controllers\TicketController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::prefix('tickets')
    ->name('tickets')
    ->group(function () {
    Route::get('/open', [TicketController::class, 'open'])->name('open');
    Route::get('/closed', [TicketController::class, 'closed'])->name('closed');
});

Route::prefix('users')
    ->name('users')
    ->group(function () {
        Route::get('{email}/tickets', [UserController::class, 'tickets'])->name('tickets');
    });

Route::get('stats', [StatsController::class, 'index'])->name('stats.index');