<?php

namespace Database\Seeders;

use App\Models\Marque;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MarqueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $marques = [
            [
                'nom' => 'Dacia',
                'slug' => 'dacia',
                'logo' => null,
            ],
            [
                'nom' => 'Renault',
                'slug' => 'renault',
                'logo' => null,
            ],
            [
                'nom' => 'Peugeot',
                'slug' => 'peugeot',
                'logo' => null,
            ],
            [
                'nom' => 'Fiat',
                'slug' => 'fiat',
                'logo' => null,
            ],
        ];

        foreach ($marques as $data) {
            Marque::firstOrCreate(
                ['slug' => $data['slug']],
                ['nom' => $data['nom'], 'logo' => $data['logo']]
            );
        }
    }
}
