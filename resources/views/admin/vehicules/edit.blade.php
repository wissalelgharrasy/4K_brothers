@extends('admin.layouts.admin')

@section('title', 'Modifier le Véhicule ' . $vehicule->nom)
@section('header_title', 'Modifier un Véhicule')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm">
        
        <div class="mb-6 pb-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-base font-black text-slate-900">Modifier : {{ $vehicule->marque->nom }} {{ $vehicule->nom }}</h2>
            <a href="{{ route('admin.vehicules.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800">
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

        <form action="{{ route('admin.vehicules.update', $vehicule) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="marque_id" class="block text-xs font-bold text-slate-700 uppercase mb-2">
                    Marque parente <span class="text-brand-red">*</span>
                </label>
                <select id="marque_id" name="marque_id" required class="w-full text-sm rounded-xl border-slate-300 focus:border-brand-red focus:ring-brand-red/30 py-2.5">
                    @foreach ($marques as $marque)
                        <option value="{{ $marque->id }}" {{ old('marque_id', $vehicule->marque_id) == $marque->id ? 'selected' : '' }}>
                            {{ $marque->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="nom" class="block text-xs font-bold text-slate-700 uppercase mb-2">
                    Nom du modèle <span class="text-brand-red">*</span>
                </label>
                <input type="text" 
                       id="nom" 
                       name="nom" 
                       value="{{ old('nom', $vehicule->nom) }}" 
                       required 
                       class="w-full text-sm rounded-xl border-slate-300 focus:border-brand-red focus:ring-brand-red/30 py-2.5">
            </div>

            <div class="pt-4 flex items-center justify-end space-x-3">
                <a href="{{ route('admin.vehicules.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold">
                    Annuler
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-brand-red hover:bg-brand-redHover text-white text-xs font-black shadow-md shadow-brand-red/20 transition-all">
                    Enregistrer les modifications
                </button>
            </div>
        </form>

    </div>
</div>
@endsection
