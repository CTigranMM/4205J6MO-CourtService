<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    /** @use HasFactory<\Database\Factories\ReservationFactory> */
    use HasFactory;

    protected $fillable = [
        'terrain_id',
        'user_id',
        'date_heure_debut',
        'date_heure_fin',
        'statut',
        'document_path',
    ];

    protected function casts(): array
    {
        return [
            'date_heure_debut' => 'datetime',
            'date_heure_fin' => 'datetime',
        ];
    }

    public function terrain(): BelongsTo
    {
        return $this->belongsTo(Terrain::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Vérifie si une réservation confirmée existe déjà sur le même terrain pour ce créneau.
     */
    public static function chevaucheCreneau(
        int $terrainId,
        CarbonInterface $debut,
        CarbonInterface $fin,
        ?int $exclureId = null,
    ): bool {
        return static::query()
            ->where('terrain_id', $terrainId)
            ->where('statut', 'confirmee')
            ->when($exclureId !== null, fn ($query) => $query->whereKeyNot($exclureId))
            ->where('date_heure_debut', '<', $fin)
            ->where('date_heure_fin', '>', $debut)
            ->exists();
    }
}
