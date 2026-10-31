@extends('admin.layouts.admin')

@section('title', 'Tableau de bord')
@section('header_title', 'Tableau de bord — 4K BROTHERS')

@section('content')
<div class="space-y-8">

    <!-- Top Alert if there are pieces "À classer" -->
    @if ($stats['pieces_a_classer_count'] > 0)
        <div class="p-5 rounded-2xl bg-amber-500/10 border-2 border-amber-500/40 text-amber-900 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center font-black flex-shrink-0">
                    !
                </div>
                <div>
                    <h3 class="font-black text-sm text-slate-900">
                        {{ $stats['pieces_a_classer_count'] }} pièce(s) orpheline(s) en attente de classement !
                    </h3>
                    <p class="text-xs text-slate-600 mt-0.5">
                        Ces pièces ont été importées ou créées sans véhicule associé. Elles nécessitent d'être affectées à un modèle pour apparaître dans les filtres clients.
                    </p>
                </div>
            </div>
            <a href="{{ route('admin.pieces.index', ['a_classer' => '1']) }}" 
               class="px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-black transition-colors whitespace-nowrap">
                Classer ces pièces &rarr;
            </a>
        </div>
    @endif

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Total Pieces -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Catalogue Pièces</span>
                <span class="p-2 rounded-lg bg-brand-red/10 text-brand-red">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </span>
            </div>
            <div class="text-3xl font-black text-slate-900">{{ $stats['pieces_count'] }}</div>
            <div class="mt-2 text-xs text-slate-500 flex items-center space-x-2">
                <span class="text-emerald-600 font-bold">{{ $stats['pieces_actives_count'] }} actives</span>
                <span>&bull;</span>
                <span class="text-slate-400">{{ $stats['pieces_rl_count'] }} droite/gauche</span>
            </div>
        </div>

        <!-- Pièces à classer -->
        <div class="bg-white rounded-2xl p-6 border {{ $stats['pieces_a_classer_count'] > 0 ? 'border-amber-300 bg-amber-50/20' : 'border-slate-200' }} shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Statut « À classer »</span>
                <span class="p-2 rounded-lg {{ $stats['pieces_a_classer_count'] > 0 ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-400' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </span>
            </div>
            <div class="text-3xl font-black {{ $stats['pieces_a_classer_count'] > 0 ? 'text-amber-600' : 'text-slate-900' }}">
                {{ $stats['pieces_a_classer_count'] }}
            </div>
            <div class="mt-2 text-xs text-slate-500">
                @if ($stats['pieces_a_classer_count'] > 0)
                    <a href="{{ route('admin.pieces.index', ['a_classer' => '1']) }}" class="text-amber-700 font-bold hover:underline">
                        Assigner un véhicule &rarr;
                    </a>
                @else
                    <span>Toutes les pièces sont associées</span>
                @endif
            </div>
        </div>

        <!-- Marques -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Constructeurs</span>
                <span class="p-2 rounded-lg bg-sky-50 text-sky-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </span>
            </div>
            <div class="text-3xl font-black text-slate-900">{{ $stats['marques_count'] }}</div>
            <div class="mt-2 text-xs text-slate-500">
                {{ $stats['vehicules_count'] }} modèles référencés
            </div>
        </div>

        <!-- Catalogue PDF -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Catalogue PDF</span>
                <span class="p-2 rounded-lg bg-brand-red/10 text-brand-red">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                </span>
            </div>
            <div class="text-base font-black text-slate-900">
                Sur Mesure & Filtres
            </div>
            <div class="mt-2 text-xs">
                <a href="{{ route('admin.catalogue.builder') }}" class="text-brand-red font-bold hover:underline">
                    Composer un catalogue &rarr;
                </a>
            </div>
        </div>

    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
        <h2 class="text-xs font-black uppercase tracking-wider text-slate-400 mb-4">Actions Rapides</h2>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.pieces.create') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-brand-red hover:bg-brand-redHover text-white text-xs font-black shadow-md shadow-brand-red/20 transition-all">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Ajouter une Pièce
            </a>

            <a href="{{ route('admin.catalogue.builder') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition-colors">
                <svg class="w-4 h-4 mr-2 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Créateur Catalogue PDF
            </a>

            <a href="{{ route('admin.marques.create') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Ajouter une Marque
            </a>

            <a href="{{ route('admin.vehicules.create') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Ajouter un Modèle
            </a>
        </div>
    </div>

    <!-- Recent Pieces Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-sm font-black text-slate-900">Dernières Pièces Ajoutées</h2>
            <a href="{{ route('admin.pieces.index') }}" class="text-xs font-bold text-brand-red hover:underline">
                Voir toutes les pièces &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase font-black text-[10px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="p-4">Pièce</th>
                        <th class="p-4">Véhicule</th>
                        <th class="p-4">Famille</th>
                        <th class="p-4">Position</th>
                        <th class="p-4">Prix TTC</th>
                        <th class="p-4">Statut</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($recentPieces as $piece)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="p-4 font-bold text-slate-900 flex items-center space-x-3">
                                <img src="{{ $piece->image_url }}" alt="" class="w-10 h-10 rounded-lg object-cover bg-slate-950 flex-shrink-0">
                                <span class="line-clamp-1">{{ $piece->designation }}</span>
                            </td>
                            <td class="p-4">
                                @if ($piece->vehicule)
                                    <span class="font-bold text-slate-800">{{ $piece->vehicule->marque->nom }} {{ $piece->vehicule->nom }}</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-black bg-amber-100 text-amber-800">
                                        À classer
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 text-slate-600">{{ $piece->famille->nom }}</td>
                            <td class="p-4 font-bold text-slate-800">{{ $piece->position }}</td>
                            <td class="p-4 font-black text-slate-900">{{ $piece->formatted_prix }}</td>
                            <td class="p-4">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $piece->actif ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-600' }}">
                                    {{ $piece->actif ? 'Actif' : 'Inactif' }}
                                </span>
                            </td>
                            <td class="p-4 text-right space-x-2">
                                <a href="{{ route('admin.pieces.edit', $piece) }}" class="text-slate-600 hover:text-brand-red font-bold">
                                    Modifier
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
