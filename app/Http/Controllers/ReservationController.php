<?php

namespace App\Http\Controllers;

use App\Http\Requests\EnregistrerReservationRequest;
use App\Models\Reservation;
use App\Models\Terrain;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $reservations = Reservation::query()
            ->with(['terrain', 'user'])
            ->latest('date_heure_debut')
            ->paginate(10);

        return view('reservations.index', compact('reservations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $terrains = Terrain::query()
            ->orderBy('nom_terrain')
            ->get();

        return view('reservations.create', compact('terrains'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EnregistrerReservationRequest $request): RedirectResponse
    {
        // Comme dans la section 7.6 du cours, l'utilisateur est déterminé côté serveur (en attendant le chapitre 9)
        $user = User::query()->firstOrFail();

        $reservation = Reservation::create([
            ...$request->validated(),
            'user_id' => $user->id,
            'statut' => $request->validated('statut') ?? 'confirmee',
        ]);

        return redirect()->route('reservations.show', $reservation)
            ->with('status', 'Réservation créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation): View
    {
        $reservation->load(['terrain', 'user']);

        return view('reservations.show', compact('reservation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservation $reservation): View
    {
        $terrains = Terrain::query()
            ->orderBy('nom_terrain')
            ->get();

        return view('reservations.edit', compact('reservation', 'terrains'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EnregistrerReservationRequest $request, Reservation $reservation): RedirectResponse
    {
        $reservation->update($request->validated());

        return redirect()->route('reservations.show', $reservation)
            ->with('status', 'Réservation modifiée avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation): RedirectResponse
    {
        $reservation->delete();

        return redirect()->route('reservations.index')
            ->with('status', 'Réservation supprimée avec succès.');
    }
}
