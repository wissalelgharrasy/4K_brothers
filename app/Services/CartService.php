<?php

namespace App\Services;

use App\Models\Piece;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected const SESSION_KEY = 'cart_items';

    /**
     * Get all cart items.
     *
     * @return array<string, array>
     */
    public function getItems(): array
    {
        return Session::get(self::SESSION_KEY, []);
    }

    /**
     * Add an item to the cart.
     *
     * @param Piece $piece
     * @param string|null $cote ('droite', 'gauche', 'les_deux', or null)
     * @param int $quantity
     * @return array Result with status and message
     */
    public function add(Piece $piece, ?string $cote = null, int $quantity = 1): array
    {
        if ($quantity < 1) {
            $quantity = 1;
        }

        // Validate availability
        if (!$piece->actif) {
            return ['success' => false, 'message' => 'Cette pièce n\'est plus disponible.'];
        }

        $coteNormalized = null;
        $coteLabel = 'Standard';
        $coefficientCote = 1;

        if ($piece->isDoubleCote()) {
            $cote = strtolower((string) $cote);
            if (!in_array($cote, ['droite', 'gauche', 'les_deux'], true)) {
                return ['success' => false, 'message' => 'Veuillez sélectionner un côté (Droit, Gauche ou Les deux).'];
            }

            if ($cote === 'droite') {
                if (!$piece->dispo_droite) {
                    return ['success' => false, 'message' => 'Le côté droit est actuellement indisponible.'];
                }
                $coteNormalized = 'droite';
                $coteLabel = 'Côté Droit';
            } elseif ($cote === 'gauche') {
                if (!$piece->dispo_gauche) {
                    return ['success' => false, 'message' => 'Le côté gauche est actuellement indisponible.'];
                }
                $coteNormalized = 'gauche';
                $coteLabel = 'Côté Gauche';
            } elseif ($cote === 'les_deux') {
                if (!$piece->dispo_droite || !$piece->dispo_gauche) {
                    return ['success' => false, 'message' => 'L\'un des côtés est indisponible, impossible de commander la paire complète.'];
                }
                $coteNormalized = 'les_deux';
                $coteLabel = 'Les deux côtés (Droit + Gauche)';
                $coefficientCote = 2; // Price is per side, pair is 2 sides
            }
        } else {
            if (!$piece->en_stock) {
                return ['success' => false, 'message' => 'Cette pièce est actuellement en rupture de stock.'];
            }
        }

        $itemKey = $piece->id . '_' . ($coteNormalized ?? 'std');
        $items = $this->getItems();

        $prixUnitaire = (float) $piece->prix_ttc;
        $prixLigneUnitaire = $prixUnitaire * $coefficientCote;

        if (isset($items[$itemKey])) {
            $items[$itemKey]['quantite'] += $quantity;
            $items[$itemKey]['total_ligne'] = $items[$itemKey]['quantite'] * $prixLigneUnitaire;
        } else {
            $items[$itemKey] = [
                'item_key' => $itemKey,
                'piece_id' => $piece->id,
                'designation' => $piece->designation,
                'slug' => $piece->slug,
                'marque_nom' => $piece->vehicule?->marque?->nom ?? 'Toutes marques',
                'vehicule_nom' => $piece->vehicule?->nom ?? 'Universel / À classer',
                'position' => $piece->position,
                'is_double_cote' => $piece->isDoubleCote(),
                'cote' => $coteNormalized,
                'cote_label' => $coteLabel,
                'coefficient_cote' => $coefficientCote,
                'prix_unitaire' => $prixUnitaire,
                'prix_par_unite_vendue' => $prixLigneUnitaire,
                'quantite' => $quantity,
                'total_ligne' => $quantity * $prixLigneUnitaire,
                'image' => $piece->image_url,
            ];
        }

        Session::put(self::SESSION_KEY, $items);

        return [
            'success' => true,
            'message' => 'Pièce ajoutée au panier avec succès !',
            'cart_count' => $this->count(),
            'cart_total' => $this->total(),
        ];
    }

    /**
     * Update quantity of a line item.
     */
    public function updateQuantity(string $itemKey, int $quantity): bool
    {
        $items = $this->getItems();

        if (!isset($items[$itemKey])) {
            return false;
        }

        if ($quantity <= 0) {
            unset($items[$itemKey]);
        } else {
            $items[$itemKey]['quantite'] = $quantity;
            $items[$itemKey]['total_ligne'] = $quantity * $items[$itemKey]['prix_par_unite_vendue'];
        }

        Session::put(self::SESSION_KEY, $items);
        return true;
    }

    /**
     * Remove an item from the cart.
     */
    public function remove(string $itemKey): bool
    {
        $items = $this->getItems();

        if (isset($items[$itemKey])) {
            unset($items[$itemKey]);
            Session::put(self::SESSION_KEY, $items);
            return true;
        }

        return false;
    }

    /**
     * Clear all cart items.
     */
    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }

    /**
     * Get total quantity of items.
     */
    public function count(): int
    {
        $count = 0;
        foreach ($this->getItems() as $item) {
            $count += (int) $item['quantite'];
        }
        return $count;
    }

    /**
     * Get total price of cart.
     */
    public function total(): float
    {
        $total = 0.0;
        foreach ($this->getItems() as $item) {
            $total += (float) $item['total_ligne'];
        }
        return $total;
    }

    /**
     * Formatted cart total.
     */
    public function formattedTotal(): string
    {
        return number_format($this->total(), 2, ',', ' ') . ' ' . config('shop.currency_label', 'DH');
    }
}
