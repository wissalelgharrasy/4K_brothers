<?php

namespace Database\Seeders;

use App\Models\Marque;
use App\Models\Vehicule;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class VehiculeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'dacia' => [
                'Duster',
                'Sandero',
                'Logan',
                'Dokker',
                'Jogger',
                'Lodgy',
            ],
            'renault' => [
                'Clio 4',
                'Clio 5',
                'Express',
                'Kangoo',
                'Megane 4',
            ],
            'peugeot' => [
                '208',
                '301',
                'Partner',
            ],
            'fiat' => [
                'Tipo',
                'Punto',
            ],
        ];

        foreach ($data as $marqueSlug => $vehicules) {
            $marque = Marque::where('slug', $marqueSlug)->first();
            if (!$marque) {
                continue;
            }

            foreach ($vehicules as $nomVehicule) {
                Vehicule::firstOrCreate(
                    [
                        'marque_id' => $marque->id,
                        'nom' => $nomVehicule,
                    ],
                    [
                        'slug' => Str::slug($nomVehicule),
                    ]
                );
            }
        }
    }
}
