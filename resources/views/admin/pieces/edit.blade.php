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
                
                <!-- Vehicle -->
                <div>
                    <label for="vehicule_id" class="block text-xs font-bold text-slate-700 uppercase mb-2">
                        Véhicule compatible
                    </label>
                    <select id="vehicule_id" name="vehicule_id" class="w-full text-xs rounded-xl border-slate-300 focus:border-brand-red focus:ring-brand-red/30 py-2.5">
                        <option value="">⚠️ Aucun véhicule (statut « À classer »)</option>
                        @foreach ($marques as $marque)
                            <optgroup label="{{ $marque->nom }}">
                                @foreach ($marque->vehicules as $vehicule)
                                    <option value="{{ $vehicule->id }}" {{ old('vehicule_id', $piece->vehicule_id) == $vehicule->id ? 'selected' : '' }}>
                                        {{ $marque->nom }} {{ $vehicule->nom }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>

                <!-- Family -->
                <div>
                    <label for="famille_id" class="block text-xs font-bold text-slate-700 uppercase mb-2">
                        Famille de pièce <span class="text-brand-red">*</span>
                    </label>
                    <select id="famille_id" name="famille_id" required class="w-full text-xs rounded-xl border-slate-300 focus:border-brand-red focus:ring-brand-red/30 py-2.5">
                        @foreach ($familles as $famille)
                            <option value="{{ $famille->id }}" {{ old('famille_id', $piece->famille_id) == $famille->id ? 'selected' : '' }}>
                                {{ $famille->nom }}
                            </option>
                        @endforeach
                    </select>
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
    function updatePositionFields(pos) {
        const rlFields = document.getElementById('rl-fields');
        const singleFields = document.getElementById('single-fields');
        if (pos === 'RL') {
            rlFields.classList.remove('hidden');
            singleFields.classList.add('hidden');
        } else {
            rlFields.classList.add('hidden');
            singleFields.classList.remove('hidden');
        }
    }
    document.addEventListener('DOMContentLoaded', () => {
        updatePositionFields(document.getElementById('position').value);
    });
</script>
@endpush
@endsection
