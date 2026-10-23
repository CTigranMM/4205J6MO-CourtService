<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Terrain extends Model
{
    /** @use HasFactory<\Database\Factories\TerrainFactory> */
    use HasFactory;

    protected $fillable = [
        'nom_terrain',
        'surface',
        'emplacement',
    ];

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
}
