<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Famille;
use App\Models\Marque;
use App\Models\Piece;
use App\Models\Vehicule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PieceController extends Controller
{
    public function index(Request $request): View
    {
        $query = Piece::with(['famille', 'vehicule.marque']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('designation', 'like', "%{$search}%")
                  ->orWhere('variante', 'like', "%{$search}%");
            });
        }

        if ($request->filled('a_classer')) {
            if ($request->input('a_classer') === '1') {
                $query->whereNull('vehicule_id');
            } elseif ($request->input('a_classer') === '0') {
                $query->whereNotNull('vehicule_id');
            }
        }

        if ($familleId = $request->input('famille_id')) {
            $query->where('famille_id', $familleId);
        }

        if ($marqueId = $request->input('marque_id')) {
            $query->whereHas('vehicule', fn ($v) => $v->where('marque_id', $marqueId));
        }

        if ($vehiculeId = $request->input('vehicule_id')) {
            $query->where('vehicule_id', $vehiculeId);
        }

        if ($position = $request->input('position')) {
            $query->where('position', $position);
        }

        if ($request->filled('actif')) {
            $query->where('actif', $request->boolean('actif'));
        }

        $pieces = $query->latest()->paginate(15)->withQueryString();

        $marques = Marque::orderBy('nom')->get();
        $familles = Famille::orderBy('nom')->get();
        $vehicules = Vehicule::orderBy('nom')->get();

        $aClasserCount = Piece::aClasser()->count();

        return view('admin.pieces.index', compact('pieces', 'marques', 'familles', 'vehicules', 'aClasserCount'));
    }

    public function create(): View
    {
        $marques = Marque::with('vehicules')->orderBy('nom')->get();
        $familles = Famille::orderBy('nom')->get();

        return view('admin.pieces.create', compact('marques', 'familles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $maxYear = date('Y') + 1;

        $validated = $request->validate([
            'designation' => 'required|string|max:255',
            'famille_id' => 'required|exists:familles,id',
            'vehicule_id' => 'nullable|exists:vehicules,id',
            'annee_debut' => "required|integer|between:1990,{$maxYear}",
            'position' => 'required|in:AV,AR,CTRL,RL',
            'variante' => 'nullable|string|max:150',
            'prix_ttc' => 'required|numeric|min:0.01',
            'dispo_droite' => 'nullable|boolean',
            'dispo_gauche' => 'nullable|boolean',
            'en_stock' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'actif' => 'nullable|boolean',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('pieces', 'public');
        }

        $isRL = $validated['position'] === 'RL';

        Piece::create([
            'designation' => $validated['designation'],
            'famille_id' => $validated['famille_id'],
            'vehicule_id' => $validated['vehicule_id'] ?? null,
            'annee_debut' => $validated['annee_debut'],
            'position' => $validated['position'],
            'variante' => $validated['variante'] ?? null,
            'prix_ttc' => $validated['prix_ttc'],
            'dispo_droite' => $isRL ? $request->boolean('dispo_droite') : null,
            'dispo_gauche' => $isRL ? $request->boolean('dispo_gauche') : null,
            'en_stock' => !$isRL ? $request->boolean('en_stock', true) : null,
            'image' => $imagePath,
            'actif' => $request->boolean('actif', true),
        ]);

        return redirect()->route('admin.pieces.index')->with('success', 'Pièce ajoutée au catalogue avec succès.');
    }

    public function edit(Piece $piece): View
    {
        $marques = Marque::with('vehicules')->orderBy('nom')->get();
        $familles = Famille::orderBy('nom')->get();

        return view('admin.pieces.edit', compact('piece', 'marques', 'familles'));
    }

    public function update(Request $request, Piece $piece): RedirectResponse
    {
        $maxYear = date('Y') + 1;

        $validated = $request->validate([
            'designation' => 'required|string|max:255',
            'famille_id' => 'required|exists:familles,id',
            'vehicule_id' => 'nullable|exists:vehicules,id',
            'annee_debut' => "required|integer|between:1990,{$maxYear}",
            'position' => 'required|in:AV,AR,CTRL,RL',
            'variante' => 'nullable|string|max:150',
            'prix_ttc' => 'required|numeric|min:0.01',
            'dispo_droite' => 'nullable|boolean',
            'dispo_gauche' => 'nullable|boolean',
            'en_stock' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'actif' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($piece->image && Storage::disk('public')->exists($piece->image)) {
                Storage::disk('public')->delete($piece->image);
            }
            $piece->image = $request->file('image')->store('pieces', 'public');
        }

        $isRL = $validated['position'] === 'RL';

        $piece->designation = $validated['designation'];
        $piece->famille_id = $validated['famille_id'];
        $piece->vehicule_id = $validated['vehicule_id'] ?? null;
        $piece->annee_debut = $validated['annee_debut'];
        $piece->position = $validated['position'];
        $piece->variante = $validated['variante'] ?? null;
        $piece->prix_ttc = $validated['prix_ttc'];
        $piece->dispo_droite = $isRL ? $request->boolean('dispo_droite') : null;
        $piece->dispo_gauche = $isRL ? $request->boolean('dispo_gauche') : null;
        $piece->en_stock = !$isRL ? $request->boolean('en_stock', true) : null;
        $piece->actif = $request->boolean('actif', true);
        $piece->save();

        return redirect()->route('admin.pieces.index')->with('success', 'Pièce mise à jour avec succès.');
    }

    public function destroy(Piece $piece): RedirectResponse
    {
        if ($piece->image && Storage::disk('public')->exists($piece->image)) {
            Storage::disk('public')->delete($piece->image);
        }

        $piece->delete();

        return redirect()->route('admin.pieces.index')->with('success', 'La pièce a été retirée du catalogue.');
    }

    /**
     * Activer / désactiver une pièce sans suppression.
     */
    public function toggleActive(Piece $piece): RedirectResponse|JsonResponse
    {
        $piece->actif = !$piece->actif;
        $piece->save();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'actif' => $piece->actif,
                'message' => $piece->actif ? 'Pièce activée.' : 'Pièce désactivée.',
            ]);
        }

        return back()->with('success', $piece->actif ? "La pièce '{$piece->designation}' est maintenant active." : "La pièce '{$piece->designation}' a été désactivée.");
    }
}
