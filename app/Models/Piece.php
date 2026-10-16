<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Piece extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicule_id',
        'famille_id',
        'designation',
        'annee_debut',
        'position',
        'variante',
        'prix_ttc',
        'dispo_droite',
        'dispo_gauche',
        'en_stock',
        'image',
        'actif',
    ];

    protected function casts(): array
    {
        return [
            'annee_debut' => 'integer',
            'prix_ttc' => 'decimal:2',
            'dispo_droite' => 'boolean',
            'dispo_gauche' => 'boolean',
            'en_stock' => 'boolean',
            'actif' => 'boolean',
        ];
    }

    public function vehicule(): BelongsTo
    {
        return $this->belongsTo(Vehicule::class);
    }

    public function famille(): BelongsTo
    {
        return $this->belongsTo(Famille::class);
    }

    /**
     * Determine whether the piece has right and left sides.
     */
    public function isDoubleCote(): bool
    {
        return $this->position === 'RL';
    }

    /**
     * Check availability for a specific side or globally.
     * $cote can be 'D', 'droite', 'G', 'gauche', 'both', or null.
     */
    public function isDisponible(?string $cote = null): bool
    {
        if (!$this->actif) {
            return false;
        }

        if ($this->isDoubleCote()) {
            $norm = strtolower((string) $cote);
            if ($norm === 'd' || $norm === 'droite' || $norm === 'droit') {
                return (bool) $this->dispo_droite;
            }
            if ($norm === 'g' || $norm === 'gauche') {
                return (bool) $this->dispo_gauche;
            }
            if ($norm === 'both' || $norm === 'les_deux' || $norm === 'les deux') {
                return (bool) ($this->dispo_droite && $this->dispo_gauche);
            }

            // Either side available
            return (bool) ($this->dispo_droite || $this->dispo_gauche);
        }

        return (bool) $this->en_stock;
    }

    /**
     * Returns true if piece is unassigned to a vehicle (À classer).
     */
    public function isAClasser(): bool
    {
        return is_null($this->vehicule_id);
    }

    /**
     * French formatted price with currency label.
     */
    protected function formattedPrix(): Attribute
    {
        return Attribute::make(
            get: fn () => number_format((float) $this->prix_ttc, 2, ',', ' ') . ' ' . config('shop.currency_label', 'DH')
        );
    }

    /**
     * Human-friendly position label.
     */
    protected function positionLabel(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match ($this->position) {
                    'RL' => 'Droite / Gauche (R/L)',
                    'AV' => 'Avant (AV)',
                    'AR' => 'Arrière (AR)',
                    'CTRL' => 'Centrale (CTRL)',
                    default => $this->position,
                };
            }
        );
    }

    /**
     * URL of the piece image or default placeholder.
     */
    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!empty($this->image)) {
                    if (str_starts_with($this->image, 'http')) {
                        return $this->image;
                    }
                    return asset('storage/' . ltrim($this->image, '/'));
                }
                return asset('images/placeholders/default.svg');
            }
        );
    }

    /**
     * URL-safe slug for piece detail pages.
     */
    public function getSlugAttribute(): string
    {
        return Str::slug($this->designation . '-' . $this->id);
    }

    /**
     * Scope to only active pieces.
     */
    public function scopeActif(Builder $query): Builder
    {
        return $query->where('actif', true);
    }

    /**
     * Scope for items pending classification (vehicule_id is null).
     */
    public function scopeAClasser(Builder $query): Builder
    {
        return $query->whereNull('vehicule_id');
    }

    /**
     * Scope for items assigned to a vehicle.
     */
    public function scopeClasse(Builder $query): Builder
    {
        return $query->whereNotNull('vehicule_id');
    }

    /**
     * Filter scope for search and criteria.
     */
    public function scopeFiltres(Builder $query, array $filtres): Builder
    {
        if (!empty($filtres['famille_id'])) {
            $query->where('famille_id', $filtres['famille_id']);
        }

        if (!empty($filtres['annee'])) {
            $query->where('annee_debut', $filtres['annee']);
        }

        if (!empty($filtres['position'])) {
            $query->where('position', $filtres['position']);
        }

        if (!empty($filtres['variante'])) {
            $query->where('variante', 'like', '%' . $filtres['variante'] . '%');
        }

        if (isset($filtres['disponible']) && $filtres['disponible'] !== '') {
            if ($filtres['disponible'] === '1' || $filtres['disponible'] === true) {
                $query->where(function ($q) {
                    $q->where(function ($sub) {
                        $sub->where('position', 'RL')
                            ->where(fn ($s) => $s->where('dispo_droite', true)->orWhere('dispo_gauche', true));
                    })->orWhere(function ($sub) {
                        $sub->where('position', '!=', 'RL')
                            ->where('en_stock', true);
                    });
                });
            } elseif ($filtres['disponible'] === '0' || $filtres['disponible'] === false) {
                $query->where(function ($q) {
                    $q->where(function ($sub) {
                        $sub->where('position', 'RL')
                            ->where('dispo_droite', false)
                            ->where('dispo_gauche', false);
                    })->orWhere(function ($sub) {
                        $sub->where('position', '!=', 'RL')
                            ->where('en_stock', false);
                    });
                });
            }
        }

        if (!empty($filtres['search'])) {
            $search = trim((string) $filtres['search']);
            $query->where(function ($q) use ($search) {
                $q->where('designation', 'like', "%{$search}%")
                  ->orWhere('variante', 'like', "%{$search}%");
            });
        }

        return $query;
    }
}
