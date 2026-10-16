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
        'nb_erreurs',
    ];

    protected function casts(): array
    {
        return [
            'date_import' => 'datetime',
            'nb_lignes' => 'integer',
            'nb_erreurs' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
