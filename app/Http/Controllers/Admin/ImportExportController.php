<?php

namespace App\Http\Controllers\Admin;

use App\Exports\CatalogExport;
use App\Exports\CatalogTemplateExport;
use App\Http\Controllers\Controller;
use App\Models\Famille;
use App\Models\ImportCatalogue;
use App\Models\Marque;
use App\Models\Piece;
use App\Models\Vehicule;
use App\Services\CatalogImportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ImportExportController extends Controller
{
    public function index(): View
    {
        $imports = ImportCatalogue::with('user')
            ->latest('date_import')
            ->paginate(15);

        $marques = Marque::orderBy('nom')->get();
        $familles = Famille::orderBy('nom')->get();

        $stats = [
            'total_pieces' => Piece::count(),
            'total_imports' => ImportCatalogue::count(),
            'total_crees' => ImportCatalogue::sum('nb_crees'),
            'total_mis_a_jour' => ImportCatalogue::sum('nb_mis_a_jour'),
            'total_erreurs' => ImportCatalogue::sum('nb_erreurs'),
        ];

        return view('admin.imports.index', compact('imports', 'marques', 'familles', 'stats'));
    }

    /**
     * Page principale d'export de catalogue (PDF et Excel).
     */
    public function exportsIndex(): View
    {
        $marques = Marque::orderBy('nom')->get();
        $familles = Famille::orderBy('nom')->get();

        return view('admin.exports.index', compact('marques', 'familles'));
    }

    public function create(): View
    {
        return view('admin.imports.create');
    }

    public function store(Request $request, CatalogImportService $importService): RedirectResponse
    {
        $request->validate([
            'fichier' => 'required|file|max:15360', // 15 MB
        ], [
            'fichier.required' => 'Veuillez sélectionner un fichier à importer.',
            'fichier.max' => 'Le fichier ne doit pas dépasser 15 Mo.',
        ]);

        $file = $request->file('fichier');
        $extension = strtolower($file->getClientOriginalExtension());

        if (! in_array($extension, ['xlsx', 'xls', 'csv', 'txt'])) {
            return back()->with('error', "Format de fichier non pris en charge (.'{$extension}'). Veuillez utiliser un fichier .xlsx, .xls ou .csv.");
        }

        try {
            $importLog = $importService->import($file, auth()->user(), $file->getClientOriginalName());

            $message = "Import terminé avec succès : {$importLog->nb_crees} pièce(s) créée(s), {$importLog->nb_mis_a_jour} mise(s) à jour.";
            if ($importLog->nb_erreurs > 0) {
                $message .= " Attention : {$importLog->nb_erreurs} ligne(s) ont été rejetées pour données invalides.";
            }

            return redirect()->route('admin.imports.show', $importLog)
                ->with('success', $message);

        } catch (\Throwable $e) {
            return back()->with('error', 'Une erreur est survenue lors de la lecture du fichier : '.$e->getMessage());
        }
    }

    public function show(ImportCatalogue $import): View
    {
        $import->load('user');

        return view('admin.imports.show', compact('import'));
    }

    /**
     * Télécharger le rapport d'erreurs d'un import au format CSV.
     */
    public function downloadErrors(ImportCatalogue $import): StreamedResponse|RedirectResponse
    {
        $errors = $import->rapport_erreurs_json ?? [];

        if (empty($errors)) {
            return back()->with('info', 'Cet import ne contient aucune erreur à télécharger.');
        }

        $filename = 'rapport_erreurs_import_'.$import->id.'_'.now()->format('Ymd_His').'.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($errors) {
            $output = fopen('php://output', 'w');

            // UTF-8 BOM pour compatibilité Excel
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

            // En-têtes CSV
            fputcsv($output, ['Numéro Ligne', 'Désignation', 'Motif du Rejet', 'Données Brutes de la Ligne'], ';');

            foreach ($errors as $err) {
                $donneesStr = is_array($err['donnees'] ?? null)
                    ? json_encode($err['donnees'], JSON_UNESCAPED_UNICODE)
                    : ($err['donnees'] ?? '');

                fputcsv($output, [
                    $err['ligne'] ?? 'N/A',
                    $err['designation'] ?? 'N/A',
                    $err['motif'] ?? 'Erreur inconnue',
                    $donneesStr,
                ], ';');
            }

            fclose($output);
        }, 200, $headers);
    }

    /**
     * Exporter le catalogue filtré vers un fichier Excel (.xlsx) ou vue PDF si format=pdf.
     */
    public function export(Request $request): mixed
    {
        if ($request->input('format') === 'pdf') {
            $request->merge(['download' => 1]);

            return $this->exportPdf($request);
        }

        $marqueId = $request->filled('marque_id') ? (int) $request->input('marque_id') : null;
        $familleId = $request->filled('famille_id') ? (int) $request->input('famille_id') : null;
        $onlyActive = $request->boolean('only_active', true);

        $dateStr = now()->format('Y-m-d_His');
        $filename = "catalogue_4k_brothers_{$dateStr}.xlsx";

        return Excel::download(new CatalogExport($marqueId, $familleId, $onlyActive), $filename);
    }

    /**
     * Vue imprimable/téléchargeable en PDF du catalogue selon le modèle exact demandé.
     */
    public function exportPdf(Request $request): View
    {
        $pieceIds = $request->input('piece_ids');
        if (is_string($pieceIds)) {
            $pieceIds = array_filter(explode(',', $pieceIds));
        }

        $customTitle = $request->input('custom_title');
        $customNote = $request->input('custom_note');

        $query = Piece::with(['famille', 'vehicule.marque']);

        if (! empty($pieceIds)) {
            // Manual specific selection of pieces
            $query->whereIn('id', (array) $pieceIds);
        } else {
            // Filter based selection
            $marqueIds = (array) $request->input('marque_ids', []);
            if ($request->filled('marque_id')) {
                $marqueIds[] = (int) $request->input('marque_id');
            }
            $marqueIds = array_filter(array_unique($marqueIds));

            $vehiculeId = $request->filled('vehicule_id') ? (int) $request->input('vehicule_id') : null;
            $familleId = $request->filled('famille_id') ? (int) $request->input('famille_id') : null;
            $search = $request->input('search');
            $onlyActive = $request->boolean('only_active', true);

            if ($onlyActive) {
                $query->where('actif', true);
            }

            if (! empty($marqueIds)) {
                $query->whereHas('vehicule', fn ($v) => $v->whereIn('marque_id', $marqueIds));
            }

            if ($vehiculeId) {
                $query->where('vehicule_id', $vehiculeId);
            }

            if ($familleId) {
                $query->where('famille_id', $familleId);
            }

            if (! empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('designation', 'like', "%{$search}%")
                        ->orWhere('variante', 'like', "%{$search}%");
                });
            }
        }

        $pieces = $query->get()->sortBy([
            fn ($a, $b) => strcmp($a->vehicule?->marque?->nom ?? 'ZZZ', $b->vehicule?->marque?->nom ?? 'ZZZ'),
            fn ($a, $b) => strcmp($a->vehicule?->nom ?? 'ZZZ', $b->vehicule?->nom ?? 'ZZZ'),
            fn ($a, $b) => strcmp($a->designation, $b->designation),
        ]);

        $selectedMarque = $request->filled('marque_id') ? Marque::find($request->input('marque_id')) : null;
        $selectedVehicule = $request->filled('vehicule_id') ? Vehicule::find($request->input('vehicule_id')) : null;
        $selectedFamille = $request->filled('famille_id') ? Famille::find($request->input('famille_id')) : null;

        return view('admin.exports.catalog-pdf', compact(
            'pieces',
            'selectedMarque',
            'selectedVehicule',
            'selectedFamille',
            'customTitle',
            'customNote'
        ));
    }

    /**
     * Formulaire pour composer un catalogue sur mesure (titre, sélection manuelle de pièces, filtres).
     */
    public function builder(Request $request): View
    {
        $marques = Marque::with('vehicules')->orderBy('nom')->get();
        $familles = Famille::orderBy('nom')->get();

        $query = Piece::with(['famille', 'vehicule.marque']);

        if ($request->filled('marque_id')) {
            $query->whereHas('vehicule', fn ($v) => $v->where('marque_id', $request->input('marque_id')));
        }
        if ($request->filled('famille_id')) {
            $query->where('famille_id', $request->input('famille_id'));
        }
        if ($request->filled('search')) {
            $s = $request->input('search');
            $query->where(function ($q) use ($s) {
                $q->where('designation', 'like', "%{$s}%")
                    ->orWhere('variante', 'like', "%{$s}%");
            });
        }

        $pieces = $query->orderBy('designation')->get();

        return view('admin.exports.builder', compact('marques', 'familles', 'pieces'));
    }

    /**
     * Télécharger le modèle type d'import (.xlsx).
     */
    public function downloadTemplate(): BinaryFileResponse
    {
        return Excel::download(new CatalogTemplateExport, 'modele_import_catalogue_4k_brothers.xlsx');
    }
}
