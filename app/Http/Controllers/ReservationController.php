<?php

namespace App\Http\Controllers;

use App\Http\Requests\EnregistrerReservationRequest;
use App\Models\Reservation;
use App\Models\Terrain;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Throwable;

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

        $data = $request->safe()->except('document');
        $path = $request->file('document')?->store('reservations', 'public');

        try {
            $reservation = Reservation::create([
                ...$data,
                'document_path' => $path,
                'user_id' => $user->id,
                'statut' => $request->validated('statut') ?? 'confirmee',
            ]);
        } catch (Throwable $exception) {
            if ($path) {
                Storage::disk('public')->delete($path);
            }
            throw $exception;
        }

        return redirect()->route('reservations.show', $reservation)
            ->with('status', __('Réservation créée avec succès.'));
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
        $data = $request->safe()->except('document');
        $newPath = $request->file('document')?->store('reservations', 'public');
        $oldPath = $reservation->document_path;

        try {
            $reservation->update([
                ...$data,
                ...($newPath ? ['document_path' => $newPath] : []),
            ]);
        } catch (Throwable $exception) {
            if ($newPath) {
                Storage::disk('public')->delete($newPath);
            }
            throw $exception;
        }

        if ($newPath && $oldPath) {
            Storage::disk('public')->delete($oldPath);
        }

        return redirect()->route('reservations.show', $reservation)
            ->with('status', __('Réservation modifiée avec succès.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation): RedirectResponse
    {
        $documentPath = $reservation->document_path;

        $reservation->delete();

        if ($documentPath) {
            Storage::disk('public')->delete($documentPath);
        }

        return redirect()->route('reservations.index')
            ->with('status', __('Réservation supprimée avec succès.'));
    }

    /**
     * Supprimer uniquement le document joint de la réservation (bouton poubelle).
     */
    public function destroyDocument(Reservation $reservation): RedirectResponse
    {
        if ($reservation->document_path) {
            Storage::disk('public')->delete($reservation->document_path);
            $reservation->update(['document_path' => null]);
        }

        return back()->with('status', __('Le document a été supprimé.'));
    }
}
