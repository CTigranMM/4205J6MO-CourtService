@extends('layouts.app')

@section('title', __('Administration'))

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h1>{{ __('Panneau d\'administration') }}</h1>
        <a href="{{ route('reservations.index') }}" class="btn btn-secondary">{{ __('Retour aux réservations') }}</a>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
        <div class="content-box" style="margin-bottom: 0; text-align: center;">
            <div style="font-size: 2rem; font-weight: bold; color: #1a56db;">{{ $totalReservations }}</div>
            <div style="color: #6c757d;">{{ __('Réservations totales') }}</div>
        </div>
        <div class="content-box" style="margin-bottom: 0; text-align: center;">
            <div style="font-size: 2rem; font-weight: bold; color: #198754;">{{ $totalTerrains }}</div>
            <div style="color: #6c757d;">{{ __('Courts disponibles') }}</div>
        </div>
        <div class="content-box" style="margin-bottom: 0; text-align: center;">
            <div style="font-size: 2rem; font-weight: bold; color: #0dcaf0;">{{ $totalUsers }}</div>
            <div style="color: #6c757d;">{{ __('Utilisateurs inscrits') }}</div>
        </div>
    </div>

    <div class="content-box">
        <h2>{{ __('Dernières réservations') }}</h2>
        <table>
            <thead>
                <tr>
                    <th>{{ __('Terrain') }}</th>
                    <th>{{ __('Joueur') }}</th>
                    <th>{{ __('Début') }}</th>
                    <th>{{ __('Fin') }}</th>
                    <th>{{ __('Statut') }}</th>
                    <th>{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($reservationsRecentes as $reservation)
                    <tr>
                        <td>
                            <strong>{{ $reservation->terrain?->nom_terrain }}</strong>
                            <div style="font-size: 0.85rem; color: #6c757d;">
                                {{ $reservation->terrain?->surface }} ({{ $reservation->terrain?->emplacement }})
                            </div>
                        </td>
                        <td>{{ $reservation->user?->name ?? __('Anonyme') }}</td>
                        <td>{{ $reservation->date_heure_debut?->format('Y-m-d H:i') }}</td>
                        <td>{{ $reservation->date_heure_fin?->format('Y-m-d H:i') }}</td>
                        <td>
                            <span class="status-{{ $reservation->statut }}">
                                {{ $reservation->statut === 'confirmee' ? __('Confirmée') : __('Annulée') }}
                            </span>
                        </td>
                        <td>
                            <div class="actions-inline">
                                <a href="{{ route('reservations.show', $reservation) }}" class="btn btn-sm btn-secondary">{{ __('Voir') }}</a>
                                <a href="{{ route('reservations.edit', $reservation) }}" class="btn btn-sm btn-outline">{{ __('Modifier') }}</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: #6c757d;">{{ __('Aucune réservation trouvée pour le moment.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

