<?php

namespace App\Exports;

use App\Models\Piece;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CatalogExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithColumnFormatting
{
    public function __construct(
        protected ?int $marqueId = null,
        protected ?int $familleId = null,
        protected ?bool $onlyActive = true
    ) {}

    public function collection(): Collection
    {
        $query = Piece::with(['famille', 'vehicule.marque']);

        if ($this->onlyActive) {
            $query->where('actif', true);
        }

        if ($this->marqueId) {
            $query->whereHas('vehicule', fn ($v) => $v->where('marque_id', $this->marqueId));
        }

        if ($this->familleId) {
            $query->where('famille_id', $this->familleId);
        }

        return $query->orderBy('designation')->get();
    }

    public function headings(): array
    {
        return [
            'Marque',
            'Modèle',
            'Famille',
            'Désignation',
            'Position',
            'Année Début',
            'Variante',
            'Prix TTC (MAD)',
            'Dispo Droite',
            'Dispo Gauche',
            'En Stock',
            'Statut',
        ];
    }

    /**
     * @param Piece $piece
     */
    public function map($piece): array
    {
        $isRL = ($piece->position === 'RL');

        return [
            $piece->vehicule?->marque?->nom ?? 'N/A',
            $piece->vehicule?->nom ?? 'À classer',
            $piece->famille?->nom ?? 'DIVERS',
            $piece->designation,
            $piece->position,
            $piece->annee_debut,
            $piece->variante ?? '',
            $piece->prix_ttc,
            $isRL ? ($piece->dispo_droite ? 'Oui' : 'Non') : '-',
            $isRL ? ($piece->dispo_gauche ? 'Oui' : 'Non') : '-',
            !$isRL ? ($piece->en_stock ? 'Oui' : 'Non') : '-',
            $piece->actif ? 'Actif' : 'Inactif',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // 1,250.00
            'F' => NumberFormat::FORMAT_NUMBER, // 2021
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        // En-tête stylisé aux couleurs 4K Brothers (Dark Slate 900 & White text)
        $sheet->getStyle('A1:L1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0F172A'], // Slate-900
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sheet->getRowDimension(1)->setRowHeight(28);

        return [];
    }
}
