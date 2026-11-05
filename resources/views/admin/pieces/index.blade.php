@extends('admin.layouts.admin')

@section('title', 'Gestion des Pièces')
@section('header_title', 'Gestion du Catalogue de Pièces')

@section('content')
<div class="space-y-6">



    <!-- Actions & Filter Bar -->
    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm space-y-4">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
            <h2 class="text-sm font-black text-slate-800">Filtres de recherche</h2>
            
            <div class="flex items-center gap-2 self-start sm:self-auto">
                <a href="{{ route('admin.export.pdf', request()->query()) }}" 
                   target="_blank"
                   class="inline-flex items-center px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-black shadow-md transition-all">
                    <svg class="w-4 h-4 mr-2 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Exporter PDF
                </a>
                <a href="{{ route('admin.pieces.create') }}" 
                   class="inline-flex items-center px-4 py-2.5 rounded-xl bg-brand-red hover:bg-brand-redHover text-white text-xs font-black shadow-md shadow-brand-red/20 transition-all">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nouvelle Pièce
                </a>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.pieces.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
            
            <!-- Search Keyword -->
            <div class="col-span-1 sm:col-span-2 lg:col-span-3">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Désignation, référence, variante..." 
                       class="w-full text-xs rounded-xl border-slate-300 focus:border-brand-red focus:ring-brand-red/30 py-2.5">
            </div>

            <!-- Filter Marque -->
            <div>
                <select name="marque_id" class="w-full text-xs rounded-xl border-slate-300 focus:border-brand-red focus:ring-brand-red/30 py-2.5">
                    <option value="">Toutes les marques</option>
                    @foreach ($marques as $m)
                        <option value="{{ $m->id }}" {{ request('marque_id') == $m->id ? 'selected' : '' }}>
                            {{ $m->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Famille -->
            <div>
                <select name="famille_id" class="w-full text-xs rounded-xl border-slate-300 focus:border-brand-red focus:ring-brand-red/30 py-2.5">
                    <option value="">Toutes les familles</option>
                    @foreach ($familles as $f)
                        <option value="{{ $f->id }}" {{ request('famille_id') == $f->id ? 'selected' : '' }}>
                            {{ $f->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Submit & Reset -->
            <div class="flex items-center space-x-2">
                <button type="submit" class="flex-1 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition-colors">
                    Filtrer
                </button>
                @if (request()->hasAny(['search', 'marque_id', 'famille_id', 'position', 'actif']))
                    <a href="{{ route('admin.pieces.index') }}" class="p-2.5 text-xs font-bold text-rose-600 hover:text-rose-800 rounded-xl bg-slate-100 hover:bg-slate-200" title="Réinitialiser">
                        &times;
                    </a>
                @endif
            </div>

        </form>
    </div>

    <!-- Pieces Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase font-black text-[10px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="p-4 w-10 text-center">
                            <input type="checkbox" id="select-all-pieces" class="rounded border-slate-300 text-brand-red focus:ring-brand-red cursor-pointer" title="Tout sélectionner">
                        </th>
                        <th class="p-4">Photo</th>
                        <th class="p-4">Désignation</th>
                        <th class="p-4">Véhicule</th>
                        <th class="p-4">Famille</th>
                        <th class="p-4">Position & Côtés</th>
                        <th class="p-4">Prix TTC</th>
                        <th class="p-4">Stock</th>
                        <th class="p-4">Statut</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($pieces as $piece)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            
                            <!-- Checkbox -->
                            <td class="p-4 text-center">
                                <input type="checkbox" value="{{ $piece->id }}" class="piece-checkbox rounded border-slate-300 text-brand-red focus:ring-brand-red cursor-pointer">
                            </td>

                            <!-- Thumbnail -->
                            <td class="p-4">
                                <img src="{{ $piece->image_url }}" alt="" class="w-12 h-12 rounded-xl object-cover bg-slate-950 flex-shrink-0 border border-slate-200">
                            </td>

                            <!-- Designation & Variant -->
                            <td class="p-4 font-bold text-slate-900 max-w-xs">
                                <a href="{{ route('catalog.piece', ['id' => $piece->id, 'slug' => $piece->slug]) }}" target="_blank" class="hover:text-brand-red transition-colors line-clamp-2">
                                    {{ $piece->designation }}
                                </a>
                                @if ($piece->variante)
                                    <span class="text-[10px] text-slate-500 font-medium block mt-0.5">{{ $piece->variante }}</span>
                                @endif
                                <span class="text-[10px] text-slate-400 font-mono">Année {{ $piece->annee_debut }}+</span>
                            </td>

                            <!-- Vehicle -->
                            <td class="p-4">
                                <span class="font-bold text-slate-800">{{ $piece->vehicule->marque->nom }}</span>
                                <span class="block text-slate-500 text-[11px]">{{ $piece->vehicule->nom }}</span>
                            </td>

                            <!-- Family -->
                            <td class="p-4 font-semibold text-slate-700">
                                {{ $piece->famille->nom }}
                            </td>

                            <!-- Position & Dispo -->
                            <td class="p-4">
                                <span class="inline-block px-2 py-0.5 rounded text-[10px] font-black bg-slate-900 text-white mb-1">
                                    {{ $piece->position }}
                                </span>
                                @if ($piece->isDoubleCote())
                                    <div class="text-[10px] space-x-1">
                                        <span class="{{ $piece->dispo_droite ? 'text-emerald-700 font-bold' : 'text-slate-400 line-through' }}">Droit</span>
                                        <span>/</span>
                                        <span class="{{ $piece->dispo_gauche ? 'text-emerald-700 font-bold' : 'text-slate-400 line-through' }}">Gauche</span>
                                    </div>
                                @else
                                    <div class="text-[10px] {{ $piece->en_stock ? 'text-emerald-700 font-bold' : 'text-rose-600' }}">
                                        {{ $piece->en_stock ? 'En stock' : 'Rupture' }}
                                    </div>
                                @endif
                            </td>

                            <!-- Price -->
                            <td class="p-4 font-black text-slate-900 whitespace-nowrap">
                                {{ $piece->formatted_prix }}
                            </td>

                            <!-- Stock -->
                            <td class="p-4 whitespace-nowrap">
                                <div class="flex items-center space-x-1.5">
                                    @if ($piece->stock > 5)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-black bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                            {{ $piece->stock }} en stock
                                        </span>
                                    @elseif ($piece->stock > 0)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-black bg-amber-50 text-amber-700 border border-amber-200" title="Stock faible">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span>
                                            {{ $piece->stock }} restant(s)
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-black bg-rose-50 text-rose-700 border border-rose-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span>
                                            Rupture (0)
                                        </span>
                                    @endif

                                    <!-- Quick adjust button -->
                                    <button type="button" 
                                            onclick="openStockModal({{ $piece->id }}, '{{ addslashes($piece->designation) }}', {{ (int) $piece->stock }})"
                                            class="p-1 rounded-md text-slate-400 hover:text-brand-red hover:bg-slate-100 transition-colors cursor-pointer"
                                            title="Modifier ou réapprovisionner le stock">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>

                            <!-- Toggle Active Status -->
                            <td class="p-4">
                                <form action="{{ route('admin.pieces.toggle-active', $piece) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black cursor-pointer transition-colors {{ $piece->actif ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-slate-200 text-slate-600 hover:bg-slate-300' }}"
                                            title="Cliquer pour activer/désactiver">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $piece->actif ? 'bg-emerald-600' : 'bg-slate-400' }} mr-1.5"></span>
                                        {{ $piece->actif ? 'Actif' : 'Inactif' }}
                                    </button>
                                </form>
                            </td>

                            <!-- Actions -->
                            <td class="p-4 text-right space-x-2 whitespace-nowrap">
                                <a href="{{ route('admin.pieces.edit', $piece) }}" class="text-xs font-bold text-slate-600 hover:text-brand-red">
                                    Modifier
                                </a>

                                <form action="{{ route('admin.pieces.destroy', $piece) }}" method="POST" class="inline-block" onsubmit="return confirm('Confirmer la suppression de la pièce {{ $piece->designation }} ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-bold text-rose-600 hover:text-rose-800">
                                        Supprimer
                                    </button>
                                </form>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="p-8 text-center text-slate-400">
                                Aucune pièce ne correspond à vos filtres.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($pieces->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $pieces->links() }}
            </div>
        @endif
    </div>

    <!-- Stock Adjustment Modal -->
    <div id="stock-modal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl border border-slate-200 overflow-hidden transform transition-all">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-black text-slate-900">Gestion & Réapprovisionnement du Stock</h3>
                    <p id="modal-piece-designation" class="text-xs text-slate-500 font-medium truncate max-w-xs mt-0.5"></p>
                </div>
                <button type="button" onclick="closeStockModal()" class="text-slate-400 hover:text-slate-700 p-1 rounded-lg cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form id="stock-form" method="POST" action="" class="p-5 space-y-4">
                @csrf
                @method('PATCH')

                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-600 uppercase">Stock actuellement disponible :</span>
                    <span id="modal-current-stock" class="text-lg font-black text-slate-900">0</span>
                </div>

                <!-- Définir la valeur absolue -->
                <div>
                    <label for="modal_stock_input" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                        Définir la nouvelle quantité en stock
                    </label>
                    <input type="number" 
                           id="modal_stock_input" 
                           name="stock" 
                           min="0" 
                           required
                           class="w-full text-sm font-bold rounded-xl border-slate-300 focus:border-brand-red focus:ring-brand-red/30 py-2.5">
                    <span class="text-[11px] text-slate-400 mt-1 block">La quantité est diminuée automatiquement lors des commandes.</span>
                </div>

                <!-- Réapprovisionnement rapide (+ X unités) -->
                <div class="pt-2 border-t border-slate-100">
                    <span class="block text-xs font-bold text-slate-700 uppercase mb-2">Ajouter du stock (+ unités) :</span>
                    <div class="flex gap-2">
                        <button type="button" onclick="quickAddStock(5)" class="flex-1 py-1.5 rounded-lg border border-slate-200 text-xs font-bold text-slate-700 hover:bg-brand-red hover:text-white hover:border-brand-red transition-colors cursor-pointer">+5</button>
                        <button type="button" onclick="quickAddStock(10)" class="flex-1 py-1.5 rounded-lg border border-slate-200 text-xs font-bold text-slate-700 hover:bg-brand-red hover:text-white hover:border-brand-red transition-colors cursor-pointer">+10</button>
                        <button type="button" onclick="quickAddStock(20)" class="flex-1 py-1.5 rounded-lg border border-slate-200 text-xs font-bold text-slate-700 hover:bg-brand-red hover:text-white hover:border-brand-red transition-colors cursor-pointer">+20</button>
                        <button type="button" onclick="quickAddStock(50)" class="flex-1 py-1.5 rounded-lg border border-slate-200 text-xs font-bold text-slate-700 hover:bg-brand-red hover:text-white hover:border-brand-red transition-colors cursor-pointer">+50</button>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-2">
                    <button type="button" onclick="closeStockModal()" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold cursor-pointer">
                        Annuler
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-brand-red hover:bg-brand-redHover text-white text-xs font-black shadow-md shadow-brand-red/20 transition-all cursor-pointer">
                        Mettre à jour le stock
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Floating Action Bar for Selected Pieces -->
    <div id="selection-bar" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 hidden bg-slate-900/95 backdrop-blur-md text-white px-5 py-3 rounded-2xl shadow-2xl border border-slate-700 flex items-center gap-4 animate-in fade-in slide-in-from-bottom-4 duration-200">
        <div class="flex items-center gap-2 text-xs">
            <span class="w-2.5 h-2.5 rounded-full bg-brand-red animate-pulse"></span>
            <span class="font-bold"><span id="selected-count">0</span> pièce(s) sélectionnée(s)</span>
        </div>
        <div class="flex items-center gap-2">
            <button id="btn-export-selection" type="button" class="px-4 py-2 rounded-xl bg-brand-red hover:bg-brand-redHover text-white text-xs font-black transition-all cursor-pointer shadow-md shadow-brand-red/30 flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Exporter la sélection en PDF
            </button>
            <button id="btn-clear-selection" type="button" class="text-xs text-slate-400 hover:text-white px-2 py-1 cursor-pointer">
                Annuler
            </button>
        </div>
    </div>

</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const selectAll   = document.getElementById('select-all-pieces');
        const checkboxes  = document.querySelectorAll('.piece-checkbox');
        const bar         = document.getElementById('selection-bar');
        const countSpan   = document.getElementById('selected-count');
        const exportBtn   = document.getElementById('btn-export-selection');
        const clearBtn    = document.getElementById('btn-clear-selection');

        function updateBar() {
            const checked = Array.from(checkboxes).filter(cb => cb.checked);
            countSpan.textContent = checked.length;
            if (checked.length > 0) {
                bar.classList.remove('hidden');
                bar.classList.add('flex');
            } else {
                bar.classList.add('hidden');
                bar.classList.remove('flex');
            }
            if (selectAll) {
                selectAll.checked = checkboxes.length > 0 && checked.length === checkboxes.length;
            }
        }

        if (selectAll) {
            selectAll.addEventListener('change', () => {
                checkboxes.forEach(cb => cb.checked = selectAll.checked);
                updateBar();
            });
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateBar);
        });

        if (clearBtn) {
            clearBtn.addEventListener('click', () => {
                checkboxes.forEach(cb => cb.checked = false);
                if (selectAll) selectAll.checked = false;
                updateBar();
            });
        }

        if (exportBtn) {
            exportBtn.addEventListener('click', () => {
                const ids = Array.from(checkboxes).filter(cb => cb.checked).map(cb => cb.value);
                if (ids.length === 0) return;
                const url = new URL("{{ route('admin.export.pdf') }}", window.location.origin);
                ids.forEach(id => url.searchParams.append('piece_ids[]', id));
                window.open(url.toString(), '_blank');
            });
        }
    });

    let currentModalPieceStock = 0;

    function openStockModal(id, designation, stock) {
        currentModalPieceStock = parseInt(stock) || 0;
        document.getElementById('modal-piece-designation').textContent = designation;
        document.getElementById('modal-current-stock').textContent = currentModalPieceStock + ' unité(s)';
        document.getElementById('modal_stock_input').value = currentModalPieceStock;
        document.getElementById('stock-form').action = '/admin/pieces/' + id + '/stock';
        
        const modal = document.getElementById('stock-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.getElementById('modal_stock_input').focus();
    }

    function closeStockModal() {
        const modal = document.getElementById('stock-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function quickAddStock(amount) {
        const input = document.getElementById('modal_stock_input');
        const current = parseInt(input.value) || 0;
        input.value = current + parseInt(amount);
    }

    // Fermer avec la touche Echap
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeStockModal();
        }
    });
</script>
@endpush
@endsection
