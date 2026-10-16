<?php

namespace App\Http\Controllers\Admin;

use App\Exports\CatalogExport;
use App\Exports\CatalogTemplateExport;
use App\Http\Controllers\Controller;
use App\Models\Famille;
use App\Models\ImportCatalogue;
use App\Models\Marque;
use App\Models\Piece;
use App\Services\CatalogImportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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

        if (!in_array($extension, ['xlsx', 'xls', 'csv', 'txt'])) {
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
            return back()->with('error', "Une erreur est survenue lors de la lecture du fichier : " . $e->getMessage());
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
            return back()->with('info', "Cet import ne contient aucune erreur à télécharger.");
        }

        $filename = 'rapport_erreurs_import_' . $import->id . '_' . now()->format('Ymd_His') . '.csv';

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
     * Exporter le catalogue filtré vers un fichier Excel (.xlsx).
     */
    public function export(Request $request): BinaryFileResponse
    {
        $marqueId = $request->filled('marque_id') ? (int)$request->input('marque_id') : null;
        $familleId = $request->filled('famille_id') ? (int)$request->input('famille_id') : null;
        $onlyActive = $request->boolean('only_active', true);

        $dateStr = now()->format('Y-m-d_His');
        $filename = "catalogue_4k_brothers_{$dateStr}.xlsx";

        return Excel::download(new CatalogExport($marqueId, $familleId, $onlyActive), $filename);
    }

    /**
     * Télécharger le modèle type d'import (.xlsx).
     */
    public function downloadTemplate(): BinaryFileResponse
    {
        return Excel::download(new CatalogTemplateExport(), 'modele_import_catalogue_4k_brothers.xlsx');
    }
}
