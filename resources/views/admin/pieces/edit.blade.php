@extends('admin.layouts.admin')

@section('title', 'Modifier : ' . $piece->designation)
@section('header_title', 'Modifier une Pièce')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm">
        
        <div class="mb-6 pb-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-base font-black text-slate-900">Modifier : {{ $piece->designation }}</h2>
            <a href="{{ route('admin.pieces.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800">
                &larr; Retour à la liste
            </a>
        </div>

        @if ($piece->isAClasser())
            <div class="p-3 mb-6 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-900 text-xs font-bold flex items-center space-x-2">
                <span>⚠️ Cette pièce est actuellement « À classer ». Choisissez un véhicule ci-dessous pour la publier correctement.</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 mb-6 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs">
                <span class="font-bold block mb-1">Veuillez corriger les erreurs :</span>
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.pieces.update', $piece) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Designation -->
            <div>
                <label for="designation" class="block text-xs font-bold text-slate-700 uppercase mb-2">
                    Désignation complète <span class="text-brand-red">*</span>
                </label>
                <input type="text" 
                       id="designation" 
                       name="designation" 
                       value="{{ old('designation', $piece->designation) }}" 
                       required 
                       class="w-full text-sm font-semibold rounded-xl border-slate-300 focus:border-brand-red focus:ring-brand-red/30 py-2.5">
            </div>

            <!-- Vehicle & Family row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <!-- Vehicle searchable select -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-2">
                        Véhicule compatible
                    </label>
                    <div class="searchable-select relative" data-target="vehicule_id">
                        <div class="relative">
                            <input type="text"
                                   class="ss-input w-full text-xs rounded-xl border-slate-300 focus:border-brand-red focus:ring-brand-red/30 py-2.5 pr-9"
                                   placeholder="Rechercher un véhicule..."
                                   autocomplete="off">
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/></svg>
                            </div>
                        </div>
                        <div class="ss-dropdown hidden absolute left-0 right-0 top-full z-50 mt-1 bg-white border border-slate-200 rounded-xl shadow-2xl text-xs" style="max-height: 220px; overflow-y: auto; overscroll-behavior: contain;">
                            @foreach ($marques as $marque)
                                <div class="px-3 py-1.5 text-[10px] font-black uppercase tracking-wider text-slate-400 bg-slate-50 border-t border-slate-100 ss-group sticky top-0 z-10">{{ $marque->nom }}</div>
                                @foreach ($marque->vehicules as $vehicule)
                                    <div class="ss-option py-2 px-3 hover:bg-brand-red/5 hover:text-brand-red cursor-pointer"
                                         data-value="{{ $vehicule->id }}"
                                         data-label="{{ $marque->nom }} {{ $vehicule->nom }}"
                                         {{ old('vehicule_id', $piece->vehicule_id) == $vehicule->id ? 'data-selected=true' : '' }}>
                                        {{ $marque->nom }} — {{ $vehicule->nom }}
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                        <input type="hidden" id="vehicule_id" name="vehicule_id" value="{{ old('vehicule_id', $piece->vehicule_id) }}">
                    </div>
                </div>

                <!-- Family searchable select -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-2">
                        Famille de pièce <span class="text-brand-red">*</span>
                    </label>
                    <div class="searchable-select relative" data-target="famille_id" data-required="true">
                        <div class="relative">
                            <input type="text"
                                   class="ss-input w-full text-xs rounded-xl border-slate-300 focus:border-brand-red focus:ring-brand-red/30 py-2.5 pr-9"
                                   placeholder="Rechercher une famille..."
                                   autocomplete="off">
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/></svg>
                            </div>
                        </div>
                        <div class="ss-dropdown hidden absolute left-0 right-0 top-full z-50 mt-1 bg-white border border-slate-200 rounded-xl shadow-2xl text-xs" style="max-height: 220px; overflow-y: auto; overscroll-behavior: contain;">
                            @foreach ($familles as $famille)
                                <div class="ss-option py-2 px-3 hover:bg-brand-red/5 hover:text-brand-red cursor-pointer"
                                     data-value="{{ $famille->id }}"
                                     data-label="{{ $famille->nom }}"
                                     {{ old('famille_id', $piece->famille_id) == $famille->id ? 'data-selected=true' : '' }}>
                                    {{ $famille->nom }}
                                </div>
                            @endforeach
                        </div>
                        <input type="hidden" id="famille_id" name="famille_id" value="{{ old('famille_id', $piece->famille_id) }}">
                    </div>
                </div>

            </div>

            <!-- Year, Position & Price row -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                
                <!-- Année début -->
                <div>
                    <label for="annee_debut" class="block text-xs font-bold text-slate-700 uppercase mb-2">
                        Année début <span class="text-brand-red">*</span>
                    </label>
                    <input type="number" 
                           id="annee_debut" 
                           name="annee_debut" 
                           value="{{ old('annee_debut', $piece->annee_debut) }}" 
                           min="1990" 
                           max="{{ date('Y') + 1 }}" 
                           required 
                           class="w-full text-xs rounded-xl border-slate-300 focus:border-brand-red focus:ring-brand-red/30 py-2.5">
                </div>

                <!-- Position -->
                <div>
                    <label for="position" class="block text-xs font-bold text-slate-700 uppercase mb-2">
                        Position <span class="text-brand-red">*</span>
                    </label>
                    <select id="position" 
                            name="position" 
                            required 
                            onchange="updatePositionFields(this.value)"
                            class="w-full text-xs rounded-xl border-slate-300 focus:border-brand-red focus:ring-brand-red/30 py-2.5">
                        <option value="RL" {{ old('position', $piece->position) == 'RL' ? 'selected' : '' }}>Droite / Gauche (R/L)</option>
                        <option value="AV" {{ old('position', $piece->position) == 'AV' ? 'selected' : '' }}>Avant (AV)</option>
                        <option value="AR" {{ old('position', $piece->position) == 'AR' ? 'selected' : '' }}>Arrière (AR)</option>
                        <option value="CTRL" {{ old('position', $piece->position) == 'CTRL' ? 'selected' : '' }}>Centrale (CTRL)</option>
                    </select>
                </div>

                <!-- Prix TTC -->
                <div>
                    <label for="prix_ttc" class="block text-xs font-bold text-slate-700 uppercase mb-2">
                        Prix TTC ({{ config('shop.currency_label', 'DH') }}) <span class="text-brand-red">*</span>
                    </label>
                    <input type="number" 
                           step="0.01" 
                           id="prix_ttc" 
                           name="prix_ttc" 
                           value="{{ old('prix_ttc', $piece->prix_ttc) }}" 
                           required 
                           class="w-full text-xs font-black text-brand-red rounded-xl border-slate-300 focus:border-brand-red focus:ring-brand-red/30 py-2.5">
                </div>

            </div>

            <!-- Availability Settings Dynamic Block -->
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                <span class="block text-xs font-black uppercase text-slate-700 mb-3">Disponibilité du Stock</span>

                <!-- For RL Position -->
                <div id="rl-fields" class="grid grid-cols-2 gap-4">
                    <label class="flex items-center space-x-2 text-xs font-semibold text-slate-700 cursor-pointer">
                        <input type="checkbox" name="dispo_droite" value="1" {{ old('dispo_droite', $piece->dispo_droite) ? 'checked' : '' }} class="rounded text-brand-red focus:ring-brand-red">
                        <span>Côté Droit disponible</span>
                    </label>

                    <label class="flex items-center space-x-2 text-xs font-semibold text-slate-700 cursor-pointer">
                        <input type="checkbox" name="dispo_gauche" value="1" {{ old('dispo_gauche', $piece->dispo_gauche) ? 'checked' : '' }} class="rounded text-brand-red focus:ring-brand-red">
                        <span>Côté Gauche disponible</span>
                    </label>
                </div>

                <!-- For Single side position (AV, AR, CTRL) -->
                <div id="single-fields" class="hidden">
                    <label class="flex items-center space-x-2 text-xs font-semibold text-slate-700 cursor-pointer">
                        <input type="checkbox" name="en_stock" value="1" {{ old('en_stock', $piece->en_stock) ? 'checked' : '' }} class="rounded text-brand-red focus:ring-brand-red">
                        <span>En stock (pièce disponible à la commande)</span>
                    </label>
                </div>
            </div>

            <!-- Variant & Image row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="variante" class="block text-xs font-bold text-slate-700 uppercase mb-2">
                        Finition / Variante
                    </label>
                    <input type="text" 
                           id="variante" 
                           name="variante" 
                           value="{{ old('variante', $piece->variante) }}" 
                           class="w-full text-xs rounded-xl border-slate-300 focus:border-brand-red focus:ring-brand-red/30 py-2.5">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Photo actuelle</label>
                    <div class="mb-3 flex items-center space-x-3">
                        <img src="{{ $piece->image_url }}" alt="" class="w-16 h-12 object-cover bg-slate-950 rounded-lg border border-slate-200">
                        <span class="text-xs text-slate-500">{{ $piece->image ? 'Photo personnalisée' : 'Placeholder par défaut' }}</span>
                    </div>

                    <label for="image" class="block text-xs font-bold text-slate-700 uppercase mb-2">
                        Remplacer la photo
                    </label>
                    <input type="file" 
                           id="image" 
                           name="image" 
                           accept="image/png,image/jpeg,image/webp" 
                           class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-900 file:text-white hover:file:bg-brand-red">
                </div>
            </div>

            <!-- Active Checkbox -->
            <div class="pt-2">
                <label class="flex items-center space-x-2 text-xs font-bold text-slate-800 cursor-pointer">
                    <input type="checkbox" name="actif" value="1" {{ old('actif', $piece->actif) ? 'checked' : '' }} class="rounded text-brand-red focus:ring-brand-red">
                    <span>Activer cette pièce sur le catalogue public</span>
                </label>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('admin.pieces.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold">
                    Annuler
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-brand-red hover:bg-brand-redHover text-white text-xs font-black shadow-md shadow-brand-red/20 transition-all">
                    Enregistrer les modifications
                </button>
            </div>
        </form>

    </div>
</div>

@push('scripts')
<script>
    /* ── Searchable Select Component ──────────────────────────────── */
    document.addEventListener('DOMContentLoaded', () => {

        /* Position toggle */
        const posEl = document.getElementById('position');
        function updatePositionFields(pos) {
            document.getElementById('rl-fields').classList.toggle('hidden', pos !== 'RL');
            document.getElementById('single-fields').classList.toggle('hidden', pos === 'RL');
        }
        if (posEl) {
            posEl.addEventListener('change', e => updatePositionFields(e.target.value));
            updatePositionFields(posEl.value);
        }

        /* Searchable selects */
        document.querySelectorAll('.searchable-select').forEach(container => {
            const input    = container.querySelector('.ss-input');
            const dropdown = container.querySelector('.ss-dropdown');
            const hidden   = container.querySelector('input[type="hidden"]');
            const options  = container.querySelectorAll('.ss-option');
            const groups   = container.querySelectorAll('.ss-group');
            const isRequired = container.dataset.required === 'true';

            /* Set initial label if value pre-selected */
            const preSelected = container.querySelector('.ss-option[data-selected]');
            if (preSelected) {
                input.value = preSelected.dataset.label || preSelected.textContent.trim();
                preSelected.classList.add('bg-brand-red/10', 'font-bold', 'text-brand-red');
            }

            /* Prevent Enter from submitting the form while typing in search */
            input.addEventListener('keydown', e => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const first = Array.from(options).find(o => !o.classList.contains('hidden'));
                    if (first) first.click();
                } else if (e.key === 'Escape') {
                    dropdown.classList.add('hidden');
                }
            });

            /* Open dropdown */
            input.addEventListener('focus', () => {
                dropdown.classList.remove('hidden');
                dropdown.scrollTop = 0;
                filterOptions('');
            });

            /* Prevent mouse wheel scroll from scrolling the main browser page */
            dropdown.addEventListener('wheel', e => {
                const delta = e.deltaY;
                const up = delta < 0;
                const down = delta > 0;
                const atTop = dropdown.scrollTop <= 0;
                const atBottom = dropdown.scrollTop + dropdown.clientHeight >= dropdown.scrollHeight - 1;

                if ((up && atTop) || (down && atBottom)) {
                    e.preventDefault();
                }
            }, { passive: false });

            /* Filter while typing */
            input.addEventListener('input', () => {
                dropdown.scrollTop = 0;
                filterOptions(input.value);
            });

            document.addEventListener('click', e => {
                if (!container.contains(e.target)) dropdown.classList.add('hidden');
            });

            options.forEach(opt => {
                opt.addEventListener('click', () => {
                    const val   = opt.dataset.value ?? '';
                    const label = opt.dataset.label || opt.textContent.trim();
                    hidden.value = val;
                    input.value  = label;
                    options.forEach(o => o.classList.remove('bg-brand-red/10', 'font-bold', 'text-brand-red'));
                    opt.classList.add('bg-brand-red/10', 'font-bold', 'text-brand-red');
                    dropdown.classList.add('hidden');
                });
            });

            function filterOptions(query) {
                const q = query.toLowerCase().trim();
                options.forEach(opt => {
                    const text = (opt.dataset.label || opt.textContent).toLowerCase();
                    opt.classList.toggle('hidden', q !== '' && !text.includes(q));
                });
                groups.forEach(group => {
                    let sibling = group.nextElementSibling;
                    let visible = false;
                    while (sibling && !sibling.classList.contains('ss-group')) {
                        if (sibling.classList.contains('ss-option') && !sibling.classList.contains('hidden')) visible = true;
                        sibling = sibling.nextElementSibling;
                    }
                    group.classList.toggle('hidden', !visible);
                });
            }
        });
    });
</script>
<style>
    .ss-dropdown {
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 transparent;
    }
    .ss-dropdown::-webkit-scrollbar {
        width: 6px;
    }
    .ss-dropdown::-webkit-scrollbar-thumb {
        background-color: #cbd5e1;
        border-radius: 9999px;
    }
</style>
@endpush
@endsection
