<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    protected $fillable = [
        'reference',
        'nom',
        'telephone',
        'adresse',
        'note',
        'items',
        'total',
        'statut',
    ];

    protected $casts = [
        'items' => 'array',
        'total' => 'decimal:2',
    ];

    /**
     * Génère une référence unique pour la commande.
     */
    public static function generateReference(): string
    {
        $date = now()->format('Ymd');
        $count = static::whereDate('created_at', today())->count() + 1;

        return sprintf('CMD-%s-%03d', $date, $count);
    }

    /**
     * Retourne le total formaté avec la devise.
     */
    public function getFormattedPrixAttribute(): string
    {
        $currency = config('shop.currency_label', 'DH');

        return number_format((float) $this->total, 2, ',', ' ').' '.$currency;
    }

    /**
     * Badge couleur selon statut (classes Tailwind).
     */
    public function getStatutBadgeAttribute(): string
    {
        return match ($this->statut) {
            'nouvelle' => 'bg-brand-red/10 text-brand-red',
            'contacté' => 'bg-amber-100 text-amber-700',
            'confirmée' => 'bg-slate-100 text-slate-700',
            'annulée' => 'bg-slate-200 text-slate-500',
            default => 'bg-slate-100 text-slate-600',
        };
    }
}
