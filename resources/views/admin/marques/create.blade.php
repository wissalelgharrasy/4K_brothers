@extends('admin.layouts.admin')

@section('title', 'Ajouter une Marque')
@section('header_title', 'Ajouter une Marque')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm">
        
        <div class="mb-6 pb-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-base font-black text-slate-900">Nouvelle Marque de Véhicules</h2>
            <a href="{{ route('admin.marques.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800">
                &larr; Retour à la liste
            </a>
        </div>

        @if ($errors->any())
            <div class="p-4 mb-6 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.marques.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label for="nom" class="block text-xs font-bold text-slate-700 uppercase mb-2">
                    Nom du constructeur <span class="text-brand-red">*</span>
                </label>
                <input type="text" 
                       id="nom" 
                       name="nom" 
                       value="{{ old('nom') }}" 
                       required 
                       placeholder="Ex. Dacia, Renault, Peugeot..."
                       class="w-full text-sm rounded-xl border-slate-300 focus:border-brand-red focus:ring-brand-red/30 py-2.5">
            </div>

            <!-- Logo Section -->
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase">
                        Logo de la marque (facultatif)
                    </label>
                </div>

                <!-- Preview container -->
                <div id="logo-preview-box" class="hidden items-center space-x-3 p-3 bg-white rounded-xl border border-slate-200">
                    <img id="logo-preview-img" src="" alt="Aperçu logo" class="w-14 h-14 object-contain rounded-lg p-1 border border-slate-200 bg-white">
                    <div>
                        <span class="text-xs font-bold text-slate-800 block">Aperçu du logo sélectionné</span>
                        <span id="logo-preview-desc" class="text-[11px] text-slate-500">Prêt à être enregistré</span>
                    </div>
                </div>

                <div>
                    <label for="logo" class="block text-xs font-semibold text-slate-600 mb-1">
                        1. Téléverser un fichier image
                    </label>
                    <input type="file" 
                           id="logo" 
                           name="logo" 
                           accept="image/png,image/jpeg,image/webp,image/svg+xml" 
                           class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-900 file:text-white hover:file:bg-brand-red file:transition-colors">
                    <span class="text-[11px] text-slate-400 mt-1 block">
                        Formats acceptés : PNG, JPG, WebP, SVG. Taille maximale : 2 Mo.
                    </span>
                </div>

                <div class="pt-2 border-t border-slate-200">
                    <label for="logo_url" class="block text-xs font-semibold text-slate-600 mb-1">
                        2. OU saisir directement une URL d'image externe
                    </label>
                    <input type="text" 
                           id="logo_url" 
                           name="logo_url" 
                           value="{{ old('logo_url') }}" 
                           placeholder="https://exemple.com/logo.png"
                           class="w-full text-xs rounded-xl border-slate-300 focus:border-brand-red focus:ring-brand-red/30 py-2">
                    <span class="text-[11px] text-slate-400 mt-1 block">
                        Si renseigné sans fichier téléversé, cette URL sera enregistrée directement dans la base de données.
                    </span>
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end space-x-3">
                <a href="{{ route('admin.marques.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold">
                    Annuler
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-brand-red hover:bg-brand-redHover text-white text-xs font-black shadow-md shadow-brand-red/20 transition-all">
                    Enregistrer la marque
                </button>
            </div>
        </form>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const fileInput = document.getElementById('logo');
                const urlInput = document.getElementById('logo_url');
                const previewBox = document.getElementById('logo-preview-box');
                const previewImg = document.getElementById('logo-preview-img');
                const previewDesc = document.getElementById('logo-preview-desc');

                fileInput.addEventListener('change', function () {
                    if (this.files && this.files[0]) {
                        const file = this.files[0];
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            previewImg.src = e.target.result;
                            previewDesc.textContent = file.name + ' (' + Math.round(file.size / 1024) + ' Ko)';
                            previewBox.classList.remove('hidden');
                            previewBox.classList.add('flex');
                        };
                        reader.readAsDataURL(file);
                    }
                });

                urlInput.addEventListener('input', function () {
                    if (this.value.trim().length > 5 && (!fileInput.files || fileInput.files.length === 0)) {
                        previewImg.src = this.value.trim();
                        previewDesc.textContent = 'Image depuis URL';
                        previewBox.classList.remove('hidden');
                        previewBox.classList.add('flex');
                    }
                });
            });
        </script>

    </div>
</div>
@endsection
