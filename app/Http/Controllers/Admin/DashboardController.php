<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\Famille;
use App\Models\ImportCatalogue;
use App\Models\Marque;
use App\Models\Piece;
use App\Models\Vehicule;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'marques_count' => Marque::count(),
            'vehicules_count' => Vehicule::count(),
            'familles_count' => Famille::count(),
            'pieces_count' => Piece::count(),
            'pieces_actives_count' => Piece::actif()->count(),
            'pieces_rl_count' => Piece::where('position', 'RL')->count(),
            'commandes_count' => Commande::count(),
        ];

        $dernierImport = ImportCatalogue::with('user')->latest()->first();

        $recentPieces = Piece::with(['famille', 'vehicule.marque'])
            ->latest()
            ->take(6)
            ->get();

        $marquesBreakdown = Marque::withCount(['vehicules', 'pieces'])
            ->orderByDesc('pieces_count')
            ->get();

        // Données pour les graphiques statistiques
        $chartMarques = [
            'labels' => $marquesBreakdown->pluck('nom')->all(),
            'data' => $marquesBreakdown->pluck('pieces_count')->all(),
        ];

        $famillesTop = Famille::withCount('pieces')
            ->orderByDesc('pieces_count')
            ->take(6)
            ->get();

        $chartFamilles = [
            'labels' => $famillesTop->pluck('nom')->all(),
            'data' => $famillesTop->pluck('pieces_count')->all(),
        ];

        $inactivesCount = max(0, $stats['pieces_count'] - $stats['pieces_actives_count']);
        $chartStatut = [
            'labels' => ['Actives', 'Inactives', 'Bilatérales (RL)'],
            'data' => [
                $stats['pieces_actives_count'],
                $inactivesCount,
                $stats['pieces_rl_count'],
            ],
        ];

        return view('admin.dashboard', compact(
            'stats',
            'dernierImport',
            'recentPieces',
            'marquesBreakdown',
            'chartMarques',
            'chartFamilles',
            'chartStatut'
        ));
    }
}
