<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CatalogTemplateExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithColumnFormatting
{
    public function collection(): Collection
    {
        return collect([
            [
                'DACIA',
                'SANDERO 3',
                'OPTIQUES',
                'OPTIQUE AV H7 CHROME SANDERO 3',
                'RL',
                2021,
                'CHROME',
                850.00,
                'Oui',
                'Oui',
                '',
            ],
            [
                'DACIA',
                'LOGAN',
                'PARE-CHOCS',
                'PARE CHOC AVANT AVEC TROUS ANTIBROUILLARD',
                'AV',
                2017,
                'AVEC ANTIBROUILLARD',
                620.00,
                '',
                '',
                'Oui',
            ],
            [
                'RENAULT',
                'CLIO 4',
                'FEUX ARRIÈRE',
                'FEU ARRIERE ROUGE / FUME CLIO 4',
                'RL',
                2016,
                'ROUGE/FUME',
                480.00,
                'Oui',
                'Non',
                '',
            ],
            [
                'RENAULT',
                'MEGANE 4',
                'CALANDRES',
                'CALANDRE CENTRALE NOIRE MEGANE 4 GT',
                'CTRL',
                2019,
                'NOIR LAQUE',
                540.00,
                '',
                '',
                'Oui',
            ],
        ]);
    }

    public function headings(): array
    {
        return [
            'Marque',
            'Modèle',
            'Famille',
            'Désignation',
            'Position (AV, AR, CTRL, RL)',
            'Année Début',
            'Variante / Précision',
            'Prix TTC (MAD)',
            'Dispo Droite (RL)',
            'Dispo Gauche (RL)',
            'En Stock (AV/AR/CTRL)',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'F' => NumberFormat::FORMAT_NUMBER,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->getStyle('A1:K1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E51A1A'], // Brand red header
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
