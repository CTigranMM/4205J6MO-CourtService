<?php

use App\Http\Controllers\RechercheReservationController;
use App\Http\Controllers\ReservationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

Route::get('/', function () {
    return redirect()->route('reservations.index');
});

Route::post('/langue', function (Request $request) {
    $data = $request->validate([
        'locale' => ['required', Rule::in(['fr', 'en'])],
    ]);

    $request->session()->put('locale', $data['locale']);

    return back();
})->name('locale.update');

Route::get('/recherche/reservations', RechercheReservationController::class)->name('recherche.reservations');
Route::delete('/reservations/{reservation}/document', [ReservationController::class, 'destroyDocument'])->name('reservations.document.destroy');

Route::resource('reservations', ReservationController::class);
