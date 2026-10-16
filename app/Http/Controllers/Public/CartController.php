<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Piece;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(CartService $cartService): View
    {
        $items = $cartService->getItems();
        $total = $cartService->total();
        $formattedTotal = $cartService->formattedTotal();
        $count = $cartService->count();

        return view('public.cart', compact('items', 'total', 'formattedTotal', 'count'));
    }

    public function add(Request $request, CartService $cartService): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'piece_id' => 'required|exists:pieces,id',
            'cote' => 'nullable|string|in:droite,gauche,les_deux',
            'quantite' => 'nullable|integer|min:1|max:100',
        ]);

        $piece = Piece::with('vehicule.marque')->findOrFail($validated['piece_id']);
        $quantity = (int) ($validated['quantite'] ?? 1);
        $cote = $validated['cote'] ?? null;

        $result = $cartService->add($piece, $cote, $quantity);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($result, $result['success'] ? 200 : 422);
        }

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        return redirect()->route('cart.index')->with('success', $result['message']);
    }

    public function update(Request $request, string $itemKey, CartService $cartService): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'quantite' => 'required|integer|min:0|max:100',
        ]);

        $cartService->updateQuantity($itemKey, (int) $validated['quantite']);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'cart_count' => $cartService->count(),
                'cart_total' => $cartService->formattedTotal(),
            ]);
        }

        return back()->with('success', 'Panier mis à jour.');
    }

    public function remove(string $itemKey, CartService $cartService): RedirectResponse|JsonResponse
    {
        $cartService->remove($itemKey);

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'cart_count' => $cartService->count(),
                'cart_total' => $cartService->formattedTotal(),
            ]);
        }

        return back()->with('success', 'Article retiré du panier.');
    }

    public function clear(CartService $cartService): RedirectResponse
    {
        $cartService->clear();
        return redirect()->route('cart.index')->with('success', 'Votre panier a été vidé.');
    }
}
