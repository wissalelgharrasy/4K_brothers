<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Catalogue Pièces — 4K BROTHERS</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: #f1f5f9;
            color: #111827;
            padding: 24px 16px;
        }

        /* ── Top Toolbar (Screen only) ───────────────────────── */
        .toolbar {
            max-width: 900px;
            margin: 0 auto 20px auto;
            background: #ffffff;
            padding: 14px 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            border: 1px solid #e2e8f0;
        }

        .toolbar-info {
            font-size: 13px;
            color: #475569;
        }

        .toolbar-info strong {
            color: #0f172a;
            font-size: 15px;
        }

        .toolbar-actions {
            display: flex;
            gap: 10px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.15s ease;
        }

        .btn-download {
            background-color: #E51A1A;
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(229, 26, 26, 0.35);
        }

        .btn-download:hover {
            background-color: #CC1111;
        }

        .btn-print {
            background-color: #f1f5f9;
            color: #1e293b;
            border: 1px solid #cbd5e1;
        }

        .btn-print:hover {
            background-color: #e2e8f0;
        }

        .btn-back {
            background-color: #0f172a;
            color: #ffffff;
        }

        .btn-back:hover {
            background-color: #1e293b;
        }

        /* ── Catalog Container ───────────────────────────────── */
        .catalog-container {
            max-width: 900px;
            margin: 0 auto;
            background: #ffffff;
            padding: 20px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
            border-radius: 4px;
        }

        /* ── Main Catalog Table ──────────────────────────────── */
        table.catalog-table {
            width: 100%;
            border-collapse: collapse;
            border: 1.5px solid #222222;
            table-layout: fixed;
            background: #ffffff;
        }

        table.catalog-table thead th {
            background-color: #E51A1A;
            color: #ffffff;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 0.5px;
            text-align: center;
            padding: 10px 8px;
            border: 1.5px solid #222222;
            text-transform: uppercase;
        }

        /* Column widths */
        table.catalog-table th.col-images,
        table.catalog-table td.col-images {
            width: 26%;
        }

        table.catalog-table th.col-designation,
        table.catalog-table td.col-designation {
            width: 44%;
        }

        table.catalog-table th.col-disp,
        table.catalog-table td.col-disp {
            width: 14%;
        }

        table.catalog-table th.col-prix,
        table.catalog-table td.col-prix {
            width: 16%;
        }

        table.catalog-table tbody tr {
            page-break-inside: avoid;
            break-inside: avoid;
        }

        table.catalog-table tbody td {
            border: 1.5px solid #222222;
            padding: 12px 10px;
            vertical-align: middle;
        }

        /* Cell: Images */
        td.col-images {
            text-align: center;
            background: #ffffff;
        }

        .piece-img {
            max-width: 140px;
            max-height: 85px;
            width: auto;
            height: auto;
            object-fit: contain;
            display: inline-block;
            vertical-align: middle;
        }

        /* Cell: Designation */
        td.col-designation {
            text-align: center;
        }

        .designation-title {
            font-size: 13px;
            font-weight: 800;
            color: #000000;
            text-transform: uppercase;
            line-height: 1.35;
            margin-bottom: 6px;
        }

        .designation-meta {
            font-size: 10px;
            color: #555555;
            line-height: 1.45;
        }

        /* Cell: Disponibilité */
        td.col-disp {
            text-align: center;
        }

        .disp-wrapper {
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .circle-badge {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 800;
            color: #ffffff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.25);
            margin: 0 auto;
        }

        .badge-green {
            background-color: #15803d;
            border: 1px solid #166534;
        }

        .badge-gray {
            background-color: #94a3b8;
            border: 1px solid #64748b;
        }

        .badge-red {
            background-color: #dc2626;
            border: 1px solid #b91c1c;
        }

        /* Cell: Prix */
        td.col-prix {
            text-align: center;
            font-size: 15px;
            font-weight: 800;
            color: #000000;
        }

        /* ── Brand Separator Header (for multi-brand catalogs) ─ */
        tr.brand-group-row {
            page-break-inside: avoid;
            page-break-after: avoid;
            break-after: avoid;
        }

        td.brand-group-header {
            background-color: #0f172a;
            color: #ffffff;
            font-size: 11px;
            font-weight: 900;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 8px 14px;
            border: 1.5px solid #222222;
            text-align: left;
        }

        /* ── Empty State ─────────────────────────────────────── */
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #64748b;
            font-size: 14px;
        }

        /* ── Print Media Styles ──────────────────────────────── */
        @media print {
            body {
                background: #ffffff !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .toolbar {
                display: none !important;
            }

            .catalog-container {
                max-width: 100% !important;
                box-shadow: none !important;
                border-radius: 0 !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            table.catalog-table {
                width: 100% !important;
                border: 1.5px solid #000000 !important;
            }

            table.catalog-table thead {
                display: table-header-group !important;
            }

            table.catalog-table thead th {
                background-color: #E51A1A !important;
                color: #ffffff !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                border: 1.5px solid #000000 !important;
            }

            table.catalog-table tbody tr {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }

            table.catalog-table tbody td {
                border: 1.5px solid #000000 !important;
            }

            .circle-badge {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .badge-green {
                background-color: #15803d !important;
            }

            .badge-gray {
                background-color: #94a3b8 !important;
            }

            td.brand-group-header {
                background-color: #0f172a !important;
                color: #ffffff !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                border: 1.5px solid #000000 !important;
            }

            @page {
                size: A4 portrait;
                margin: 8mm;
            }
        }
    </style>
</head>
<body>

    <!-- Top Action Toolbar (hidden on print) -->
    <div class="toolbar">
        <div class="toolbar-info">
            <strong>Export Catalogue PDF</strong> &bull; {{ $pieces->count() }} pièce(s)
            @if($selectedMarque || $selectedFamille || $selectedVehicule)
                <div style="font-size: 11px; margin-top: 2px;">
                    Filtres :
                    @if($selectedMarque) Marque : <b>{{ $selectedMarque->nom }}</b> @endif
                    @if($selectedVehicule) | Modèle : <b>{{ $selectedVehicule->nom }}</b> @endif
                    @if($selectedFamille) | Famille : <b>{{ $selectedFamille->nom }}</b> @endif
                </div>
            @endif
        </div>
        <div class="toolbar-actions">
            <button id="btn-download-pdf" onclick="downloadPDF()" class="btn btn-download">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Enregistrer en PDF
            </button>
            <button onclick="window.print()" class="btn btn-print">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Imprimer
            </button>
            <a href="{{ url()->previous() ?: route('admin.catalogue.builder') }}" class="btn btn-back">
                &larr; Retour
            </a>
        </div>
    </div>

    <!-- Main Catalog Printable Area -->
    <div class="catalog-container" id="catalog-content">
        @if(!empty($customTitle))
            <div style="margin-bottom: 16px; padding-bottom: 12px; border-bottom: 2px solid #222; display: flex; justify-content: space-between; align-items: flex-end;">
                <div>
                    <h1 style="font-size: 20px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.5px; margin: 0; color: #111;">
                        {{ $customTitle }}
                    </h1>
                    @if(!empty($customNote))
                        <p style="font-size: 11px; color: #555; margin-top: 4px;">{{ $customNote }}</p>
                    @endif
                </div>
                <div style="font-size: 11px; font-weight: 700; color: #64748b; text-align: right;">
                    4K BROTHERS &bull; {{ date('d/m/Y') }}
                </div>
            </div>
        @endif

        @if($pieces->isEmpty())
            <div class="empty-state">
                Aucune pièce ne correspond aux filtres sélectionnés.
            </div>
        @else
            <table class="catalog-table">
                <thead>
                    <tr>
                        <th class="col-images">IMAGES</th>
                        <th class="col-designation">DESIGNATION</th>
                        <th class="col-disp">DISP</th>
                        <th class="col-prix">PRIX</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $groupedByMarque = $pieces->groupBy(fn($p) => $p->vehicule?->marque?->nom ?? 'Générale / Universelle');
                    @endphp

                    @foreach($groupedByMarque as $marqueNom => $marquePieces)
                        @if($groupedByMarque->count() > 1)
                            <tr class="brand-group-row">
                                <td colspan="4" class="brand-group-header">
                                    CONSTRUCTEUR : {{ strtoupper($marqueNom) }} &bull; ({{ $marquePieces->count() }} pièce{{ $marquePieces->count() > 1 ? 's' : '' }})
                                </td>
                            </tr>
                        @endif

                        @foreach($marquePieces as $piece)
                            <tr>
                                <!-- Image -->
                                <td class="col-images">
                                    <img src="{{ $piece->image_url }}" alt="{{ $piece->designation }}" class="piece-img">
                                </td>

                                <!-- Designation -->
                                <td class="col-designation">
                                    <div class="designation-title">{{ $piece->designation }}</div>
                                    <div class="designation-meta">
                                        @if($piece->vehicule)
                                            Modèle: <b>{{ $piece->vehicule->nom }}</b> &bull; 
                                        @endif
                                        Famille: {{ $piece->famille->nom ?? 'GÉNÉRALE' }}
                                    </div>
                                    <div class="designation-meta">
                                        Année: {{ $piece->annee_debut }} &bull; 
                                        Position: {{ $piece->position === 'RL' ? 'R / L' : $piece->position }} &bull; 
                                        {{ $piece->isDisponible() ? 'Disponible' : 'Sur commande' }}
                                    </div>
                                </td>

                                <!-- Disponibilité -->
                                <td class="col-disp">
                                    <div class="disp-wrapper">
                                        @if($piece->position === 'RL')
                                            <div class="circle-badge {{ $piece->dispo_droite ? 'badge-green' : 'badge-gray' }}" title="Côté Droit">R</div>
                                            <div class="circle-badge {{ $piece->dispo_gauche ? 'badge-green' : 'badge-gray' }}" title="Côté Gauche">L</div>
                                        @else
                                            <div class="circle-badge {{ $piece->en_stock ? 'badge-green' : 'badge-red' }}">
                                                {{ $piece->en_stock ? 'OK' : 'NON' }}
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                <!-- Prix -->
                                <td class="col-prix">
                                    {{ number_format((float)$piece->prix_ttc, 2, ',', ' ') }}
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        function downloadPDF() {
            const btn = document.getElementById('btn-download-pdf');
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<span>⏳ Génération du PDF...</span>';
            btn.disabled = true;

            const element = document.getElementById('catalog-content');
            const opt = {
                margin:       [8, 8, 8, 8],
                filename:     'catalogue_4k_brothers_{{ date('Y-m-d_His') }}.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, useCORS: true, logging: false },
                jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' },
                pagebreak:    { mode: ['avoid-all', 'css', 'legacy'] }
            };

            html2pdf().set(opt).from(element).save().then(() => {
                btn.innerHTML = originalHtml;
                btn.disabled = false;
            }).catch(err => {
                console.error('Erreur html2pdf:', err);
                btn.innerHTML = originalHtml;
                btn.disabled = false;
                if (confirm("Le téléchargement automatique a rencontré un souci d'affichage. Voulez-vous utiliser la boîte d'impression pour enregistrer en PDF ?")) {
                    window.print();
                }
            });
        }

        @if(request()->boolean('download'))
        window.addEventListener('load', () => {
            setTimeout(downloadPDF, 600);
        });
        @endif
    </script>
</body>
</html>
