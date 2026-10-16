<?php

namespace Tests\Feature;

use App\Models\Marque;
use App\Models\Piece;
use App\Models\Vehicule;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicCatalogAndCartTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_homepage_is_accessible_and_shows_brands(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('4K BROTHERS');
        $response->assertSee('Dacia');
        $response->assertSee('Renault');
    }

    public function test_marque_page_lists_vehicules(): void
    {
        $response = $this->get(route('catalog.marque', 'dacia'));

        $response->assertStatus(200);
        $response->assertSee('Dacia');
        $response->assertSee('Duster');
        $response->assertSee('Sandero');
        $response->assertSee('Logan');
    }

    public function test_vehicule_page_lists_pieces_and_allows_filtering(): void
    {
        $response = $this->get(route('catalog.vehicule', ['marque' => 'dacia', 'vehicule' => 'duster']));

        $response->assertStatus(200);
        $response->assertSee('Dacia Duster');
        $response->assertSee('OPTIQUE DACIA DUSTER 18-');

        // Test filtering by family
        $optiqueFamilleId = \App\Models\Famille::where('nom', 'OPTIQUE')->value('id');
        $filterResponse = $this->get(route('catalog.vehicule', [
            'marque' => 'dacia',
            'vehicule' => 'duster',
            'famille_id' => $optiqueFamilleId,
        ]));

        $filterResponse->assertStatus(200);
        $filterResponse->assertSee('OPTIQUE DACIA DUSTER 18-');
    }

    public function test_piece_detail_page_loads_with_side_availability(): void
    {
        $piece = Piece::where('designation', 'OPTIQUE DACIA DUSTER 18-')->firstOrFail();

        $response = $this->get(route('catalog.piece', ['id' => $piece->id, 'slug' => $piece->slug]));

        $response->assertStatus(200);
        $response->assertSee($piece->designation);
        $response->assertSee('Côté Droit');
        $response->assertSee('Côté Gauche');
        $response->assertSee('795,00 DH');
    }

    public function test_add_piece_right_side_to_cart_and_view_cart(): void
    {
        $piece = Piece::where('designation', 'OPTIQUE DACIA DUSTER 18-')->firstOrFail();

        $postResponse = $this->post(route('cart.add'), [
            'piece_id' => $piece->id,
            'cote' => 'droite',
            'quantite' => 1,
        ]);

        $postResponse->assertRedirect(route('cart.index'));
        $postResponse->assertSessionHas('success');

        // Check cart page
        $cartResponse = $this->get(route('cart.index'));
        $cartResponse->assertStatus(200);
        $cartResponse->assertSee('OPTIQUE DACIA DUSTER 18-');
        $cartResponse->assertSee('Côté Droit');
        $cartResponse->assertSee('795,00 DH');
    }

    public function test_cannot_add_unavailable_side(): void
    {
        // Find piece where dispo_gauche is false
        $piece = Piece::where('position', 'RL')
            ->where('dispo_droite', true)
            ->where('dispo_gauche', false)
            ->firstOrFail();

        $postResponse = $this->from(route('catalog.piece', ['id' => $piece->id]))
            ->post(route('cart.add'), [
                'piece_id' => $piece->id,
                'cote' => 'gauche',
                'quantite' => 1,
            ]);

        $postResponse->assertRedirect(route('catalog.piece', ['id' => $piece->id]));
        $postResponse->assertSessionHas('error');
    }

    public function test_checkout_redirects_to_whatsapp_with_correct_formatted_message(): void
    {
        $piece = Piece::where('designation', 'OPTIQUE DACIA DUSTER 18-')->firstOrFail();

        // 1. Add to cart: right side
        $this->post(route('cart.add'), [
            'piece_id' => $piece->id,
            'cote' => 'droite',
            'quantite' => 1,
        ]);

        // 2. Submit checkout
        $response = $this->post(route('checkout.process'), [
            'nom' => 'Karim Idrissi',
            'telephone' => '0612345678',
            'adresse' => 'Casablanca, Maarif',
            'note' => 'Livraison urgente svp',
        ]);

        // 3. Verify redirect to wa.me URL
        $response->assertRedirect();
        $targetUrl = $response->headers->get('Location');

        $this->assertStringStartsWith('https://wa.me/212600000000?text=', $targetUrl);

        // Decode message query
        $parsedUrl = parse_url($targetUrl);
        parse_str($parsedUrl['query'], $queryParams);
        $message = $queryParams['text'];

        // Assert message contents as requested in prompt Section 5
        $this->assertStringContainsString('OPTIQUE DACIA DUSTER 18-', $message);
        $this->assertStringContainsString('Côté Droit', $message);
        $this->assertStringContainsString('x1', $message);
        $this->assertStringContainsString('795,00 DH', $message);
        $this->assertStringContainsString('Karim Idrissi', $message);
        $this->assertStringContainsString('0612345678', $message);
        $this->assertStringContainsString('Casablanca, Maarif', $message);
        $this->assertStringContainsString('Livraison urgente svp', $message);
    }
}
