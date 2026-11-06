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

        $ancienStatut = $commande->statut;
        $nouveauStatut = $request->statut;

        if ($ancienStatut !== $nouveauStatut) {
            // Si la commande est annulée, on restitue (remet) les quantités au stock
            if ($nouveauStatut === 'annulée' && $ancienStatut !== 'annulée') {
                $commande->restaurerStock();
                $message = 'Commande annulée : quantité restituée au stock avec succès.';
            }
            // Si au contraire une commande annulée redevient active, on retire la quantité du stock
            elseif ($ancienStatut === 'annulée' && $nouveauStatut !== 'annulée') {
                $commande->deduireStock();
                $message = 'Commande réactivée : quantité retirée du stock.';
            } else {
                $message = 'Statut mis à jour avec succès.';
            }

            $commande->update(['statut' => $nouveauStatut]);
        } else {
            $message = 'Statut inchangé.';
        }

        return back()->with('success', $message);
    }

    public function destroy(Commande $commande): RedirectResponse
    {
        // Si la commande n'était pas annulée, on restitue le stock avant suppression
        if ($commande->statut !== 'annulée') {
            $commande->restaurerStock();
        }

        $commande->delete();

        return redirect()->route('admin.commandes.index')->with('success', 'Commande supprimée et stock restitué.');
    }
}
