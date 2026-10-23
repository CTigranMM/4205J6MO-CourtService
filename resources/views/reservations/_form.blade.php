@csrf

<div class="form-group">
    <label for="terrain_id">Terrain</label>
    <select id="terrain_id" name="terrain_id">
        <option value="">-- Sélectionnez un court --</option>
        @foreach ($terrains as $terrain)
            <option value="{{ $terrain->id }}" {{ old('terrain_id', $reservation?->terrain_id) == $terrain->id ? 'selected' : '' }}>
                {{ $terrain->nom_terrain }} ({{ $terrain->surface }} - {{ $terrain->emplacement }})
            </option>
        @endforeach
    </select>
    @error('terrain_id')
        <p class="error-message">{{ $message }}</p>
    @enderror
</div>

<div class="form-group">
    <label for="date_heure_debut">Date et heure de début</label>
    <input 
        type="datetime-local" 
        id="date_heure_debut" 
        name="date_heure_debut" 
        value="{{ old('date_heure_debut', $reservation?->date_heure_debut?->format('Y-m-d\TH:i')) }}"
    >
    @error('date_heure_debut')
        <p class="error-message">{{ $message }}</p>
    @enderror
</div>

<div class="form-group">
    <label for="date_heure_fin">Date et heure de fin</label>
    <input 
        type="datetime-local" 
        id="date_heure_fin" 
        name="date_heure_fin" 
        value="{{ old('date_heure_fin', $reservation?->date_heure_fin?->format('Y-m-d\TH:i')) }}"
    >
    @error('date_heure_fin')
        <p class="error-message">{{ $message }}</p>
    @enderror
</div>

<div class="form-group">
    <label for="statut">Statut</label>
    <select id="statut" name="statut">
        <option value="confirmee" {{ old('statut', $reservation?->statut ?? 'confirmee') === 'confirmee' ? 'selected' : '' }}>
            Confirmée
        </option>
        <option value="annulee" {{ old('statut', $reservation?->statut) === 'annulee' ? 'selected' : '' }}>
            Annulée
        </option>
    </select>
    @error('statut')
        <p class="error-message">{{ $message }}</p>
    @enderror
</div>

<div class="actions-inline" style="margin-top: 1.5rem;">
    <button type="submit" class="btn">Enregistrer</button>
    <a href="{{ route('reservations.index') }}" class="btn btn-secondary">Annuler</a>
</div>
