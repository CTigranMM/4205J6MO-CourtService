<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompteController;
use App\Http\Controllers\InscriptionController;
use App\Http\Controllers\RechercheReservationController;
use App\Http\Controllers\ReservationController;
use App\Http\Middleware\EnsureManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

Route::get('/', [ReservationController::class, 'index'])->name('home');

Route::post('/langue', function (Request $request) {
    $data = $request->validate([
        'locale' => ['required', Rule::in(['fr', 'en'])],
    ]);

    $request->session()->put('locale', $data['locale']);

    return back();
})->name('locale.update');

Route::get('/recherche/reservations', RechercheReservationController::class)->name('recherche.reservations');

Route::get('/inscription', [InscriptionController::class, 'create'])->middleware('guest')->name('register');
Route::post('/inscription', [InscriptionController::class, 'store'])->middleware('guest')->name('register.store');

Route::get('/connexion', [AuthController::class, 'create'])->middleware('guest')->name('login');
Route::post('/connexion', [AuthController::class, 'login'])->middleware(['guest', 'throttle:6,1'])->name('login.store');
Route::post('/deconnexion', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/compte', [CompteController::class, 'show'])->middleware('auth')->name('account.show');

Route::middleware(['auth', EnsureManager::class])->group(function () {
    Route::get('/administration', function () {
        $totalReservations = \App\Models\Reservation::count();
        $totalTerrains = \App\Models\Terrain::count();
        $totalUsers = \App\Models\User::count();
        $reservationsRecentes = \App\Models\Reservation::with(['terrain', 'user'])->latest()->take(5)->get();

        return view('admin.index', compact('totalReservations', 'totalTerrains', 'totalUsers', 'reservationsRecentes'));
    })->name('admin.index');
});

Route::delete('/reservations/{reservation}/document', [ReservationController::class, 'destroyDocument'])
    ->middleware('auth')
    ->name('reservations.document.destroy');

Route::resource('reservations', ReservationController::class)
    ->middlewareFor(['create', 'store', 'edit', 'update', 'destroy'], 'auth');
