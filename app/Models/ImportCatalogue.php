<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImportCatalogue extends Model
{
    use HasFactory;

    protected $table = 'import_catalogues';

    protected $fillable = [
        'user_id',
        'fichier',
        'date_import',
        'nb_lignes',
        'nb_crees',
        'nb_mis_a_jour',
        'nb_erreurs',
        'rapport_erreurs_json',
        'statut',
    ];

    protected function casts(): array
    {
        return [
            'date_import' => 'datetime',
            'nb_lignes' => 'integer',
            'nb_crees' => 'integer',
            'nb_mis_a_jour' => 'integer',
            'nb_erreurs' => 'integer',
            'rapport_erreurs_json' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtenir le nom d'affichage du fichier (sans le préfixe de hash si stocké).
     */
    public function getNomOriginalAttribute(): string
    {
        return basename($this->fichier);
    }

    /**
     * Vérifier si cet import a rencontré des erreurs.
     */
    public function hasErrors(): bool
    {
        return $this->nb_erreurs > 0 || !empty($this->rapport_erreurs_json);
    }
}
