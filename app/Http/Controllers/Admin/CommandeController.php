<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommandeController extends Controller
{
    public function index(): View
    {
        $commandes = Commande::latest()->paginate(20);

        return view('admin.commandes.index', compact('commandes'));
    }

    public function show(Commande $commande): View
    {
        return view('admin.commandes.show', compact('commande'));
    }

    public function updateStatut(Request $request, Commande $commande): RedirectResponse
    {
        $request->validate([
            'statut' => 'required|in:nouvelle,contacté,confirmée,annulée',
        ]);

        $commande->update(['statut' => $request->statut]);

        return back()->with('success', 'Statut mis à jour.');
    }

    public function destroy(Commande $commande): RedirectResponse
    {
        $commande->delete();

        return redirect()->route('admin.commandes.index')->with('success', 'Commande supprimée.');
    }
}
