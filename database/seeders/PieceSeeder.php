<?php

namespace Database\Seeders;

use App\Models\Famille;
use App\Models\Marque;
use App\Models\Piece;
use App\Models\Vehicule;
use Illuminate\Database\Seeder;

class PieceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Helper to find a vehicule by marque slug and model name
        $getVehicule = function (string $marqueSlug, string $nom) {
            $marque = Marque::where('slug', $marqueSlug)->first();
            if (!$marque) return null;
            return Vehicule::where('marque_id', $marque->id)->where('nom', $nom)->first();
        };

        // Helper to find a famille by name
        $getFamille = function (string $nom) {
            return Famille::where('nom', $nom)->first();
        };

        // Vehicles cache
        $duster = $getVehicule('dacia', 'Duster');
        $sandero = $getVehicule('dacia', 'Sandero');
        $logan = $getVehicule('dacia', 'Logan');
        $dokker = $getVehicule('dacia', 'Dokker');
        $clio4 = $getVehicule('renault', 'Clio 4');
        $clio5 = $getVehicule('renault', 'Clio 5');
        $express = $getVehicule('renault', 'Express');
        $kangoo = $getVehicule('renault', 'Kangoo');

        // Families cache
        $optique = $getFamille('OPTIQUE')?->id;
        $pareChoc = $getFamille('PARE CHOC')?->id;
        $calandre = $getFamille('CALANDRE')?->id;
        $feuArr = $getFamille('FEU ARR')?->id;
        $grille = $getFamille('GRILLE')?->id;
        $chrome = $getFamille('CHROME')?->id;
        $spoiler = $getFamille('SPOILER')?->id;
        $baguette = $getFamille('BAGUETTE')?->id;
        $catadioptre = $getFamille('CATADIOPTRE')?->id;
        $couvercle = $getFamille('COUVERCLE')?->id;
        $moustache = $getFamille('MOUSTACHE')?->id;
        $antibrouillard = $getFamille('PHARE ANTIBROUILLARD')?->id;

        $pieces = [
            // --- DACIA DUSTER ---
            [
                'vehicule_id' => $duster?->id,
                'famille_id' => $optique,
                'designation' => 'OPTIQUE DACIA DUSTER 18-',
                'annee_debut' => 2018,
                'position' => 'RL',
                'variante' => 'Standard H7',
                'prix_ttc' => 795.00,
                'dispo_droite' => true,
                'dispo_gauche' => true,
                'en_stock' => null,
                'actif' => true,
            ],
            [
                'vehicule_id' => $duster?->id,
                'famille_id' => $optique,
                'designation' => 'OPTIQUE DACIA DUSTER 22- LED',
                'annee_debut' => 2022,
                'position' => 'RL',
                'variante' => 'Signature LED',
                'prix_ttc' => 1250.00,
                'dispo_droite' => true,
                'dispo_gauche' => false, // Only Right available test case
                'en_stock' => null,
                'actif' => true,
            ],
            [
                'vehicule_id' => $duster?->id,
                'famille_id' => $pareChoc,
                'designation' => 'PARE CHOC AV DACIA DUSTER 10- +PH',
                'annee_debut' => 2010,
                'position' => 'AV',
                'variante' => 'Avec trous antibrouillard',
                'prix_ttc' => 445.00,
                'dispo_droite' => null,
                'dispo_gauche' => null,
                'en_stock' => true,
                'actif' => true,
            ],
            [
                'vehicule_id' => $duster?->id,
                'famille_id' => $pareChoc,
                'designation' => 'PARE CHOC AR DACIA DUSTER 18-',
                'annee_debut' => 2018,
                'position' => 'AR',
                'variante' => 'Grainé noir',
                'prix_ttc' => 580.00,
                'dispo_droite' => null,
                'dispo_gauche' => null,
                'en_stock' => true,
                'actif' => true,
            ],
            [
                'vehicule_id' => $duster?->id,
                'famille_id' => $calandre,
                'designation' => 'CALANDRE DACIA DUSTER 18- CHROME',
                'annee_debut' => 2018,
                'position' => 'CTRL',
                'variante' => 'Chrome',
                'prix_ttc' => 420.00,
                'dispo_droite' => null,
                'dispo_gauche' => null,
                'en_stock' => true,
                'actif' => true,
            ],
            [
                'vehicule_id' => $duster?->id,
                'famille_id' => $feuArr,
                'designation' => 'FEU ARR DACIA DUSTER 18-',
                'annee_debut' => 2018,
                'position' => 'RL',
                'variante' => 'Motif carré',
                'prix_ttc' => 430.00,
                'dispo_droite' => false,
                'dispo_gauche' => true, // Only Left available test case
                'en_stock' => null,
                'actif' => true,
            ],
            [
                'vehicule_id' => $duster?->id,
                'famille_id' => $couvercle,
                'designation' => 'COUVER PC AV DC DUSTER 18- GRIS',
                'annee_debut' => 2018,
                'position' => 'RL',
                'variante' => 'Gris satiné',
                'prix_ttc' => 115.00,
                'dispo_droite' => true,
                'dispo_gauche' => true,
                'en_stock' => null,
                'actif' => true,
            ],

            // --- DACIA SANDERO ---
            [
                'vehicule_id' => $sandero?->id,
                'famille_id' => $optique,
                'designation' => 'OPTIQUE DACIA SANDERO 13- STEPWAY',
                'annee_debut' => 2013,
                'position' => 'RL',
                'variante' => 'Fleur chrome',
                'prix_ttc' => 520.00,
                'dispo_droite' => true,
                'dispo_gauche' => true,
                'en_stock' => null,
                'actif' => true,
            ],
            [
                'vehicule_id' => $sandero?->id,
                'famille_id' => $optique,
                'designation' => 'OPTIQUE DACIA SANDERO 21- LED STEPWAY',
                'annee_debut' => 2021,
                'position' => 'RL',
                'variante' => 'Full LED Y-shape',
                'prix_ttc' => 1350.00,
                'dispo_droite' => true,
                'dispo_gauche' => true,
                'en_stock' => null,
                'actif' => true,
            ],
            [
                'vehicule_id' => $sandero?->id,
                'famille_id' => $pareChoc,
                'designation' => 'PARE CHOC AV DACIA SANDERO 17- STEPWAY',
                'annee_debut' => 2017,
                'position' => 'AV',
                'variante' => 'Avec skid plate',
                'prix_ttc' => 610.00,
                'dispo_droite' => null,
                'dispo_gauche' => null,
                'en_stock' => true,
                'actif' => true,
            ],
            [
                'vehicule_id' => $sandero?->id,
                'famille_id' => $feuArr,
                'designation' => 'FEU ARR DACIA SANDERO 21-',
                'annee_debut' => 2021,
                'position' => 'RL',
                'variante' => 'Teinté rouge',
                'prix_ttc' => 460.00,
                'dispo_droite' => true,
                'dispo_gauche' => false,
                'en_stock' => null,
                'actif' => true,
            ],
            [
                'vehicule_id' => $sandero?->id,
                'famille_id' => $grille,
                'designation' => 'GRILLE CENTRALE DACIA SANDERO 17-',
                'annee_debut' => 2017,
                'position' => 'CTRL',
                'variante' => 'Nid d\'abeille',
                'prix_ttc' => 165.00,
                'dispo_droite' => null,
                'dispo_gauche' => null,
                'en_stock' => true,
                'actif' => true,
            ],

            // --- DACIA LOGAN ---
            [
                'vehicule_id' => $logan?->id,
                'famille_id' => $optique,
                'designation' => 'OPTIQUE DACIA LOGAN 08- PH2',
                'annee_debut' => 2008,
                'position' => 'RL',
                'variante' => 'Chrome classique',
                'prix_ttc' => 380.00,
                'dispo_droite' => true,
                'dispo_gauche' => true,
                'en_stock' => null,
                'actif' => true,
            ],
            [
                'vehicule_id' => $logan?->id,
                'famille_id' => $optique,
                'designation' => 'OPTIQUE DACIA LOGAN 13-',
                'annee_debut' => 2013,
                'position' => 'RL',
                'variante' => 'Fond noir',
                'prix_ttc' => 445.00,
                'dispo_droite' => true,
                'dispo_gauche' => true,
                'en_stock' => null,
                'actif' => true,
            ],
            [
                'vehicule_id' => $logan?->id,
                'famille_id' => $pareChoc,
                'designation' => 'PARE CHOC AV DACIA LOGAN 13- S/PH',
                'annee_debut' => 2013,
                'position' => 'AV',
                'variante' => 'Sans trous antibrouillard',
                'prix_ttc' => 390.00,
                'dispo_droite' => null,
                'dispo_gauche' => null,
                'en_stock' => true,
                'actif' => true,
            ],
            [
                'vehicule_id' => $logan?->id,
                'famille_id' => $feuArr,
                'designation' => 'FEU ARR DACIA LOGAN 13-',
                'annee_debut' => 2013,
                'position' => 'RL',
                'variante' => 'Rouge/blanc standard',
                'prix_ttc' => 295.00,
                'dispo_droite' => true,
                'dispo_gauche' => true,
                'en_stock' => null,
                'actif' => true,
            ],
            [
                'vehicule_id' => $logan?->id,
                'famille_id' => $calandre,
                'designation' => 'CALANDRE DACIA LOGAN 17- CHROME',
                'annee_debut' => 2017,
                'position' => 'CTRL',
                'variante' => 'Barrettes chromées',
                'prix_ttc' => 320.00,
                'dispo_droite' => null,
                'dispo_gauche' => null,
                'en_stock' => true,
                'actif' => true,
            ],

            // --- DACIA DOKKER ---
            [
                'vehicule_id' => $dokker?->id,
                'famille_id' => $optique,
                'designation' => 'OPTIQUE DACIA DOKKER 12-',
                'annee_debut' => 2012,
                'position' => 'RL',
                'variante' => 'Chrome standard',
                'prix_ttc' => 475.00,
                'dispo_droite' => true,
                'dispo_gauche' => true,
                'en_stock' => null,
                'actif' => true,
            ],
            [
                'vehicule_id' => $dokker?->id,
                'famille_id' => $feuArr,
                'designation' => 'FEU ARR DACIA DOKKER 12-',
                'annee_debut' => 2012,
                'position' => 'RL',
                'variante' => 'Double battant',
                'prix_ttc' => 273.00,
                'dispo_droite' => true,
                'dispo_gauche' => true,
                'en_stock' => null,
                'actif' => true,
            ],
            [
                'vehicule_id' => $dokker?->id,
                'famille_id' => $grille,
                'designation' => 'GRILLE CENTRALE DACIA DOKKER 12-',
                'annee_debut' => 2012,
                'position' => 'CTRL',
                'variante' => 'Noire grainée',
                'prix_ttc' => 189.00,
                'dispo_droite' => null,
                'dispo_gauche' => null,
                'en_stock' => true,
                'actif' => true,
            ],
            [
                'vehicule_id' => $dokker?->id,
                'famille_id' => $pareChoc,
                'designation' => 'PARE CHOC AR DACIA DOKKER 12-',
                'annee_debut' => 2012,
                'position' => 'AR',
                'variante' => 'Standard',
                'prix_ttc' => 498.00,
                'dispo_droite' => null,
                'dispo_gauche' => null,
                'en_stock' => true,
                'actif' => true,
            ],
            [
                'vehicule_id' => $dokker?->id,
                'famille_id' => $calandre,
                'designation' => 'CALANDRE DACIA DOKKER 18- STEPWAY',
                'annee_debut' => 2018,
                'position' => 'CTRL',
                'variante' => 'Stepway chromée',
                'prix_ttc' => 439.00,
                'dispo_droite' => null,
                'dispo_gauche' => null,
                'en_stock' => true,
                'actif' => true,
            ],
            [
                'vehicule_id' => $dokker?->id,
                'famille_id' => $baguette,
                'designation' => 'BAGUETTE AR DC DOKKER',
                'annee_debut' => 2012,
                'position' => 'RL',
                'variante' => 'Protection latérale',
                'prix_ttc' => 289.00,
                'dispo_droite' => true,
                'dispo_gauche' => false,
                'en_stock' => null,
                'actif' => true,
            ],

            // --- RENAULT CLIO 4 ---
            [
                'vehicule_id' => $clio4?->id,
                'famille_id' => $optique,
                'designation' => 'OPTIQUE RENAULT CLIO 4 12- CHROME',
                'annee_debut' => 2012,
                'position' => 'RL',
                'variante' => 'Fond chrome H7',
                'prix_ttc' => 690.00,
                'dispo_droite' => true,
                'dispo_gauche' => true,
                'en_stock' => null,
                'actif' => true,
            ],
            [
                'vehicule_id' => $clio4?->id,
                'famille_id' => $optique,
                'designation' => 'OPTIQUE RENAULT CLIO 4 16- LED PURE VISION',
                'annee_debut' => 2016,
                'position' => 'RL',
                'variante' => 'Full LED Pure Vision',
                'prix_ttc' => 1731.00,
                'dispo_droite' => true,
                'dispo_gauche' => false, // Out of stock left test case
                'en_stock' => null,
                'actif' => true,
            ],
            [
                'vehicule_id' => $clio4?->id,
                'famille_id' => $pareChoc,
                'designation' => 'PARE CHOC AV RENAULT CLIO 4 12-',
                'annee_debut' => 2012,
                'position' => 'AV',
                'variante' => 'À peindre avec apprêt',
                'prix_ttc' => 540.00,
                'dispo_droite' => null,
                'dispo_gauche' => null,
                'en_stock' => true,
                'actif' => true,
            ],
            [
                'vehicule_id' => $clio4?->id,
                'famille_id' => $feuArr,
                'designation' => 'FEU ARR RENAULT CLIO 4 16- LED',
                'annee_debut' => 2016,
                'position' => 'RL',
                'variante' => 'Phase 2 LED',
                'prix_ttc' => 580.00,
                'dispo_droite' => true,
                'dispo_gauche' => true,
                'en_stock' => null,
                'actif' => true,
            ],
            [
                'vehicule_id' => $clio4?->id,
                'famille_id' => $moustache,
                'designation' => 'MOUSTACHE CALANDRE CLIO 4 ROUGE',
                'annee_debut' => 2012,
                'position' => 'RL',
                'variante' => 'Liseré Rouge GT Line',
                'prix_ttc' => 140.00,
                'dispo_droite' => true,
                'dispo_gauche' => true,
                'en_stock' => null,
                'actif' => true,
            ],

            // --- RENAULT CLIO 5 ---
            [
                'vehicule_id' => $clio5?->id,
                'famille_id' => $optique,
                'designation' => 'OPTIQUE RENAULT CLIO 5 19- LED C-SHAPE',
                'annee_debut' => 2019,
                'position' => 'RL',
                'variante' => 'LED Pure Vision C-Shape',
                'prix_ttc' => 1850.00,
                'dispo_droite' => true,
                'dispo_gauche' => true,
                'en_stock' => null,
                'actif' => true,
            ],
            [
                'vehicule_id' => $clio5?->id,
                'famille_id' => $feuArr,
                'designation' => 'FEU ARR RENAULT CLIO 5 19- EXT',
                'annee_debut' => 2019,
                'position' => 'RL',
                'variante' => 'Partie extérieure aile',
                'prix_ttc' => 620.00,
                'dispo_droite' => false,
                'dispo_gauche' => true,
                'en_stock' => null,
                'actif' => true,
            ],
            [
                'vehicule_id' => $clio5?->id,
                'famille_id' => $pareChoc,
                'designation' => 'PARE CHOC AV RENAULT CLIO 5 19-',
                'annee_debut' => 2019,
                'position' => 'AV',
                'variante' => 'Avec capteurs radar',
                'prix_ttc' => 780.00,
                'dispo_droite' => null,
                'dispo_gauche' => null,
                'en_stock' => true,
                'actif' => true,
            ],

            // --- RENAULT EXPRESS ---
            [
                'vehicule_id' => $express?->id,
                'famille_id' => $optique,
                'designation' => 'OPTIQUE RENAULT EXPRESS 21-',
                'annee_debut' => 2021,
                'position' => 'RL',
                'variante' => 'Halogène avec signature LED',
                'prix_ttc' => 740.00,
                'dispo_droite' => true,
                'dispo_gauche' => true,
                'en_stock' => null,
                'actif' => true,
            ],
            [
                'vehicule_id' => $express?->id,
                'famille_id' => $feuArr,
                'designation' => 'FEU ARR RENAULT EXPRESS 21-',
                'annee_debut' => 2021,
                'position' => 'RL',
                'variante' => 'Standard',
                'prix_ttc' => 380.00,
                'dispo_droite' => true,
                'dispo_gauche' => true,
                'en_stock' => null,
                'actif' => true,
            ],
            [
                'vehicule_id' => $express?->id,
                'famille_id' => $pareChoc,
                'designation' => 'PARE CHOC AV RENAULT EXPRESS 21- NOIR',
                'annee_debut' => 2021,
                'position' => 'AV',
                'variante' => 'Plastique texturé noir',
                'prix_ttc' => 560.00,
                'dispo_droite' => null,
                'dispo_gauche' => null,
                'en_stock' => true,
                'actif' => true,
            ],

            // --- RENAULT KANGOO ---
            [
                'vehicule_id' => $kangoo?->id,
                'famille_id' => $optique,
                'designation' => 'OPTIQUE RENAULT KANGOO 13- PH2',
                'annee_debut' => 2013,
                'position' => 'RL',
                'variante' => 'Glace lisse',
                'prix_ttc' => 510.00,
                'dispo_droite' => true,
                'dispo_gauche' => true,
                'en_stock' => null,
                'actif' => true,
            ],
            [
                'vehicule_id' => $kangoo?->id,
                'famille_id' => $pareChoc,
                'designation' => 'PARE CHOC AV RENAULT KANGOO 13- S/PH',
                'annee_debut' => 2013,
                'position' => 'AV',
                'variante' => 'Sans antibrouillard',
                'prix_ttc' => 450.00,
                'dispo_droite' => null,
                'dispo_gauche' => null,
                'en_stock' => false, // Out of stock single side test case
                'actif' => true,
            ],
            [
                'vehicule_id' => $kangoo?->id,
                'famille_id' => $feuArr,
                'designation' => 'FEU ARR RENAULT KANGOO 13- VINGTAUX',
                'annee_debut' => 2013,
                'position' => 'RL',
                'variante' => 'Portes battantes',
                'prix_ttc' => 310.00,
                'dispo_droite' => true,
                'dispo_gauche' => true,
                'en_stock' => null,
                'actif' => true,
            ],

            // --- PIÈCES « À CLASSER » (vehicule_id = NULL) ---
            [
                'vehicule_id' => null, // À CLASSER
                'famille_id' => $antibrouillard,
                'designation' => 'PHARE SECOUR UNIVERSEL RENAULT DACIA 13-',
                'annee_debut' => 2013,
                'position' => 'RL',
                'variante' => 'Type Valeo 90mm',
                'prix_ttc' => 195.00,
                'dispo_droite' => true,
                'dispo_gauche' => true,
                'en_stock' => null,
                'actif' => true,
            ],
            [
                'vehicule_id' => null, // À CLASSER
                'famille_id' => $catadioptre,
                'designation' => 'CATAPHOT AR UNIVERSEL MULTI-MARQUES',
                'annee_debut' => 2015,
                'position' => 'AR',
                'variante' => 'Rouge réfléchissant',
                'prix_ttc' => 85.00,
                'dispo_droite' => null,
                'dispo_gauche' => null,
                'en_stock' => true,
                'actif' => true,
            ],
        ];

        foreach ($pieces as $data) {
            Piece::create($data);
        }
    }
}
