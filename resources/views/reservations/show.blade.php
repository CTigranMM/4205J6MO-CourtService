@extends('layouts.app')

@section('title', 'Détail de la réservation #' . $reservation->id)

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 1rem;">
        <h2>Détail de la réservation #{{ $reservation->id }}</h2>
        <span class="status-{{ $reservation->statut }}">
            {{ $reservation->statut === 'confirmee' ? 'Confirmée' : 'Annulée' }}
        </span>
    </div>

    <div class="content-box">
        <ul class="detail-list">
            <li>
                <strong>Terrain :</strong>
                {{ $reservation->terrain?->nom_terrain ?? 'Court non spécifié' }}
            </li>
            <li>
                <strong>Caractéristiques :</strong>
                {{ $reservation->terrain?->surface }} ({{ $reservation->terrain?->emplacement }})
            </li>
            <li>
                <strong>Joueur :</strong>
                {{ $reservation->user?->name ?? 'Non assigné' }} ({{ $reservation->user?->email }})
            </li>
            <li>
                <strong>Début :</strong>
                {{ $reservation->date_heure_debut?->format('Y-m-d H:i') ?? 'N/A' }}
            </li>
            <li>
                <strong>Fin :</strong>
                {{ $reservation->date_heure_fin?->format('Y-m-d H:i') ?? 'N/A' }}
            </li>
            <li>
                <strong>Statut :</strong>
                <span class="status-{{ $reservation->statut }}">
                    {{ ucfirst($reservation->statut) }}
                </span>
            </li>
        </ul>

        <div class="actions-inline">
            <a href="{{ route('reservations.edit', $reservation) }}" class="btn">Modifier</a>

            <form method="post" action="{{ route('reservations.destroy', $reservation) }}" onsubmit="return confirm('Voulez-vous vraiment supprimer cette réservation ?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Supprimer</button>
            </form>

            <a href="{{ route('reservations.index') }}" class="btn btn-secondary">Retour à la liste</a>
        </div>
    </div>
@endsection
