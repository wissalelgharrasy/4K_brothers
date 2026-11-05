<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Piece;
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

        // Décrémentation automatique du stock pour chaque pièce commandée
        foreach ($items as $item) {
            if (! empty($item['piece_id'])) {
                $piece = Piece::find($item['piece_id']);
                if ($piece) {
                    $coeff = (int) ($item['coefficient_cote'] ?? 1);
                    $qtyToDeduct = (int) ($item['quantite'] ?? 1) * max(1, $coeff);
                    $piece->decrementStock($qtyToDeduct);
                }
            }
        }

        $orderData = $whatsAppService->generateOrderLink(
            $items,
            $cartService->total(),
            $validated
        );

        // Vider le panier après validation
        $cartService->clear();

        // Rediriger vers WhatsApp
        return redirect()->away($orderData['url']);
    }
}
