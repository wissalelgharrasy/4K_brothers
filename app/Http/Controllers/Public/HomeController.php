<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Famille;
use App\Models\Marque;
use App\Models\Piece;
use App\Models\Vehicule;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $marques = Marque::withCount(['vehicules', 'pieces'])->get();

        $familles = Famille::has('pieces')
            ->withCount('pieces')
            ->orderByDesc('pieces_count')
            ->take(8)
            ->get();

        $featuredPieces = Piece::with(['vehicule.marque', 'famille'])
            ->actif()
            ->latest()
            ->take(8)
            ->get();

        $stats = [
            'total_pieces' => Piece::actif()->count(),
            'total_marques' => $marques->count(),
            'total_vehicules' => Vehicule::count(),
        ];

        return view('public.home', compact('marques', 'familles', 'featuredPieces', 'stats'));
    }
}
