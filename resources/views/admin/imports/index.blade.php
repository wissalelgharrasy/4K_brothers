@extends('admin.layouts.admin')

@section('title', 'Import & Export Catalogue')

@section('content')
<div class="space-y-6">

    <!-- Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Import & Export Catalogue</h1>
            <p class="text-xs text-slate-500 mt-1">
                Gérez les imports de fichiers fournisseurs (.xlsx, .csv) et exportez l'inventaire complet.
            </p>
        </div>

        <div class="flex items-center flex-wrap gap-2.5">
            <!-- Download Template Button -->
            <a href="{{ route('admin.imports.template') }}" 
               class="inline-flex items-center px-3.5 py-2.5 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold shadow-sm transition-colors"
               title="Télécharger un modèle vierge pré-formaté">
                <svg class="w-4 h-4 mr-2 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Modèle Excel type
            </a>

            <!-- New Import Button -->
            <a href="{{ route('admin.imports.create') }}" 
               class="inline-flex items-center px-4 py-2.5 rounded-xl bg-brand-red hover:bg-red-700 text-white text-xs font-black shadow-lg shadow-brand-red/20 transition-all">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                Importer un catalogue
            </a>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Pièces en base</span>
            <span class="text-2xl font-black text-slate-900 mt-1 block">{{ number_format($stats['total_pieces'], 0, ',', ' ') }}</span>
            <span class="text-[11px] text-slate-400 mt-1 block">Catalogue actuellement actif</span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Imports réalisés</span>
            <span class="text-2xl font-black text-slate-900 mt-1 block">{{ $stats['total_imports'] }}</span>
            <span class="text-[11px] text-slate-400 mt-1 block">Fichiers fournisseurs traités</span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider block">Pièces créées</span>
            <span class="text-2xl font-black text-emerald-600 mt-1 block">{{ number_format($stats['total_crees'], 0, ',', ' ') }}</span>
            <span class="text-[11px] text-slate-400 mt-1 block">Nouvelles références ajoutées</span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-xs font-bold text-blue-600 uppercase tracking-wider block">Prix mis à jour</span>
            <span class="text-2xl font-black text-blue-600 mt-1 block">{{ number_format($stats['total_mis_a_jour'], 0, ',', ' ') }}</span>
            <span class="text-[11px] text-slate-400 mt-1 block">Sans création de doublons</span>
        </div>
    </div>

    <!-- Export Filter Bar -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h3 class="text-sm font-bold text-slate-900 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Exporter le catalogue (PDF)
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">
                    Générez un catalogue prêt à imprimer ou enregistrer en PDF avec photos, désignation, disponibilité et prix.
                </p>
            </div>

            <form action="{{ route('admin.export.pdf') }}" method="GET" target="_blank" class="flex flex-wrap items-center gap-3">
                <!-- Filter Marque -->
                <select name="marque_id" class="text-xs rounded-xl border-slate-300 focus:border-brand-red focus:ring-brand-red/30 py-2 pl-3 pr-8">
                    <option value="">Toutes les marques</option>
                    @foreach($marques as $m)
                        <option value="{{ $m->id }}">{{ $m->nom }}</option>
                    @endforeach
                </select>

                <!-- Filter Famille -->
                <select name="famille_id" class="text-xs rounded-xl border-slate-300 focus:border-brand-red focus:ring-brand-red/30 py-2 pl-3 pr-8">
                    <option value="">Toutes les familles</option>
                    @foreach($familles as $f)
                        <option value="{{ $f->id }}">{{ $f->nom }}</option>
                    @endforeach
                </select>

                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold shadow-sm transition-colors cursor-pointer">
                    <svg class="w-4 h-4 mr-1.5 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Télécharger le catalogue PDF
                </button>
            </form>
        </div>
    </div>

    <!-- Import History Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-sm font-black text-slate-900">Historique des imports de catalogue</h2>
            <span class="text-xs text-slate-500">{{ $imports->total() }} fichier(s) au total</span>
        </div>

        @if($imports->isEmpty())
            <div class="p-12 text-center">
                <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mx-auto text-slate-400 mb-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-slate-700">Aucun import enregistré</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto mt-1">
                    Commencez par importer un fichier Excel ou CSV de catalogue fournisseur pour alimenter le catalogue.
                </p>
                <div class="mt-4">
                    <a href="{{ route('admin.imports.create') }}" class="inline-flex items-center px-4 py-2 rounded-xl bg-brand-red text-white text-xs font-bold">
                        Importer un catalogue maintenant
                    </a>
                </div>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-700">
                    <thead class="bg-slate-50 text-[11px] uppercase font-bold text-slate-500 border-b border-slate-100">
                        <tr>
                            <th class="p-4">Fichier</th>
                            <th class="p-4">Date & Heure</th>
                            <th class="p-4">Auteur</th>
                            <th class="p-4 text-center">Lignes</th>
                            <th class="p-4 text-center">Créées</th>
                            <th class="p-4 text-center">Mises à jour</th>
                            <th class="p-4 text-center">Erreurs</th>
                            <th class="p-4 text-center">Statut</th>
                            <th class="p-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($imports as $imp)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="p-4 font-semibold text-slate-900">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <span class="truncate max-w-xs" title="{{ $imp->fichier }}">{{ $imp->nom_original }}</span>
                                    </div>
                                </td>
                                <td class="p-4 text-slate-500">
                                    {{ $imp->date_import ? $imp->date_import->format('d/m/Y H:i') : $imp->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="p-4 text-slate-500">
                                    {{ $imp->user?->name ?? 'Système' }}
                                </td>
                                <td class="p-4 text-center font-bold text-slate-900">
                                    {{ $imp->nb_lignes }}
                                </td>
                                <td class="p-4 text-center">
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800">
                                        +{{ $imp->nb_crees }}
                                    </span>
                                </td>
                                <td class="p-4 text-center">
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-black bg-blue-100 text-blue-800">
                                        {{ $imp->nb_mis_a_jour }}
                                    </span>
                                </td>
                                <td class="p-4 text-center">
                                    @if($imp->nb_erreurs > 0)
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-black bg-rose-100 text-rose-800">
                                            {{ $imp->nb_erreurs }} rejetée(s)
                                        </span>
                                    @else
                                        <span class="text-slate-400 font-medium">0</span>
                                    @endif
                                </td>
                                <td class="p-4 text-center">
                                    @if($imp->statut === 'termine')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                            Succès
                                        </span>
                                    @elseif($imp->statut === 'partiel')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span>
                                            Partiel
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span>
                                            Échec
                                        </span>
                                    @endif
                                </td>
                                <td class="p-4 text-right space-x-1.5 whitespace-nowrap">
                                    <a href="{{ route('admin.imports.show', $imp) }}" 
                                       class="inline-flex items-center px-2.5 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-colors">
                                        Rapport détaillé
                                    </a>

                                    @if($imp->hasErrors())
                                        <a href="{{ route('admin.imports.errors-csv', $imp) }}" 
                                           class="inline-flex items-center px-2.5 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold transition-colors"
                                           title="Télécharger les lignes rejetées en CSV">
                                            CSV Erreurs
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($imports->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $imports->links() }}
                </div>
            @endif
        @endif
    </div>

</div>
@endsection
