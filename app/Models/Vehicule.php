<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Vehicule extends Model
{
    use HasFactory;

    protected $fillable = [
        'marque_id',
        'nom',
        'slug',
    ];

    protected static function booted(): void
    {
        static::creating(function (Vehicule $vehicule) {
            if (empty($vehicule->slug)) {
                $vehicule->slug = Str::slug($vehicule->nom);
            }
        });

        static::updating(function (Vehicule $vehicule) {
            if ($vehicule->isDirty('nom') && empty($vehicule->slug)) {
                $vehicule->slug = Str::slug($vehicule->nom);
            }
        });
    }

    public function marque(): BelongsTo
    {
        return $this->belongsTo(Marque::class);
    }

    public function pieces(): HasMany
    {
        return $this->hasMany(Piece::class);
    }

    public function hasPieces(): bool
    {
        return $this->pieces()->exists();
    }
}
