<?php

namespace Database\Seeders;

use App\Models\Famille;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FamilleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $familles = [
            'OPTIQUE',
            'PARE CHOC',
            'CALANDRE',
            'FEU ARR',
            'FEU AV',
            'GRILLE',
            'CHROME',
            'SPOILER',
            'BAGUETTE',
            'CATADIOPTRE',
            'MOUSTACHE',
            'COUVERCLE',
            'FIXATION',
            'MASQUE',
            'SABOT',
            'PHARE ANTIBROUILLARD',
            'CACHE ROUE',
            'ENTOURAGE',
            'LED',
        ];

        foreach ($familles as $nom) {
            Famille::firstOrCreate(
                ['slug' => Str::slug($nom)],
                ['nom' => $nom]
            );
        }
    }
}
