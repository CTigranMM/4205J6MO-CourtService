@extends('layouts.app')

@section('title', __('Mon compte'))

@section('content')
    <div class="content-box">
        <h1>{{ __('Mon compte : :name', ['name' => $user->name]) }}</h1>
        <p><strong>{{ __('Courriel') }} :</strong> {{ $user->email }}</p>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
        <h2>{{ __('Mes réservations') }}</h2>
        <a href="{{ route('reservations.create') }}" class="btn">{{ __('+ Nouvelle réservation') }}</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>{{ __('#') }}</th>
                <th>{{ __('Terrain') }}</th>
                <th>{{ __('Début') }}</th>
                <th>{{ __('Fin') }}</th>
                <th>{{ __('Statut') }}</th>
                <th>{{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($reservations as $reservation)
                <tr>
                    <td>{{ $reservation->id }}</td>
                    <td>
                        <strong>{{ $reservation->terrain?->nom_terrain ?? __('Court non spécifié') }}</strong>
                        @if ($reservation->terrain)
                            <div style="font-size: 0.85rem; color: #6c757d;">
                                {{ $reservation->terrain->surface }} ({{ $reservation->terrain->emplacement }})
                            </div>
                        @endif
                    </td>
                    <td>{{ $reservation->date_heure_debut?->format('Y-m-d H:i') ?? 'N/A' }}</td>
                    <td>{{ $reservation->date_heure_fin?->format('Y-m-d H:i') ?? 'N/A' }}</td>
                    <td>
                        <span class="status-{{ $reservation->statut }}">
                            {{ $reservation->statut === 'confirmee' ? __('Confirmée') : __('Annulée') }}
                        </span>
                    </td>
                    <td>
                        <div class="actions-inline">
                            <a href="{{ route('reservations.show', $reservation) }}" class="btn btn-sm btn-secondary">
                                {{ __('Voir') }}
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 2rem; color: #6c757d;">
                        {{ __('Vous n\'avez aucune réservation pour le moment.') }}
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 1.5rem;">
        <a href="{{ route('reservations.index') }}" class="btn btn-secondary">{{ __('Retour à la liste') }}</a>
    </div>
@endsection

