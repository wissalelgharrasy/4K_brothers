<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Famille;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FamilleController extends Controller
{
    public function index(Request $request): View
    {
        $query = Famille::withCount('pieces');

        if ($search = $request->input('search')) {
            $query->where('nom', 'like', "%{$search}%");
        }

        $familles = $query->orderBy('nom')->paginate(20)->withQueryString();

        return view('admin.familles.index', compact('familles'));
    }

    public function create(): View
    {
        return view('admin.familles.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:100|unique:familles,nom',
        ]);

        $nom = mb_strtoupper(trim($validated['nom']), 'UTF-8');

        Famille::create([
            'nom' => $nom,
            'slug' => Str::slug($nom),
        ]);

        return redirect()->route('admin.familles.index')->with('success', 'Famille de pièces créée avec succès.');
    }

    public function edit(Famille $famille): View
    {
        return view('admin.familles.edit', compact('famille'));
    }

    public function update(Request $request, Famille $famille): RedirectResponse
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:100', Rule::unique('familles', 'nom')->ignore($famille->id)],
        ]);

        $nom = mb_strtoupper(trim($validated['nom']), 'UTF-8');

        $famille->update([
            'nom' => $nom,
            'slug' => Str::slug($nom),
        ]);

        return redirect()->route('admin.familles.index')->with('success', 'Famille de pièces mise à jour avec succès.');
    }

    public function destroy(Request $request, Famille $famille): RedirectResponse
    {
        $piecesCount = $famille->pieces()->count();

        if ($piecesCount > 0 && !$request->boolean('force')) {
            return back()->with('error', "Impossible de supprimer la famille '{$famille->nom}' car elle contient encore {$piecesCount} pièce(s) associée(s).");
        }

        $famille->delete();

        return redirect()->route('admin.familles.index')->with('success', "La famille '{$famille->nom}' a été supprimée.");
    }
}
