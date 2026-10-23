@extends('layouts.app')

@section('title', 'Liste des réservations')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
        <h2>Réservations</h2>
        <a href="{{ route('reservations.create') }}" class="btn">+ Nouvelle réservation</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Terrain</th>
                <th>Joueur</th>
                <th>Début</th>
                <th>Fin</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($reservations as $reservation)
                <tr>
                    <td>{{ $reservation->id }}</td>
                    <td>
                        <strong>{{ $reservation->terrain?->nom_terrain }}</strong>
                        <div style="font-size: 0.85rem; color: #6c757d;">{{ $reservation->terrain?->surface }} ({{ $reservation->terrain?->emplacement }})</div>
                    </td>
                    <td>{{ $reservation->user?->name ?? 'Anonyme' }}</td>
                    <td>{{ $reservation->date_heure_debut?->format('Y-m-d H:i') }}</td>
                    <td>{{ $reservation->date_heure_fin?->format('Y-m-d H:i') }}</td>
                    <td>
                        <span class="status-{{ $reservation->statut }}">
                            {{ $reservation->statut === 'confirmee' ? 'Confirmée' : 'Annulée' }}
                        </span>
                    </td>
                    <td>
                        <div class="actions-inline">
                            <a href="{{ route('reservations.show', $reservation) }}" class="btn btn-sm btn-secondary">Voir</a>
                            <a href="{{ route('reservations.edit', $reservation) }}" class="btn btn-sm btn-outline">Modifier</a>
                            <form method="post" action="{{ route('reservations.destroy', $reservation) }}" style="display: inline;" onsubmit="return confirm('Supprimer cette réservation ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 2rem; color: #6c757d;">
                        Aucune réservation trouvée pour le moment.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination-container">
        {{ $reservations->links() }}
    </div>
@endsection
