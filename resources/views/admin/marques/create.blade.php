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

            <div>
                <label for="logo" class="block text-xs font-bold text-slate-700 uppercase mb-2">
                    Logo de la marque (facultatif)
                </label>
                <input type="file" 
                       id="logo" 
                       name="logo" 
                       accept="image/png,image/jpeg,image/webp" 
                       class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-900 file:text-white hover:file:bg-brand-red file:transition-colors">
                <span class="text-[11px] text-slate-400 mt-1 block">Formats acceptés : PNG, JPG, WebP. 2 Mo maximum.</span>
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

    </div>
</div>
@endsection
