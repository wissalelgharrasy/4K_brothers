<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Services\CartService;
use App\Services\WhatsAppOrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function index(CartService $cartService): View|RedirectResponse
    {
        $items = $cartService->getItems();

        if (empty($items)) {
            return redirect()->route('cart.index')->with('info', 'Votre panier est vide.');
        }

        $total = $cartService->total();
        $formattedTotal = $cartService->formattedTotal();

        return view('public.checkout', compact('items', 'total', 'formattedTotal'));
    }

    public function process(
        Request $request,
        CartService $cartService,
        WhatsAppOrderService $whatsAppService
    ): RedirectResponse|View {
        $items = $cartService->getItems();

        if (empty($items)) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:100',
            'telephone' => 'required|string|max:30',
            'adresse' => 'required|string|max:255',
            'note' => 'nullable|string|max:500',
        ]);

        $total = $cartService->total();

        // Sauvegarder la commande en base de données
        $commande = Commande::create([
            'reference' => Commande::generateReference(),
            'nom' => $validated['nom'],
            'telephone' => $validated['telephone'],
            'adresse' => $validated['adresse'],
            'note' => $validated['note'] ?? null,
            'items' => array_values($items),
            'total' => $total,
            'statut' => 'nouvelle',
        ]);

        // Décrémentation automatique du stock pour chaque pièce commandée
        $commande->deduireStock();

        $orderData = $whatsAppService->generateOrderLink(
            $items,
            $total,
            $validated
        );

        // Vider le panier après validation
        $cartService->clear();

        // Rediriger vers WhatsApp (le client envoie le message lui-même)
        return redirect()->away($orderData['url']);
    }
}
