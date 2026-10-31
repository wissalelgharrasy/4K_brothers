@extends('admin.layouts.admin')

@section('title', 'Créateur de Catalogue PDF')
@section('header_title', 'Composer un Catalogue PDF Personnalisé')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto pb-24">

    <!-- Form container -->
    <form id="catalog-builder-form" action="{{ route('admin.export.pdf') }}" method="GET" target="_blank">
        <input type="hidden" name="download" value="1">

        <!-- Top Header Card: Title & Notes -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-5">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h2 class="text-base font-black text-slate-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        1. Informations de l'en-tête du catalogue
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Personnalisez le titre principal et les mentions qui apparaîtront en haut de votre document PDF.
                    </p>
                </div>
                <button type="submit" 
                        id="btn-top-generate-pdf"
                        disabled
                        class="inline-flex items-center px-4 py-2 rounded-xl bg-brand-red hover:bg-brand-redHover disabled:opacity-40 disabled:cursor-not-allowed text-white text-xs font-black shadow-md shadow-brand-red/20 transition-all cursor-pointer">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Enregistrer le PDF (<span class="selected-count-text">0</span>)
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="custom_title" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                        Titre du catalogue <span class="text-slate-400 font-normal">(Optionnel)</span>
                    </label>
                    <input type="text" 
                           id="custom_title" 
                           name="custom_title" 
                           placeholder="Ex. CATALOGUE PIÈCES BERLINGO, OFFRE SPÉCIALE MARS..." 
                           class="w-full text-xs font-semibold rounded-xl border-slate-300 focus:border-brand-red focus:ring-brand-red/30 py-2.5">
                    <span class="text-[11px] text-slate-400 mt-1 block">Laisser vide pour afficher uniquement le tableau.</span>
                </div>

                <div>
                    <label for="custom_note" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                        Sous-titre ou note client <span class="text-slate-400 font-normal">(Optionnel)</span>
                    </label>
                    <input type="text" 
                           id="custom_note" 
                           name="custom_note" 
                           placeholder="Ex. Contact : 06 XX XX XX XX • Offre valable jusqu'au 31/12" 
                           class="w-full text-xs rounded-xl border-slate-300 focus:border-brand-red focus:ring-brand-red/30 py-2.5">
                    <span class="text-[11px] text-slate-400 mt-1 block">Mention affichée sous le titre du catalogue.</span>
                </div>
            </div>
        </div>

        <!-- Selection Card -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mt-6">
            
            <!-- Controls & Quick Filters -->
            <div class="p-5 border-b border-slate-100 space-y-4 bg-slate-50/50">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-base font-black text-slate-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                            2. Sélectionnez les pièces à inclure
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Cochez les pièces que vous voulez mettre dans ce catalogue spécifique.
                        </p>
                    </div>

                    <!-- Quick buttons -->
                    <div class="flex items-center gap-2">
                        <button type="button" id="btn-toggle-selected-only" class="px-3 py-1.5 rounded-lg bg-red-50 border border-red-200 text-xs font-bold text-red-700 hover:bg-red-100 cursor-pointer shadow-sm">
                            🎯 Voir ma sélection (<span id="only-selected-badge">0</span>)
                        </button>
                        <button type="button" id="btn-select-all-visible" class="px-3 py-1.5 rounded-lg bg-white border border-slate-300 text-xs font-bold text-slate-700 hover:bg-slate-100 cursor-pointer shadow-sm">
                            Tout cocher
                        </button>
                        <button type="button" id="btn-deselect-all" class="px-3 py-1.5 rounded-lg bg-white border border-slate-300 text-xs font-bold text-slate-700 hover:bg-slate-100 cursor-pointer shadow-sm">
                            Tout décocher
                        </button>
                    </div>
                </div>

                <!-- Instant Filter Inputs -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2">
                    <!-- Live Search -->
                    <div>
                        <input type="text" 
                               id="live-search" 
                               placeholder="Rechercher par désignation..." 
                               class="w-full text-xs rounded-xl border-slate-300 focus:border-brand-red focus:ring-brand-red/30 py-2">
                    </div>

                    <!-- Filter by Brand -->
                    <div>
                        <select id="filter-brand" class="w-full text-xs rounded-xl border-slate-300 focus:border-brand-red focus:ring-brand-red/30 py-2">
                            <option value="">Toutes les marques</option>
                            @foreach($marques as $m)
                                <option value="{{ $m->nom }}">{{ $m->nom }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter by Family -->
                    <div>
                        <select id="filter-family" class="w-full text-xs rounded-xl border-slate-300 focus:border-brand-red focus:ring-brand-red/30 py-2">
                            <option value="">Toutes les familles</option>
                            @foreach($familles as $f)
                                <option value="{{ $f->nom }}">{{ $f->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Pieces Table List -->
            <div class="overflow-x-auto max-h-[550px] overflow-y-auto">
                <table class="w-full text-left text-xs" id="pieces-table">
                    <thead class="bg-slate-50 text-slate-500 uppercase font-black text-[10px] tracking-wider border-b border-slate-200 sticky top-0 z-10">
                        <tr>
                            <th class="p-3 w-12 text-center">
                                <input type="checkbox" id="master-checkbox" class="rounded border-slate-300 text-brand-red focus:ring-brand-red">
                            </th>
                            <th class="p-3 w-16">Photo</th>
                            <th class="p-3">Désignation</th>
                            <th class="p-3">Marque / Véhicule</th>
                            <th class="p-3">Famille</th>
                            <th class="p-3">Position</th>
                            <th class="p-3 text-right">Prix TTC</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($pieces as $piece)
                            <tr class="piece-row hover:bg-slate-50 transition-colors"
                                data-designation="{{ strtolower($piece->designation . ' ' . $piece->variante) }}"
                                data-brand="{{ strtolower($piece->vehicule?->marque?->nom ?? '') }}"
                                data-family="{{ strtolower($piece->famille?->nom ?? '') }}">
                                
                                <td class="p-3 text-center">
                                    <input type="checkbox" 
                                           name="piece_ids[]" 
                                           value="{{ $piece->id }}" 
                                           class="piece-item-cb rounded border-slate-300 text-brand-red focus:ring-brand-red cursor-pointer">
                                </td>

                                <td class="p-3">
                                    <img src="{{ $piece->image_url }}" alt="" class="w-10 h-10 rounded-lg object-contain bg-white border border-slate-200">
                                </td>

                                <td class="p-3 font-bold text-slate-900">
                                    <label class="cursor-pointer">
                                        {{ $piece->designation }}
                                        @if($piece->variante)
                                            <span class="block text-[10px] text-slate-400 font-normal">{{ $piece->variante }}</span>
                                        @endif
                                    </label>
                                </td>

                                <td class="p-3 text-slate-600">
                                    @if($piece->vehicule)
                                        <b>{{ $piece->vehicule->marque->nom }}</b> — {{ $piece->vehicule->nom }}
                                    @else
                                        <span class="text-amber-600 italic">Universelle</span>
                                    @endif
                                </td>

                                <td class="p-3 font-medium text-slate-600">
                                    {{ $piece->famille->nom ?? '-' }}
                                </td>

                                <td class="p-3">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700">
                                        {{ $piece->position === 'RL' ? 'R / L' : $piece->position }}
                                    </span>
                                </td>

                                <td class="p-3 text-right font-black text-slate-900">
                                    {{ number_format((float)$piece->prix_ttc, 2, ',', ' ') }} DH
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-8 text-center text-slate-400">
                                    Aucune pièce trouvée dans la base de données.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-3 bg-slate-50 border-t border-slate-100 text-xs text-slate-500 flex justify-between items-center">
                <span id="visible-count-label">{{ $pieces->count() }} pièces visibles</span>
                <span class="text-slate-400 text-[11px]">Cochez les pièces souhaitées puis cliquez sur le bouton rouge ci-dessous.</span>
            </div>

        </div>

        <!-- Floating Action Bar for Selected Pieces (hidden when 0 selected, positioned on bottom-right to never overlap sidebar) -->
        <div id="selection-bar-builder" class="fixed bottom-6 right-6 md:right-10 z-50 hidden bg-slate-900/95 backdrop-blur-md text-white px-5 py-3.5 rounded-2xl shadow-2xl border border-slate-700 items-center gap-4 animate-in fade-in slide-in-from-bottom-4 duration-200">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-full bg-brand-red text-white font-black flex items-center justify-center text-xs shadow-md shadow-brand-red/30" id="selected-count-badge">
                    0
                </div>
                <div class="text-xs font-bold text-white pr-2">
                    <span class="selected-count-text">0</span> pièce(s) sélectionnée(s)
                </div>
            </div>

            <button type="submit" 
                    id="btn-generate-pdf"
                    class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-brand-red hover:bg-brand-redHover text-white text-xs font-black shadow-lg shadow-brand-red/30 transition-all cursor-pointer">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Enregistrer le Catalogue PDF
            </button>
        </div>

    </form>

</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const checkboxes   = document.querySelectorAll('.piece-item-cb');
        const masterCb     = document.getElementById('master-checkbox');
        const rows         = document.querySelectorAll('.piece-row');
        const liveSearch   = document.getElementById('live-search');
        const filterBrand  = document.getElementById('filter-brand');
        const filterFamily = document.getElementById('filter-family');
        const btnSelectAll = document.getElementById('btn-select-all-visible');
        const btnDeselect  = document.getElementById('btn-deselect-all');
        const btnToggleSel = document.getElementById('btn-toggle-selected-only');
        const onlyBadge    = document.getElementById('only-selected-badge');
        const bar          = document.getElementById('selection-bar-builder');
        const badgeCount   = document.getElementById('selected-count-badge');
        const textCounts   = document.querySelectorAll('.selected-count-text');
        const btnSubmit    = document.getElementById('btn-generate-pdf');
        const btnTopSubmit = document.getElementById('btn-top-generate-pdf');
        const visibleLabel = document.getElementById('visible-count-label');

        let showSelectedOnly = false;

        function updateSelectionCount() {
            const count = Array.from(checkboxes).filter(cb => cb.checked).length;
            if (badgeCount) badgeCount.textContent = count;
            textCounts.forEach(el => el.textContent = count);
            if (onlyBadge) onlyBadge.textContent = count;
            if (btnTopSubmit) btnTopSubmit.disabled = count === 0;

            if (count > 0) {
                bar.classList.remove('hidden');
                bar.classList.add('flex');
            } else {
                bar.classList.add('hidden');
                bar.classList.remove('flex');
            }
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', () => {
                updateSelectionCount();
                const visibleCbs = Array.from(checkboxes).filter(c => !c.closest('tr').classList.contains('hidden'));
                masterCb.checked = visibleCbs.length > 0 && visibleCbs.every(c => c.checked);
            });
        });

        masterCb.addEventListener('change', () => {
            const visibleCbs = Array.from(checkboxes).filter(c => !c.closest('tr').classList.contains('hidden'));
            visibleCbs.forEach(c => c.checked = masterCb.checked);
            updateSelectionCount();
        });

        btnSelectAll.addEventListener('click', () => {
            const visibleCbs = Array.from(checkboxes).filter(c => !c.closest('tr').classList.contains('hidden'));
            visibleCbs.forEach(c => c.checked = true);
            masterCb.checked = true;
            updateSelectionCount();
        });

        btnDeselect.addEventListener('click', () => {
            checkboxes.forEach(c => c.checked = false);
            masterCb.checked = false;
            updateSelectionCount();
        });

        btnToggleSel.addEventListener('click', () => {
            showSelectedOnly = !showSelectedOnly;
            btnToggleSel.classList.toggle('bg-brand-red', showSelectedOnly);
            btnToggleSel.classList.toggle('text-white', showSelectedOnly);
            btnToggleSel.classList.toggle('bg-red-50', !showSelectedOnly);
            btnToggleSel.classList.toggle('text-red-700', !showSelectedOnly);
            filterRows();
        });

        function filterRows() {
            const searchVal = liveSearch.value.toLowerCase().trim();
            const brandVal  = filterBrand.value.toLowerCase().trim();
            const familyVal = filterFamily.value.toLowerCase().trim();

            let visibleCount = 0;

            rows.forEach(row => {
                const cb = row.querySelector('.piece-item-cb');
                const isChecked = cb && cb.checked;

                if (showSelectedOnly && !isChecked) {
                    row.classList.add('hidden');
                    return;
                }

                const desig  = row.dataset.designation || '';
                const brand  = row.dataset.brand || '';
                const family = row.dataset.family || '';

                const matchesSearch = !searchVal || desig.includes(searchVal);
                const matchesBrand  = !brandVal  || brand === brandVal;
                const matchesFamily = !familyVal || family === familyVal;

                const show = matchesSearch && matchesBrand && matchesFamily;
                row.classList.toggle('hidden', !show);
                if (show) visibleCount++;
            });

            visibleLabel.textContent = `${visibleCount} pièces visibles`;
        }

        liveSearch.addEventListener('input', filterRows);
        filterBrand.addEventListener('change', filterRows);
        filterFamily.addEventListener('change', filterRows);
    });
</script>
@endpush
@endsection
