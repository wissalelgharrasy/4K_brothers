<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Famille;
use App\Models\Marque;
use App\Models\Piece;
use App\Models\Vehicule;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    /**
     * Page marque : liste des véhicules de la marque.
     */
    public function marque(string $slug): View
    {
        $marque = Marque::where('slug', $slug)->firstOrFail();

        $vehicules = $marque->vehicules()
            ->withCount(['pieces' => fn ($q) => $q->actif()])
            ->orderBy('nom')
            ->get();

        return view('public.marque', compact('marque', 'vehicules'));
    }

    /**
     * Page véhicule : liste des pièces avec filtres et tri.
     */
    public function vehicule(string $marqueSlug, string $vehiculeSlug, Request $request): View
    {
        $marque = Marque::where('slug', $marqueSlug)->firstOrFail();

        $vehicule = Vehicule::where('marque_id', $marque->id)
            ->where('slug', $vehiculeSlug)
            ->firstOrFail();

        $query = Piece::with(['famille', 'vehicule.marque'])
            ->where('vehicule_id', $vehicule->id)
            ->actif();

        // Filters
        $filtres = [
            'famille_id' => $request->input('famille_id'),
            'annee' => $request->input('annee'),
            'position' => $request->input('position'),
            'variante' => $request->input('variante'),
            'disponible' => $request->input('disponible'),
            'search' => $request->input('search'),
        ];

        $query->filtres($filtres);

        // Sorting
        $sort = $request->input('sort', 'default');
        match ($sort) {
            'prix_asc' => $query->orderBy('prix_ttc', 'asc'),
            'prix_desc' => $query->orderBy('prix_ttc', 'desc'),
            'nom_asc' => $query->orderBy('designation', 'asc'),
            'nom_desc' => $query->orderBy('designation', 'desc'),
            default => $query->orderBy('designation', 'asc'),
        };

        $pieces = $query->paginate(12)->withQueryString();

        // Available filter options for this vehicle
        $familles = Famille::whereHas('pieces', fn ($q) => $q->where('vehicule_id', $vehicule->id)->actif())
            ->orderBy('nom')
            ->get();

        $annees = Piece::where('vehicule_id', $vehicule->id)
            ->actif()
            ->select('annee_debut')
            ->distinct()
            ->orderByDesc('annee_debut')
            ->pluck('annee_debut');

        $positions = Piece::where('vehicule_id', $vehicule->id)
            ->actif()
            ->select('position')
            ->distinct()
            ->pluck('position');

        return view('public.vehicule', compact(
            'marque',
            'vehicule',
            'pieces',
            'familles',
            'annees',
            'positions',
            'filtres',
            'sort'
        ));
    }

    /**
     * Fiche pièce détaillée.
     */
    public function piece(int $id, ?string $slug = null): View
    {
        $piece = Piece::with(['famille', 'vehicule.marque'])->findOrFail($id);

        if (!$piece->actif && !auth()->check()) {
            abort(404, 'Pièce non trouvée ou inactive');
        }

        // Related pieces
        $relatedPieces = Piece::with(['famille', 'vehicule.marque'])
            ->actif()
            ->where('id', '!=', $piece->id)
            ->where(function ($q) use ($piece) {
                if ($piece->vehicule_id) {
                    $q->where('vehicule_id', $piece->vehicule_id);
                } else {
                    $q->where('famille_id', $piece->famille_id);
                }
            })
            ->take(4)
            ->get();

        return view('public.piece', compact('piece', 'relatedPieces'));
    }

    /**
     * Recherche globale par désignation, groupée par véhicule.
     */
    public function search(Request $request): View
    {
        $q = trim((string) $request->input('q', ''));

        if (empty($q)) {
            return view('public.search', [
                'query' => '',
                'results' => collect(),
                'totalCount' => 0,
            ]);
        }

        $pieces = Piece::with(['famille', 'vehicule.marque'])
            ->actif()
            ->where(function ($query) use ($q) {
                $query->where('designation', 'like', "%{$q}%")
                      ->orWhere('variante', 'like', "%{$q}%")
                      ->orWhereHas('famille', fn ($f) => $f->where('nom', 'like', "%{$q}%"))
                      ->orWhereHas('vehicule', fn ($v) => $v->where('nom', 'like', "%{$q}%"));
            })
            ->orderBy('designation')
            ->get();

        // Group results by vehicle
        $grouped = $pieces->groupBy(function ($piece) {
            if ($piece->vehicule) {
                return $piece->vehicule->marque->nom . ' ' . $piece->vehicule->nom;
            }
            return 'Pièces universelles / À classer';
        });

        return view('public.search', [
            'query' => $q,
            'groupedResults' => $grouped,
            'totalCount' => $pieces->count(),
        ]);
    }
}
