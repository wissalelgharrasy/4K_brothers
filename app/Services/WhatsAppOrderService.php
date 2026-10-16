<?php

namespace App\Services;

class WhatsAppOrderService
{
    /**
     * Generate the WhatsApp message text and wa.me redirect link.
     *
     * @param array $cartItems
     * @param float $total
     * @param array $customerData ['nom', 'telephone', 'adresse', 'note']
     * @return array ['message' => string, 'url' => string]
     */
    public function generateOrderLink(array $cartItems, float $total, array $customerData): array
    {
        $currency = config('shop.currency_label', 'DH');
        $shopName = config('shop.name', '4K BROTHERS');
        $phone = preg_replace('/[^0-9]/', '', (string) config('shop.whatsapp_number', '212600000000'));

        $lines = [];
        $lines[] = "Bonjour *{$shopName}*, je souhaite commander :";
        $lines[] = "";

        $i = 1;
        $maxItemsToDetail = 20; // Guard against URL length overflow
        $totalItemsCount = count($cartItems);

        foreach (array_values($cartItems) as $index => $item) {
            if ($index >= $maxItemsToDetail) {
                $remaining = $totalItemsCount - $maxItemsToDetail;
                $lines[] = "... et {$remaining} autre(s) article(s).";
                break;
            }

            $coteTxt = '';
            if (!empty($item['cote_label']) && $item['cote_label'] !== 'Standard') {
                $coteTxt = " — {$item['cote_label']}";
            }

            $formattedLignePrix = number_format((float) $item['total_ligne'], 2, ',', ' ') . ' ' . $currency;
            $lines[] = "{$i}. {$item['designation']}{$coteTxt} — x{$item['quantite']} — {$formattedLignePrix}";
            $i++;
        }

        $lines[] = "";
        $formattedTotal = number_format($total, 2, ',', ' ') . ' ' . $currency;
        $lines[] = "*Total : {$formattedTotal}*";
        $lines[] = "";

        $nom = trim((string) ($customerData['nom'] ?? ''));
        $tel = trim((string) ($customerData['telephone'] ?? ''));
        $adresse = trim((string) ($customerData['adresse'] ?? ''));
        $note = trim((string) ($customerData['note'] ?? ''));

        $lines[] = "Nom : {$nom}";
        $lines[] = "Téléphone : {$tel}";
        if (!empty($adresse)) {
            $lines[] = "Adresse / Ville : {$adresse}";
        }
        if (!empty($note)) {
            $lines[] = "Note : {$note}";
        }

        $message = implode("\n", $lines);

        // Encode for URL
        $encodedMessage = rawurlencode($message);
        $url = "https://wa.me/{$phone}?text={$encodedMessage}";

        return [
            'message' => $message,
            'url' => $url,
            'phone' => $phone,
        ];
    }
}
