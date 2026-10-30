@extends('layouts.app')

@section('title', 'Liste des réservations')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
        <h2>{{ __('Réservations') }}</h2>
        <a href="{{ route('reservations.create') }}" class="btn">{{ __('+ Nouvelle réservation') }}</a>
    </div>

    <div class="search-box">
        <label for="search" class="search-label">{{ __('Rechercher une réservation') }}</label>
        <input id="search" 
               type="search" 
               class="search-input"
               placeholder="{{ __('Court, joueur, surface') }}" 
               autocomplete="off" 
               data-search-url="{{ route('recherche.reservations') }}" 
               data-searching="{{ __('Recherche en cours...') }}" 
               data-empty="{{ __('Aucun résultat.') }}" 
               data-count="{{ __(':count résultat(s).') }}" 
               data-error="{{ __('Recherche temporairement indisponible.') }}">
        <p id="search-status" role="status" aria-live="polite" class="search-status"></p>
        <ul id="search-results" class="search-results"></ul>
    </div>

    <table>
        <thead>
            <tr>
                <th>{{ __('#') }}</th>
                <th>{{ __('Terrain') }}</th>
                <th>{{ __('Joueur') }}</th>
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
                        <strong>{{ $reservation->terrain?->nom_terrain }}</strong>
                        <div style="font-size: 0.85rem; color: #6c757d;">{{ $reservation->terrain?->surface }} ({{ $reservation->terrain?->emplacement }})</div>
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
                            <form method="post" action="{{ route('reservations.destroy', $reservation) }}" style="display: inline;" onsubmit="return confirm('{{ __('Supprimer cette réservation ?') }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">{{ __('Supprimer') }}</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 2rem; color: #6c757d;">
                        {{ __('Aucune réservation trouvée pour le moment.') }}
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination-container">
        {{ $reservations->links() }}
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/recherche.js') }}" defer></script>
@endpush
