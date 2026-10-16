<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Marque;
use App\Models\Vehicule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class VehiculeController extends Controller
{
    public function index(Request $request): View
    {
        $query = Vehicule::with('marque')->withCount('pieces');

        if ($marqueId = $request->input('marque_id')) {
            $query->where('marque_id', $marqueId);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhereHas('marque', fn ($m) => $m->where('nom', 'like', "%{$search}%"));
            });
        }

        $vehicules = $query->orderBy('nom')->paginate(15)->withQueryString();
        $marques = Marque::orderBy('nom')->get();

        return view('admin.vehicules.index', compact('vehicules', 'marques'));
    }

    public function create(): View
    {
        $marques = Marque::orderBy('nom')->get();
        return view('admin.vehicules.create', compact('marques'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'marque_id' => 'required|exists:marques,id',
            'nom' => [
                'required',
                'string',
                'max:100',
                Rule::unique('vehicules')->where(fn ($query) => $query->where('marque_id', $request->marque_id)),
            ],
        ], [
            'nom.unique' => 'Ce modèle existe déjà pour cette marque.',
        ]);

        Vehicule::create([
            'marque_id' => $validated['marque_id'],
            'nom' => $validated['nom'],
            'slug' => Str::slug($validated['nom']),
        ]);

        return redirect()->route('admin.vehicules.index')->with('success', 'Véhicule créé avec succès.');
    }

    public function edit(Vehicule $vehicule): View
    {
        $marques = Marque::orderBy('nom')->get();
        return view('admin.vehicules.edit', compact('vehicule', 'marques'));
    }

    public function update(Request $request, Vehicule $vehicule): RedirectResponse
    {
        $validated = $request->validate([
            'marque_id' => 'required|exists:marques,id',
            'nom' => [
                'required',
                'string',
                'max:100',
                Rule::unique('vehicules')->where(fn ($query) => $query->where('marque_id', $request->marque_id))->ignore($vehicule->id),
            ],
        ], [
            'nom.unique' => 'Ce modèle existe déjà pour cette marque.',
        ]);

        $vehicule->update([
            'marque_id' => $validated['marque_id'],
            'nom' => $validated['nom'],
            'slug' => Str::slug($validated['nom']),
        ]);

        return redirect()->route('admin.vehicules.index')->with('success', 'Véhicule mis à jour avec succès.');
    }

    public function destroy(Request $request, Vehicule $vehicule): RedirectResponse
    {
        $piecesCount = $vehicule->pieces()->count();

        if ($piecesCount > 0 && !$request->boolean('force')) {
            return back()->with('error', "Impossible de supprimer le modèle '{$vehicule->nom}' car il contient encore {$piecesCount} pièce(s) liée(s).");
        }

        $vehicule->delete();

        return redirect()->route('admin.vehicules.index')->with('success', "Le modèle '{$vehicule->nom}' a été supprimé.");
    }
}
