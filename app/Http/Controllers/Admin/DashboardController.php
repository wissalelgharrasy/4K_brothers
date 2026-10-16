<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
            'pieces_a_classer_count' => Piece::aClasser()->count(),
            'pieces_rl_count' => Piece::where('position', 'RL')->count(),
        ];

        $dernierImport = ImportCatalogue::with('user')->latest()->first();

        $recentPieces = Piece::with(['famille', 'vehicule.marque'])
            ->latest()
            ->take(8)
            ->get();

        $marquesBreakdown = Marque::withCount(['vehicules', 'pieces'])->get();

        return view('admin.dashboard', compact('stats', 'dernierImport', 'recentPieces', 'marquesBreakdown'));
    }
}
