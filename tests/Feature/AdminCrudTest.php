<?php

namespace Tests\Feature;

use App\Models\Famille;
use App\Models\Marque;
use App\Models\Piece;
use App\Models\User;
use App\Models\Vehicule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@4kbrothers.ma',
        ]);
    }

    public function test_guest_is_redirected_to_login_when_accessing_admin_routes(): void
    {
        $this->get('/admin')->assertRedirect('/login');
        $this->get('/admin/marques')->assertRedirect('/login');
        $this->get('/admin/vehicules')->assertRedirect('/login');
        $this->get('/admin/familles')->assertRedirect('/login');
        $this->get('/admin/pieces')->assertRedirect('/login');
    }

    public function test_admin_can_view_dashboard_with_stats(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin');

        $response->assertStatus(200);
        $response->assertSee('Tableau de bord');
        $response->assertSee('admin@4kbrothers.ma');
    }

    public function test_admin_can_create_update_and_delete_marque(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/marques', [
            'nom' => 'Peugeot',
        ]);

        $response->assertRedirect('/admin/marques');
        $this->assertDatabaseHas('marques', [
            'nom' => 'Peugeot',
            'slug' => 'peugeot',
        ]);

        $marque = Marque::where('slug', 'peugeot')->first();

        $updateResponse = $this->actingAs($this->admin)->put("/admin/marques/{$marque->id}", [
            'nom' => 'Peugeot Sport',
        ]);

        $updateResponse->assertRedirect('/admin/marques');
        $this->assertDatabaseHas('marques', [
            'id' => $marque->id,
            'nom' => 'Peugeot Sport',
            'slug' => 'peugeot-sport',
        ]);

        $deleteResponse = $this->actingAs($this->admin)->delete("/admin/marques/{$marque->id}");
        $deleteResponse->assertRedirect('/admin/marques');
        $this->assertDatabaseMissing('marques', ['id' => $marque->id]);
    }

    public function test_marque_cannot_be_deleted_if_it_has_pieces_without_force(): void
    {
        $marque = Marque::create(['nom' => 'Dacia', 'slug' => 'dacia']);
        $vehicule = Vehicule::create(['marque_id' => $marque->id, 'nom' => 'Duster', 'slug' => 'duster']);
        $famille = Famille::create(['nom' => 'OPTIQUES', 'slug' => 'optiques']);

        $piece = Piece::create([
            'designation' => 'Optique Avant Duster',
            'famille_id' => $famille->id,
            'vehicule_id' => $vehicule->id,
            'annee_debut' => 2018,
            'position' => 'AV',
            'prix_ttc' => 650.00,
            'actif' => true,
        ]);

        $response = $this->actingAs($this->admin)->delete("/admin/marques/{$marque->id}");
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('marques', ['id' => $marque->id]);

        // Remove piece, then deletion succeeds
        $piece->delete();
        $vehicule->delete();

        $deleteResponse = $this->actingAs($this->admin)->delete("/admin/marques/{$marque->id}");
        $deleteResponse->assertRedirect('/admin/marques');
        $this->assertDatabaseMissing('marques', ['id' => $marque->id]);
    }

    public function test_admin_can_create_update_and_delete_vehicule(): void
    {
        $marque = Marque::create(['nom' => 'Renault', 'slug' => 'renault']);

        $createResponse = $this->actingAs($this->admin)->post('/admin/vehicules', [
            'marque_id' => $marque->id,
            'nom' => 'Megane 4',
        ]);

        $createResponse->assertRedirect('/admin/vehicules');
        $this->assertDatabaseHas('vehicules', [
            'marque_id' => $marque->id,
            'nom' => 'Megane 4',
            'slug' => 'megane-4',
        ]);

        $vehicule = Vehicule::where('slug', 'megane-4')->first();

        $updateResponse = $this->actingAs($this->admin)->put("/admin/vehicules/{$vehicule->id}", [
            'marque_id' => $marque->id,
            'nom' => 'Megane IV Phase 2',
        ]);

        $updateResponse->assertRedirect('/admin/vehicules');
        $this->assertDatabaseHas('vehicules', [
            'id' => $vehicule->id,
            'nom' => 'Megane IV Phase 2',
            'slug' => 'megane-iv-phase-2',
        ]);

        $deleteResponse = $this->actingAs($this->admin)->delete("/admin/vehicules/{$vehicule->id}");
        $deleteResponse->assertRedirect('/admin/vehicules');
        $this->assertDatabaseMissing('vehicules', ['id' => $vehicule->id]);
    }

    public function test_admin_can_create_and_update_famille(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/familles', [
            'nom' => 'rétroviseurs',
        ]);

        $response->assertRedirect('/admin/familles');
        $this->assertDatabaseHas('familles', [
            'nom' => 'RÉTROVISEURS',
            'slug' => 'retroviseurs',
        ]);

        $famille = Famille::where('slug', 'retroviseurs')->first();

        $updateResponse = $this->actingAs($this->admin)->put("/admin/familles/{$famille->id}", [
            'nom' => 'Rétroviseurs & Glaces',
        ]);

        $updateResponse->assertRedirect('/admin/familles');
        $this->assertDatabaseHas('familles', [
            'id' => $famille->id,
            'nom' => 'RÉTROVISEURS & GLACES',
        ]);
    }

    public function test_admin_can_create_edit_and_delete_piece(): void
    {
        $marque = Marque::create(['nom' => 'Renault', 'slug' => 'renault']);
        $vehicule = Vehicule::create(['marque_id' => $marque->id, 'nom' => 'Clio 5', 'slug' => 'clio-5']);
        $famille = Famille::create(['nom' => 'OPTIQUES', 'slug' => 'optiques']);

        $storeResponse = $this->actingAs($this->admin)->post('/admin/pieces', [
            'designation' => 'OPTIQUE AVANT FULL LED',
            'famille_id' => $famille->id,
            'vehicule_id' => $vehicule->id,
            'annee_debut' => 2021,
            'position' => 'RL',
            'variante' => 'TITANIUM',
            'prix_ttc' => 1450.00,
            'dispo_droite' => 1,
            'dispo_gauche' => 1,
            'actif' => 1,
        ]);

        $storeResponse->assertRedirect('/admin/pieces');
        $this->assertDatabaseHas('pieces', [
            'designation' => 'OPTIQUE AVANT FULL LED',
            'position' => 'RL',
            'dispo_droite' => 1,
            'dispo_gauche' => 1,
            'prix_ttc' => 1450.00,
            'actif' => 1,
        ]);

        $piece = Piece::where('designation', 'OPTIQUE AVANT FULL LED')->first();

        $updateResponse = $this->actingAs($this->admin)->put("/admin/pieces/{$piece->id}", [
            'designation' => 'OPTIQUE AVANT FULL LED RESTYLEE',
            'famille_id' => $famille->id,
            'vehicule_id' => $vehicule->id,
            'annee_debut' => 2022,
            'position' => 'RL',
            'variante' => 'BLACK EDITION',
            'prix_ttc' => 1550.00,
            'dispo_droite' => 1,
            'dispo_gauche' => 0,
            'actif' => 1,
        ]);

        $updateResponse->assertRedirect('/admin/pieces');
        $this->assertDatabaseHas('pieces', [
            'id' => $piece->id,
            'designation' => 'OPTIQUE AVANT FULL LED RESTYLEE',
            'dispo_droite' => 1,
            'dispo_gauche' => 0,
            'prix_ttc' => 1550.00,
        ]);

        $deleteResponse = $this->actingAs($this->admin)->delete("/admin/pieces/{$piece->id}");
        $deleteResponse->assertRedirect('/admin/pieces');
        $this->assertDatabaseMissing('pieces', ['id' => $piece->id]);
    }

    public function test_admin_can_toggle_piece_active_status(): void
    {
        $famille = Famille::create(['nom' => 'OPTIQUES', 'slug' => 'optiques']);
        $piece = Piece::create([
            'designation' => 'Feu Arrière Test',
            'famille_id' => $famille->id,
            'annee_debut' => 2020,
            'position' => 'AR',
            'prix_ttc' => 450.00,
            'actif' => true,
        ]);

        $this->assertTrue($piece->actif);

        // Toggle via standard POST
        $this->actingAs($this->admin)->post("/admin/pieces/{$piece->id}/toggle-active");
        $piece->refresh();
        $this->assertFalse($piece->actif);

        // Toggle via JSON
        $jsonResponse = $this->actingAs($this->admin)->postJson("/admin/pieces/{$piece->id}/toggle-active");
        $jsonResponse->assertStatus(200)->assertJson([
            'success' => true,
            'actif' => true,
        ]);

        $piece->refresh();
        $this->assertTrue($piece->actif);
    }

    public function test_admin_can_filter_a_classer_pieces(): void
    {
        $marque = Marque::create(['nom' => 'Renault', 'slug' => 'renault']);
        $vehicule = Vehicule::create(['marque_id' => $marque->id, 'nom' => 'Clio 4', 'slug' => 'clio-4']);
        $famille = Famille::create(['nom' => 'OPTIQUES', 'slug' => 'optiques']);

        $classified = Piece::create([
            'designation' => 'Pièce Classée Clio 4',
            'famille_id' => $famille->id,
            'vehicule_id' => $vehicule->id,
            'annee_debut' => 2016,
            'position' => 'AV',
            'prix_ttc' => 300.00,
            'actif' => true,
        ]);

        $unclassified = Piece::create([
            'designation' => 'Pièce Sans Véhicule À Classer',
            'famille_id' => $famille->id,
            'vehicule_id' => null,
            'annee_debut' => 2016,
            'position' => 'AV',
            'prix_ttc' => 300.00,
            'actif' => true,
        ]);

        $responseClasser = $this->actingAs($this->admin)->get('/admin/pieces?a_classer=1');
        $responseClasser->assertStatus(200);
        $responseClasser->assertSee('Pièce Sans Véhicule À Classer');
        $responseClasser->assertDontSee('Pièce Classée Clio 4');

        $responseNormal = $this->actingAs($this->admin)->get('/admin/pieces?a_classer=0');
        $responseNormal->assertStatus(200);
        $responseNormal->assertSee('Pièce Classée Clio 4');
        $responseNormal->assertDontSee('Pièce Sans Véhicule À Classer');
    }

    public function test_artisan_admin_create_command(): void
    {
        $this->artisan('admin:create', [
            '--name' => 'Agent Admin',
            '--email' => 'agent@4kbrothers.ma',
            '--password' => 'secretPassword123',
        ])->assertSuccessful();

        $user = User::where('email', 'agent@4kbrothers.ma')->first();
        $this->assertNotNull($user);
        $this->assertEquals('Agent Admin', $user->name);
        $this->assertTrue(Hash::check('secretPassword123', $user->password));
    }
}
