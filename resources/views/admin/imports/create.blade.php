@extends('admin.layouts.admin')

@section('title', 'Importer un Catalogue Fournisseur')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Back Button & Header -->
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.imports.index') }}" class="inline-flex items-center text-xs font-bold text-slate-500 hover:text-slate-900 transition-colors mb-2">
                <svg class="w-4 h-4 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Retour à l'historique des imports
            </a>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Importer un fichier fournisseur</h1>
            <p class="text-xs text-slate-500 mt-1">
                Formats acceptés : <strong>Excel (.xlsx, .xls)</strong> ou <strong>CSV</strong> (jusqu'à 15 Mo).
            </p>
        </div>

        <a href="{{ route('admin.imports.template') }}" 
           class="inline-flex items-center px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-colors">
            <svg class="w-4 h-4 mr-1.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            Télécharger le modèle vierge (.xlsx)
        </a>
    </div>

    <!-- Upload Card -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8">
        <form action="{{ route('admin.imports.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Drag & Drop Zone -->
            <div x-data="{ fileName: null, isDropping: false }" class="space-y-2">
                <label class="block text-xs font-black uppercase tracking-wider text-slate-700">
                    Sélectionner le fichier de catalogue <span class="text-brand-red">*</span>
                </label>

                <div @dragover.prevent="isDropping = true"
                     @dragleave.prevent="isDropping = false"
                     @drop.prevent="isDropping = false; $refs.fileInput.files = $event.dataTransfer.files; fileName = $refs.fileInput.files[0]?.name"
                     :class="isDropping ? 'border-brand-red bg-red-50/50' : 'border-slate-300 hover:border-slate-400 bg-slate-50/60'"
                     class="border-2 border-dashed rounded-2xl p-8 sm:p-12 text-center transition-all cursor-pointer relative"
                     @click="$refs.fileInput.click()">
                    
                    <input type="file" 
                           name="fichier" 
                           x-ref="fileInput" 
                           accept=".xlsx,.xls,.csv" 
                           class="hidden" 
                           @change="fileName = $refs.fileInput.files[0]?.name"
                           required>

                    <div class="space-y-3">
                        <div class="w-14 h-14 rounded-2xl bg-white shadow-sm border border-slate-200 flex items-center justify-center mx-auto text-brand-red">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                        </div>

                        <div>
                            <p class="text-sm font-bold text-slate-800" x-text="fileName ? 'Fichier sélectionné : ' + fileName : 'Glissez-déposez votre fichier ici, ou cliquez pour parcourir'"></p>
                            <p class="text-xs text-slate-500 mt-1">Excel (.xlsx, .xls) ou CSV &bull; Taille maximum : 15 Mo</p>
                        </div>

                        <div class="pt-2">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold text-brand-red bg-red-50 border border-brand-red/20">
                                Parcourir les dossiers
                            </span>
                        </div>
                    </div>
                </div>

                @error('fichier')
                    <p class="text-xs font-bold text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="pt-4 flex items-center justify-end space-x-3 border-t border-slate-100">
                <a href="{{ route('admin.imports.index') }}" 
                   class="px-4 py-2.5 rounded-xl border border-slate-300 text-xs font-bold text-slate-700 hover:bg-slate-50 transition-colors">
                    Annuler
                </a>

                <button type="submit" 
                        class="px-6 py-2.5 rounded-xl bg-brand-red hover:bg-red-700 text-white text-xs font-black shadow-lg shadow-brand-red/30 transition-all flex items-center space-x-2 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    <span>Lancer l'importation</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Rules & Smart Parser Documentation -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-4">
        <h3 class="text-sm font-black text-slate-900 flex items-center">
            <svg class="w-4 h-4 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Fonctionnement du moteur d'import intelligent
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs text-slate-600">
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1.5">
                <span class="font-black text-slate-800 block text-xs uppercase tracking-wider">1. En-têtes flexibles</span>
                <p>L'ordre des colonnes n'a pas d'importance. Les en-têtes sont reconnus automatiquement sans distinction de majuscules ou d'accents (ex: <em>Désignation</em>, <em>Prix TTC</em>, <em>Modèle</em>, <em>Famille</em>, <em>Position</em>, etc.).</p>
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1.5">
                <span class="font-black text-slate-800 block text-xs uppercase tracking-wider">2. Mise à jour sans doublon</span>
                <p>Si une pièce existe déjà dans le catalogue (même désignation, véhicule, côté et année), son prix et ses disponibilités sont actualisés. Aucun doublon n'est généré.</p>
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1.5">
                <span class="font-black text-slate-800 block text-xs uppercase tracking-wider">3. Déductions automatiques</span>
                <p>Si la position (AV, AR, RL) ou l'année ne sont pas précisées dans une colonne dédiée, l'algorithme les extrait automatiquement depuis la désignation (ex. <em>"OPTIQUE AV LOGAN 13"</em> &rarr; AV, 2013).</p>
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1.5">
                <span class="font-black text-slate-800 block text-xs uppercase tracking-wider">4. Rapport d'erreurs téléchargeable</span>
                <p>Si une ligne présente un prix manquant ou une désignation vide, elle est simplement écartée sans bloquer les autres lignes. Un rapport CSV est généré à la fin.</p>
            </div>
        </div>
    </div>

</div>
@endsection
