<?php

namespace Tests\Feature;

use App\Models\Famille;
use App\Models\Marque;
use App\Models\Piece;
use App\Models\User;
use App\Models\Vehicule;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogModelAndSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeder_populates_required_data(): void
    {
        $this->seed(DatabaseSeeder::class);

        // Check Admin
        $this->assertDatabaseHas('users', [
            'email' => 'admin@4kbrothers.ma',
        ]);

        // Check Marques
        $this->assertDatabaseHas('marques', ['slug' => 'dacia']);
        $this->assertDatabaseHas('marques', ['slug' => 'renault']);
        $this->assertDatabaseHas('marques', ['slug' => 'peugeot']);
        $this->assertDatabaseHas('marques', ['slug' => 'fiat']);

        // Check at least 30 pieces
        $this->assertGreaterThanOrEqual(30, Piece::count());

        // Check pieces a classer (null vehicule_id)
        $this->assertGreaterThanOrEqual(1, Piece::aClasser()->count());

        // Check RL position exists
        $this->assertTrue(Piece::where('position', 'RL')->exists());
    }

    public function test_piece_double_cote_and_side_availability(): void
    {
        $famille = Famille::create(['nom' => 'OPTIQUE']);
        $marque = Marque::create(['nom' => 'Dacia']);
        $vehicule = Vehicule::create(['marque_id' => $marque->id, 'nom' => 'Duster']);

        $pieceRL = Piece::create([
            'vehicule_id' => $vehicule->id,
            'famille_id' => $famille->id,
            'designation' => 'OPTIQUE TEST RL',
            'annee_debut' => 2020,
            'position' => 'RL',
            'prix_ttc' => 500.00,
            'dispo_droite' => true,
            'dispo_gauche' => false,
            'en_stock' => null,
            'actif' => true,
        ]);

        $this->assertTrue($pieceRL->isDoubleCote());
        $this->assertTrue($pieceRL->isDisponible('droite'));
        $this->assertFalse($pieceRL->isDisponible('gauche'));
        $this->assertFalse($pieceRL->isDisponible('both'));
        $this->assertTrue($pieceRL->isDisponible()); // at least one side
    }

    public function test_piece_single_cote_availability(): void
    {
        $famille = Famille::create(['nom' => 'PARE CHOC']);
        $marque = Marque::create(['nom' => 'Renault']);
        $vehicule = Vehicule::create(['marque_id' => $marque->id, 'nom' => 'Clio 4']);

        $pieceAV = Piece::create([
            'vehicule_id' => $vehicule->id,
            'famille_id' => $famille->id,
            'designation' => 'PARE CHOC TEST AV',
            'annee_debut' => 2015,
            'position' => 'AV',
            'prix_ttc' => 450.00,
            'dispo_droite' => null,
            'dispo_gauche' => null,
            'en_stock' => true,
            'actif' => true,
        ]);

        $this->assertFalse($pieceAV->isDoubleCote());
        $this->assertTrue($pieceAV->isDisponible());
    }

    public function test_piece_formatted_prix(): void
    {
        $famille = Famille::create(['nom' => 'CALANDRE']);

        $piece = Piece::create([
            'famille_id' => $famille->id,
            'designation' => 'CALANDRE TEST',
            'annee_debut' => 2018,
            'position' => 'CTRL',
            'prix_ttc' => 1731.00,
            'en_stock' => true,
        ]);

        // Formatted price with French thousands separator and DH currency
        $this->assertStringContainsString('1 731,00', $piece->formatted_prix);
        $this->assertStringContainsString('DH', $piece->formatted_prix);
    }
}
