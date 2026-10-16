@extends('admin.layouts.admin')

@section('title', 'Rapport d\'importation — ' . $import->nom_original)

@section('content')
<div class="space-y-6">

    <!-- Header & Navigation -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="{{ route('admin.imports.index') }}" class="inline-flex items-center text-xs font-bold text-slate-500 hover:text-slate-900 transition-colors mb-2">
                <svg class="w-4 h-4 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Retour à l'historique
            </a>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight flex items-center space-x-2">
                <span>Rapport d'importation</span>
                <span class="text-sm font-semibold text-slate-500">#{{ $import->id }}</span>
            </h1>
            <p class="text-xs text-slate-500 mt-1">
                Fichier : <strong class="text-slate-700">{{ $import->nom_original }}</strong> &bull; 
                Date : {{ $import->date_import ? $import->date_import->format('d/m/Y à H:i') : $import->created_at->format('d/m/Y à H:i') }} &bull; 
                Par : {{ $import->user?->name ?? 'Administrateur' }}
            </p>
        </div>

        <div class="flex items-center space-x-2.5">
            @if($import->hasErrors())
                <a href="{{ route('admin.imports.errors-csv', $import) }}" 
                   class="inline-flex items-center px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-black shadow-sm transition-all">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Télécharger les erreurs (CSV)
                </a>
            @endif

            <a href="{{ route('admin.pieces.index') }}" 
               class="inline-flex items-center px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold shadow-sm transition-all">
                Voir les pièces dans le catalogue
                <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>

    <!-- 4 Result Stat Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Processed -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Lignes traitées</span>
            <span class="text-3xl font-black text-slate-900 mt-1 block">{{ $import->nb_lignes }}</span>
            <span class="text-[11px] text-slate-400 mt-1 block">Total des lignes du fichier</span>
        </div>

        <!-- Created -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider block">Nouvelles pièces</span>
            <span class="text-3xl font-black text-emerald-600 mt-1 block">+{{ $import->nb_crees }}</span>
            <span class="text-[11px] text-slate-400 mt-1 block">Ajoutées au catalogue actif</span>
        </div>

        <!-- Updated -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-xs font-bold text-blue-600 uppercase tracking-wider block">Prix / Stocks mis à jour</span>
            <span class="text-3xl font-black text-blue-600 mt-1 block">{{ $import->nb_mis_a_jour }}</span>
            <span class="text-[11px] text-slate-400 mt-1 block">Pièces existantes actualisées</span>
        </div>

        <!-- Errors -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-xs font-bold text-rose-600 uppercase tracking-wider block">Lignes rejetées</span>
            <span class="text-3xl font-black text-rose-600 mt-1 block">{{ $import->nb_erreurs }}</span>
            <span class="text-[11px] text-slate-400 mt-1 block">Pour données manquantes/invalides</span>
        </div>
    </div>

    <!-- Error Breakdown Table if any -->
    @if($import->hasErrors())
        @php
            $errorRows = $import->rapport_erreurs_json ?? [];
        @endphp

        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-rose-50/40">
                <div class="flex items-center space-x-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-600"></span>
                    <h2 class="text-sm font-black text-slate-900">Détail des {{ count($errorRows) }} ligne(s) non importée(s)</h2>
                </div>

                <a href="{{ route('admin.imports.errors-csv', $import) }}" 
                   class="inline-flex items-center text-xs font-bold text-rose-700 hover:text-rose-900 underline">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Télécharger en format CSV
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-700">
                    <thead class="bg-slate-50 text-[11px] uppercase font-bold text-slate-500 border-b border-slate-100">
                        <tr>
                            <th class="p-4 w-20 text-center">N° Ligne</th>
                            <th class="p-4">Désignation</th>
                            <th class="p-4">Motif du Rejet</th>
                            <th class="p-4">Données Brutes Détectées</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($errorRows as $err)
                            <tr class="hover:bg-rose-50/30 transition-colors">
                                <td class="p-4 text-center font-black text-slate-500">
                                    #{{ $err['ligne'] ?? '?' }}
                                </td>
                                <td class="p-4 font-bold text-slate-900">
                                    {{ $err['designation'] ?? 'N/A' }}
                                </td>
                                <td class="p-4">
                                    <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-bold bg-rose-100 text-rose-800">
                                        {{ $err['motif'] ?? 'Erreur de format' }}
                                    </span>
                                </td>
                                <td class="p-4 font-mono text-[11px] text-slate-500 max-w-md truncate" title="{{ is_array($err['donnees'] ?? null) ? json_encode($err['donnees']) : ($err['donnees'] ?? '') }}">
                                    @if(is_array($err['donnees'] ?? null))
                                        {{ collect($err['donnees'])->map(fn($v, $k) => "$k: $v")->implode(' | ') }}
                                    @else
                                        {{ $err['donnees'] ?? '-' }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="bg-emerald-50 border border-emerald-200 rounded-3xl p-8 text-center space-y-2">
            <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center mx-auto mb-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h3 class="text-base font-black text-emerald-900">Importation 100% sans erreur</h3>
            <p class="text-xs text-emerald-700 max-w-md mx-auto">
                Toutes les lignes du fichier ont été correctement analysées, créées ou mises à jour dans la base de données.
            </p>
        </div>
    @endif

</div>
@endsection
