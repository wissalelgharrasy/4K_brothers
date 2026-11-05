<?php

namespace Tests\Feature;

use App\Models\Famille;
use App\Models\ImportCatalogue;
use App\Models\Marque;
use App\Models\Piece;
use App\Models\User;
use App\Models\Vehicule;
use App\Services\CatalogImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CatalogImportExportTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private Marque $dacia;

    private Marque $renault;

    private Vehicule $duster;

    private Vehicule $clio4;

    private Famille $optiques;

    private Famille $pareChocs;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'email' => 'admin@4kbrothers.ma',
        ]);

        $this->dacia = Marque::create(['nom' => 'Dacia', 'slug' => 'dacia']);
        $this->renault = Marque::create(['nom' => 'Renault', 'slug' => 'renault']);

        $this->duster = Vehicule::create(['marque_id' => $this->dacia->id, 'nom' => 'Duster', 'slug' => 'duster']);
        $this->clio4 = Vehicule::create(['marque_id' => $this->renault->id, 'nom' => 'Clio 4', 'slug' => 'clio-4']);

        $this->optiques = Famille::create(['nom' => 'OPTIQUES', 'slug' => 'optiques']);
        $this->pareChocs = Famille::create(['nom' => 'PARE-CHOCS', 'slug' => 'pare-chocs']);
    }

    public function test_guest_cannot_access_export_routes(): void
    {
        $this->get('/admin/export')->assertRedirect('/login');
        $this->get('/admin/export/pdf')->assertRedirect('/login');
        $this->get('/admin/catalogue/builder')->assertRedirect('/login');
    }

    public function test_admin_import_routes_redirect_to_dashboard(): void
    {
        $this->actingAs($this->admin)->get('/admin/imports')->assertRedirect('/admin');
        $this->actingAs($this->admin)->get('/admin/imports/create')->assertRedirect(route('admin.dashboard'));
        $this->actingAs($this->admin)->get('/admin/exports')->assertRedirect('/admin');
    }

    public function test_admin_can_import_valid_csv_via_service(): void
    {
        $csvContent = implode("\n", [
            'Marque;Modèle;Famille;Désignation;Position;Année;Prix TTC;Dispo Droite;Dispo Gauche;Variante',
            'DACIA;DUSTER;OPTIQUES;OPTIQUE AV CHROME DUSTER;RL;2018;650,00;Oui;Oui;CHROME',
            'RENAULT;CLIO 4;PARE-CHOCS;PARE CHOC AVANT CLIO 4;AV;2016;750.50;;;NOIR',
        ]);

        $file = UploadedFile::fake()->createWithContent('catalogue_test.csv', $csvContent);

        app(CatalogImportService::class)->import($file, $this->admin, 'catalogue_test.csv');

        $this->assertDatabaseHas('pieces', [
            'designation' => 'OPTIQUE AV CHROME DUSTER',
            'position' => 'RL',
            'prix_ttc' => 650.00,
            'dispo_droite' => 1,
            'dispo_gauche' => 1,
            'annee_debut' => 2018,
            'vehicule_id' => $this->duster->id,
        ]);

        $this->assertDatabaseHas('pieces', [
            'designation' => 'PARE CHOC AVANT CLIO 4',
            'position' => 'AV',
            'prix_ttc' => 750.50,
            'en_stock' => 1,
            'annee_debut' => 2016,
            'vehicule_id' => $this->clio4->id,
        ]);

        $this->assertDatabaseHas('import_catalogues', [
            'nb_crees' => 2,
            'nb_mis_a_jour' => 0,
            'nb_erreurs' => 0,
            'statut' => 'termine',
        ]);
    }

    public function test_duplicate_row_updates_price_without_creating_new_record(): void
    {
        // Create initial piece
        Piece::create([
            'designation' => 'OPTIQUE AV CHROME DUSTER',
            'famille_id' => $this->optiques->id,
            'vehicule_id' => $this->duster->id,
            'position' => 'RL',
            'annee_debut' => 2018,
            'prix_ttc' => 600.00,
            'dispo_droite' => true,
            'dispo_gauche' => false,
            'actif' => true,
        ]);

        $this->assertEquals(1, Piece::where('designation', 'OPTIQUE AV CHROME DUSTER')->count());

        // Import CSV with updated price and sides
        $csvContent = implode("\n", [
            'Marque;Modèle;Famille;Désignation;Position;Année;Prix TTC;Dispo Droite;Dispo Gauche',
            'DACIA;DUSTER;OPTIQUES;OPTIQUE AV CHROME DUSTER;RL;2018;680.00;Oui;Oui',
        ]);

        $file = UploadedFile::fake()->createWithContent('update_test.csv', $csvContent);

        app(CatalogImportService::class)->import($file, $this->admin, 'update_test.csv');

        // Still only 1 record, but updated price and availability
        $this->assertEquals(1, Piece::where('designation', 'OPTIQUE AV CHROME DUSTER')->count());
        $piece = Piece::where('designation', 'OPTIQUE AV CHROME DUSTER')->first();
        $this->assertEquals(680.00, (float) $piece->prix_ttc);
        $this->assertTrue((bool) $piece->dispo_gauche);

        $this->assertDatabaseHas('import_catalogues', [
            'nb_crees' => 0,
            'nb_mis_a_jour' => 1,
            'nb_erreurs' => 0,
        ]);
    }

    public function test_invalid_rows_are_rejected_and_logged_without_failing_valid_rows(): void
    {
        $csvContent = implode("\n", [
            'Marque;Modèle;Famille;Désignation;Position;Année;Prix TTC',
            'DACIA;DUSTER;OPTIQUES;PIECE VALIDE 1;AV;2020;400.00',
            'DACIA;DUSTER;OPTIQUES;;AV;2020;500.00', // Empty designation
            'RENAULT;CLIO 4;OPTIQUES;PIECE PRIX NEGATIF;AV;2016;-25.00', // Negative price
            'RENAULT;CLIO 4;OPTIQUES;PIECE SANS PRIX;AV;2016;', // Missing price
            'RENAULT;CLIO 4;PARE-CHOCS;PIECE VALIDE 2;AV;2016;600.00',
        ]);

        $file = UploadedFile::fake()->createWithContent('invalid_rows_test.csv', $csvContent);

        app(CatalogImportService::class)->import($file, $this->admin, 'invalid_rows_test.csv');

        $this->assertDatabaseHas('pieces', ['designation' => 'PIECE VALIDE 1']);
        $this->assertDatabaseHas('pieces', ['designation' => 'PIECE VALIDE 2']);

        $import = ImportCatalogue::latest()->first();
        $this->assertEquals(2, $import->nb_crees);
        $this->assertEquals(3, $import->nb_erreurs);
        $this->assertCount(3, $import->rapport_erreurs_json);

        // Check error details
        $this->assertEquals('Désignation de la pièce manquante ou vide.', $import->rapport_erreurs_json[0]['motif']);
        $this->assertStringContainsString('Prix TTC invalide', $import->rapport_erreurs_json[1]['motif']);
    }

    public function test_piece_without_recognized_vehicle_is_rejected_with_error(): void
    {
        $csvContent = implode("\n", [
            'Marque;Modèle;Famille;Désignation;Position;Année;Prix TTC',
            ';;OPTIQUES;OPTIQUE UNIVERSELLE SANS MODELE;AV;2020;350.00',
        ]);

        $file = UploadedFile::fake()->createWithContent('sans_vehicule.csv', $csvContent);

        app(CatalogImportService::class)->import($file, $this->admin, 'sans_vehicule.csv');

        $piece = Piece::where('designation', 'OPTIQUE UNIVERSELLE SANS MODELE')->first();
        $this->assertNull($piece);

        $import = ImportCatalogue::latest('id')->first();
        $this->assertEquals(1, $import->nb_erreurs);
        $this->assertStringContainsString('Véhicule non reconnu', $import->rapport_erreurs_json[0]['motif']);
    }

    public function test_admin_can_export_catalogue_with_filters(): void
    {
        // Create 1 active piece and 1 inactive piece
        Piece::create([
            'designation' => 'Feu Arrière Clio Actif',
            'famille_id' => $this->optiques->id,
            'vehicule_id' => $this->clio4->id,
            'position' => 'AR',
            'annee_debut' => 2016,
            'prix_ttc' => 500.00,
            'actif' => true,
        ]);

        Piece::create([
            'designation' => 'Feu Arrière Duster Inactif',
            'famille_id' => $this->optiques->id,
            'vehicule_id' => $this->duster->id,
            'position' => 'AR',
            'annee_debut' => 2018,
            'prix_ttc' => 600.00,
            'actif' => false,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.export', [
            'marque_id' => $this->renault->id,
            'only_active' => 1,
        ]));

        $response->assertStatus(200);
        $this->assertStringContainsString('catalogue_4k_brothers', $response->headers->get('content-disposition'));
    }

    public function test_admin_can_export_catalogue_as_pdf(): void
    {
        Piece::create([
            'designation' => 'OPTIQUE CITROEN BERLINGO 08-',
            'famille_id' => $this->optiques->id,
            'vehicule_id' => $this->clio4->id,
            'position' => 'RL',
            'annee_debut' => 2008,
            'prix_ttc' => 753.00,
            'dispo_droite' => true,
            'dispo_gauche' => true,
            'actif' => true,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.export.pdf'));

        $response->assertStatus(200);
        $response->assertSee('IMAGES');
        $response->assertSee('DESIGNATION');
        $response->assertSee('DISP');
        $response->assertSee('PRIX');
        $response->assertSee('OPTIQUE CITROEN BERLINGO 08-');
        $response->assertSee('753,00');
    }

    public function test_admin_can_access_catalogue_builder(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.catalogue.builder'));

        $response->assertStatus(200);
        $response->assertSee('Composer un Catalogue PDF Personnalisé');
    }
}
