<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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

    /**
     * Get the accessible public URL for the brand logo.
     */
    protected function logoUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (empty($this->logo)) {
                    return null;
                }

                if (str_starts_with($this->logo, 'http://') || str_starts_with($this->logo, 'https://')) {
                    return $this->logo;
                }

                if (str_starts_with($this->logo, 'storage/')) {
                    return asset($this->logo);
                }

                if (str_starts_with($this->logo, '/')) {
                    return asset(ltrim($this->logo, '/'));
                }

                return asset('storage/'.$this->logo);
            }
        );
    }

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
