<?php

namespace Tests\Feature;

use App\Models\Famille;
use App\Models\Marque;
use App\Models\Piece;
use App\Models\User;
use App\Models\Vehicule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PieceStockTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected Vehicule $vehicule;

    protected Famille $famille;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'email' => 'admin@4kbrothers.ma',
        ]);

        $marque = Marque::create(['nom' => 'Dacia', 'slug' => 'dacia']);
        $this->vehicule = Vehicule::create(['marque_id' => $marque->id, 'nom' => 'Duster', 'slug' => 'duster']);
        $this->famille = Famille::create(['nom' => 'OPTIQUE', 'slug' => 'optique']);
    }

    public function test_checkout_automatically_decrements_piece_stock(): void
    {
        $piece = Piece::create([
            'vehicule_id' => $this->vehicule->id,
            'famille_id' => $this->famille->id,
            'designation' => 'OPTIQUE AVANT TEST STOCK',
            'annee_debut' => 2020,
            'position' => 'AV',
            'prix_ttc' => 500.00,
            'stock' => 10,
            'en_stock' => true,
            'actif' => true,
        ]);

        // Add 3 units to cart
        $this->post(route('cart.add'), [
            'piece_id' => $piece->id,
            'quantite' => 3,
        ])->assertRedirect(route('cart.index'));

        // Process checkout order
        $this->post(route('checkout.process'), [
            'nom' => 'Mohamed Alami',
            'telephone' => '0600112233',
            'adresse' => 'Rabat, Agdal',
        ])->assertRedirect();

        // Verify stock has been decremented by 3 (10 - 3 = 7)
        $this->assertDatabaseHas('pieces', [
            'id' => $piece->id,
            'stock' => 7,
            'en_stock' => true,
        ]);
    }

    public function test_checkout_decrements_stock_to_zero_and_sets_rupture(): void
    {
        $piece = Piece::create([
            'vehicule_id' => $this->vehicule->id,
            'famille_id' => $this->famille->id,
            'designation' => 'CALANDRE DERNIER STOCK',
            'annee_debut' => 2021,
            'position' => 'AV',
            'prix_ttc' => 350.00,
            'stock' => 2,
            'en_stock' => true,
            'actif' => true,
        ]);

        // Add all 2 units to cart
        $this->post(route('cart.add'), [
            'piece_id' => $piece->id,
            'quantite' => 2,
        ]);

        // Process checkout
        $this->post(route('checkout.process'), [
            'nom' => 'Youssef Bennis',
            'telephone' => '0699887766',
            'adresse' => 'Casablanca, Ain Diab',
        ]);

        // Stock should now be 0, and en_stock should become false
        $piece->refresh();
        $this->assertEquals(0, $piece->stock);
        $this->assertFalse($piece->en_stock);
        $this->assertFalse($piece->isDisponible());
    }

    public function test_cannot_add_piece_with_zero_stock_to_cart(): void
    {
        $piece = Piece::create([
            'vehicule_id' => $this->vehicule->id,
            'famille_id' => $this->famille->id,
            'designation' => 'PIECE RUPTURE STOCK',
            'annee_debut' => 2019,
            'position' => 'AV',
            'prix_ttc' => 200.00,
            'stock' => 0,
            'en_stock' => false,
            'actif' => true,
        ]);

        $response = $this->post(route('cart.add'), [
            'piece_id' => $piece->id,
            'quantite' => 1,
        ]);

        $response->assertSessionHas('error');
    }

    public function test_admin_can_create_piece_with_custom_stock(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/pieces', [
            'designation' => 'PARE CHOC AR DUSTER TEST',
            'famille_id' => $this->famille->id,
            'vehicule_id' => $this->vehicule->id,
            'annee_debut' => 2020,
            'position' => 'AR',
            'prix_ttc' => 600.00,
            'stock' => 15,
            'actif' => 1,
        ]);

        $response->assertRedirect('/admin/pieces');
        $this->assertDatabaseHas('pieces', [
            'designation' => 'PARE CHOC AR DUSTER TEST',
            'stock' => 15,
        ]);
    }

    public function test_admin_can_update_stock_via_edit(): void
    {
        $piece = Piece::create([
            'vehicule_id' => $this->vehicule->id,
            'famille_id' => $this->famille->id,
            'designation' => 'PARE CHOC EDIT STOCK',
            'annee_debut' => 2020,
            'position' => 'AV',
            'prix_ttc' => 400.00,
            'stock' => 5,
            'en_stock' => true,
            'actif' => true,
        ]);

        $response = $this->actingAs($this->admin)->put("/admin/pieces/{$piece->id}", [
            'designation' => 'PARE CHOC EDIT STOCK',
            'famille_id' => $this->famille->id,
            'vehicule_id' => $this->vehicule->id,
            'annee_debut' => 2020,
            'position' => 'AV',
            'prix_ttc' => 400.00,
            'stock' => 28,
            'actif' => 1,
        ]);

        $response->assertRedirect('/admin/pieces');
        $this->assertDatabaseHas('pieces', [
            'id' => $piece->id,
            'stock' => 28,
        ]);
    }

    public function test_admin_can_quick_update_and_add_stock_via_route(): void
    {
        $piece = Piece::create([
            'vehicule_id' => $this->vehicule->id,
            'famille_id' => $this->famille->id,
            'designation' => 'OPTIQUE QUICK STOCK',
            'annee_debut' => 2021,
            'position' => 'RL',
            'prix_ttc' => 750.00,
            'stock' => 10,
            'en_stock' => true,
            'actif' => true,
        ]);

        // 1. Définir une valeur absolue
        $response1 = $this->actingAs($this->admin)->patch("/admin/pieces/{$piece->id}/stock", [
            'stock' => 40,
        ]);

        $response1->assertRedirect();
        $this->assertEquals(40, $piece->fresh()->stock);

        // 2. Ajouter du stock (réapprovisionnement +15)
        $response2 = $this->actingAs($this->admin)->patch("/admin/pieces/{$piece->id}/stock", [
            'ajout_stock' => 15,
        ]);

        $response2->assertRedirect();
        $this->assertEquals(55, $piece->fresh()->stock);
    }
}
