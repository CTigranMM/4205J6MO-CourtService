<?php

use App\Http\Controllers\RechercheReservationController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('reservations.index');
});

Route::get('/recherche/reservations', RechercheReservationController::class)->name('recherche.reservations');
Route::delete('/reservations/{reservation}/document', [ReservationController::class, 'destroyDocument'])->name('reservations.document.destroy');

Route::resource('reservations', ReservationController::class);
