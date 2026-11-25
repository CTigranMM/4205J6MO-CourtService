<?php

namespace App\Http\Requests;

use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class EnregistrerReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'terrain_id' => ['required', 'exists:terrains,id'],
            'date_heure_debut' => ['required', 'date'],
            'date_heure_fin' => ['required', 'date', 'after:date_heure_debut'],
            'statut' => ['nullable', 'in:confirmee,annulee'],
            'document' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:2048'],
        ];
    }

    /**
     * Vérifie l'absence de conflit horaire avec une réservation confirmée existante.
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($validator->errors()->hasAny(['terrain_id', 'date_heure_debut', 'date_heure_fin', 'statut'])) {
                    return;
                }

                $statut = $this->input('statut', $this->route('reservation')?->statut ?? 'confirmee');
                if ($statut !== 'confirmee') {
                    return;
                }

                $conflit = Reservation::chevaucheCreneau(
                    (int) $this->input('terrain_id'),
                    Carbon::parse((string) $this->input('date_heure_debut')),
                    Carbon::parse((string) $this->input('date_heure_fin')),
                    $this->route('reservation')?->id,
                );

                if ($conflit) {
                    $validator->errors()->add(
                        'date_heure_debut',
                        __('Ce terrain est déjà réservé sur ce créneau horaire.')
                    );
                }
            },
        ];
    }

    /**
     * Messages d'erreur
     */
    public function messages(): array
    {
        return [
            'terrain_id.required' => 'Veuillez sélectionner un terrain.',
            'terrain_id.exists' => 'Le terrain sélectionné est invalide.',
            'date_heure_debut.required' => 'La date et l\'heure de début sont requises.',
            'date_heure_debut.date' => 'La date et l\'heure de début doivent être une date valide.',
            'date_heure_fin.required' => 'La date et l\'heure de fin sont requises.',
            'date_heure_fin.date' => 'La date et l\'heure de fin doivent être une date valide.',
            'date_heure_fin.after' => 'La date et l\'heure de fin doivent être postérieures au début.',
            'statut.in' => 'Le statut doit être "confirmee" ou "annulee".',
            'document.file' => 'Le champ document doit être un fichier valide.',
            'document.mimes' => 'Le document doit être un fichier de type : jpg, jpeg, png, webp ou pdf.',
            'document.max' => 'Le document ne doit pas dépasser 2 048 Ko.',
        ];
    }
}
