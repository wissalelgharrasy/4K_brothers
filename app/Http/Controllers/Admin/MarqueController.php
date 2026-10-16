<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Marque;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MarqueController extends Controller
{
    public function index(Request $request): View
    {
        $query = Marque::withCount(['vehicules', 'pieces']);

        if ($search = $request->input('search')) {
            $query->where('nom', 'like', "%{$search}%");
        }

        $marques = $query->orderBy('nom')->paginate(15)->withQueryString();

        return view('admin.marques.index', compact('marques'));
    }

    public function create(): View
    {
        return view('admin.marques.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:100|unique:marques,nom',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('marques', 'public');
        }

        Marque::create([
            'nom' => $validated['nom'],
            'slug' => Str::slug($validated['nom']),
            'logo' => $logoPath,
        ]);

        return redirect()->route('admin.marques.index')->with('success', 'Marque créée avec succès.');
    }

    public function edit(Marque $marque): View
    {
        return view('admin.marques.edit', compact('marque'));
    }

    public function update(Request $request, Marque $marque): RedirectResponse
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:100', Rule::unique('marques', 'nom')->ignore($marque->id)],
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            if ($marque->logo && Storage::disk('public')->exists($marque->logo)) {
                Storage::disk('public')->delete($marque->logo);
            }
            $marque->logo = $request->file('logo')->store('marques', 'public');
        }

        $marque->nom = $validated['nom'];
        $marque->slug = Str::slug($validated['nom']);
        $marque->save();

        return redirect()->route('admin.marques.index')->with('success', 'Marque mise à jour avec succès.');
    }

    public function destroy(Request $request, Marque $marque): RedirectResponse
    {
        $piecesCount = $marque->pieces()->count();

        if ($piecesCount > 0 && !$request->boolean('force')) {
            return back()->with('error', "Impossible de supprimer la marque '{$marque->nom}' car elle contient encore {$piecesCount} pièce(s) liée(s). Veuillez d'abord supprimer ou reclasser ces pièces.");
        }

        if ($marque->logo && Storage::disk('public')->exists($marque->logo)) {
            Storage::disk('public')->delete($marque->logo);
        }

        $marque->delete();

        return redirect()->route('admin.marques.index')->with('success', "La marque '{$marque->nom}' a été supprimée.");
    }
}
