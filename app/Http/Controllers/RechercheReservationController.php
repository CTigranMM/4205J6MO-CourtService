<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RechercheReservationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $data = $request->validate([
            'q' => ['required', 'string', 'min:2', 'max:50'],
        ]);

        $reservations = Reservation::query()
            ->whereHas('terrain', function ($query) use ($data) {
                $query->where('nom_terrain', 'like', '%' . $data['q'] . '%');
            })
            ->limit(8)
            ->get();

        return response()->json([
            'data' => $reservations->map(fn (Reservation $reservation) => [
                'id' => $reservation->id,
                'label' => $reservation->terrain->nom_terrain,
                'url' => route('reservations.show', $reservation),
            ]),
        ]);
    }
}
