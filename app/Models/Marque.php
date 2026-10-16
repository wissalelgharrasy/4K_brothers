<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Str;

class Marque extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'slug',
        'logo',
    ];

    protected static function booted(): void
    {
        static::creating(function (Marque $marque) {
            if (empty($marque->slug)) {
                $marque->slug = Str::slug($marque->nom);
            }
        });

        static::updating(function (Marque $marque) {
            if ($marque->isDirty('nom') && empty($marque->slug)) {
                $marque->slug = Str::slug($marque->nom);
            }
        });
    }

    public function vehicules(): HasMany
    {
        return $this->hasMany(Vehicule::class);
    }

    public function pieces(): HasManyThrough
    {
        return $this->hasManyThrough(Piece::class, Vehicule::class);
    }

    /**
     * Check if marque has associated pieces.
     */
    public function hasPieces(): bool
    {
        return $this->pieces()->exists();
    }
}
