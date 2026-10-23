<?php

use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('reservations.index');
});

Route::resource('reservations', ReservationController::class);
