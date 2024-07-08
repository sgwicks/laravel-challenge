<?php

use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;


Route::prefix('tickets')
    ->name('tickets')
    ->group(function () {
    Route::get('/open', [TicketController::class, 'open'])->name('open');
});
