<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Famille extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'slug',
    ];

    protected static function booted(): void
    {
        static::creating(function (Famille $famille) {
            if (empty($famille->slug)) {
                $famille->slug = Str::slug($famille->nom);
            }
        });

        static::updating(function (Famille $famille) {
            if ($famille->isDirty('nom') && empty($famille->slug)) {
                $famille->slug = Str::slug($famille->nom);
            }
        });
    }

    public function pieces(): HasMany
    {
        return $this->hasMany(Piece::class);
    }
}
