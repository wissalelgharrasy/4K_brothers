@extends('admin.layouts.admin')

@section('title', 'Tableau de bord')
@section('header_title', 'Tableau de bord — 4K BROTHERS')

@section('content')
<div class="space-y-8">

    <!-- Welcome / System Status Banner -->
    <div class="relative overflow-hidden bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 text-white rounded-3xl p-6 sm:p-8 shadow-xl border border-slate-800">
        <!-- Background subtle glow -->
        <div class="absolute -right-16 -top-16 w-64 h-64 bg-brand-red/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-40 -bottom-20 w-48 h-48 bg-sky-500/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md text-[11px] font-bold text-slate-200 mb-3 border border-white/10">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Système opérationnel &bull; Base MySQL active</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-black tracking-tight text-white">
                    Bienvenue sur le Panel 4K BROTHERS
                </h1>
                <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl">
                    Gérez en temps réel votre catalogue de pièces de carrosserie, vos compatibilités véhicules, vos tarifs et vos exports personnalisés.
                </p>
            </div>

            <!-- Quick Primary Actions in Banner -->
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('admin.pieces.create') }}" 
                   class="inline-flex items-center px-4 py-2.5 rounded-xl bg-brand-red hover:bg-brand-redHover text-white text-xs font-black shadow-lg shadow-brand-red/30 transition-all hover:-translate-y-0.5">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nouvelle Pièce
                </a>
                <a href="{{ route('admin.catalogue.builder') }}" 
                   class="inline-flex items-center px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold border border-white/15 transition-all">
                    <svg class="w-4 h-4 mr-2 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Catalogue sur Mesure
                </a>
            </div>
        </div>
    </div>

    <!-- 1. KPI Stats Cards Grid -->
    <div>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xs font-black uppercase tracking-wider text-slate-400">Indicateurs Clés du Catalogue</h2>
            <span class="text-[11px] text-slate-400 font-medium">Statistiques mises à jour en direct</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

            <!-- Card 1: Pièces Référencées -->
            <div class="group bg-white rounded-2xl p-6 border border-slate-200/90 shadow-sm hover:shadow-md hover:border-slate-300 transition-all">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-black text-slate-400 uppercase tracking-wider">Pièces au Catalogue</span>
                    <div class="w-11 h-11 rounded-2xl bg-slate-100 text-slate-600 flex items-center justify-center transition-transform group-hover:scale-110">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                </div>
                <div class="flex items-baseline space-x-2">
                    <span class="text-3xl font-black text-slate-900 tracking-tight">{{ $stats['pieces_count'] }}</span>
                    <span class="text-xs text-slate-500 font-medium">références</span>
                </div>
                <div class="mt-3 flex items-center justify-between pt-3 border-t border-slate-100 text-xs">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">
                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400 mr-1.5"></span>
                        {{ $stats['pieces_actives_count'] }} en vente
                    </span>
                    <a href="{{ route('admin.pieces.index') }}" class="font-bold text-brand-red hover:underline inline-flex items-center gap-1">
                        Gérer &rarr;
                    </a>
                </div>
            </div>

            <!-- Card 2: Véhicules & Modèles -->
            <div class="group bg-white rounded-2xl p-6 border border-slate-200/90 shadow-sm hover:shadow-md hover:border-slate-300 transition-all">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-black text-slate-400 uppercase tracking-wider">Modèles Référencés</span>
                    <div class="w-11 h-11 rounded-2xl bg-slate-100 text-slate-600 flex items-center justify-center transition-transform group-hover:scale-110">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                        </svg>
                    </div>
                </div>
                <div class="flex items-baseline space-x-2">
                    <span class="text-3xl font-black text-slate-900 tracking-tight">{{ $stats['vehicules_count'] }}</span>
                    <span class="text-xs text-slate-500 font-medium">modèles auto</span>
                </div>
                <div class="mt-3 flex items-center justify-between pt-3 border-t border-slate-100 text-xs">
                    <span class="text-slate-500 text-[11px] font-medium">
                        Répartis sur <strong>{{ $stats['marques_count'] }}</strong> constructeurs
                    </span>
                    <a href="{{ route('admin.vehicules.index') }}" class="font-bold text-brand-red hover:underline inline-flex items-center gap-1">
                        Modèles &rarr;
                    </a>
                </div>
            </div>

            <!-- Card 3: Familles de Pièces -->
            <div class="group bg-white rounded-2xl p-6 border border-slate-200/90 shadow-sm hover:shadow-md hover:border-slate-300 transition-all">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-black text-slate-400 uppercase tracking-wider">Familles de Pièces</span>
                    <div class="w-11 h-11 rounded-2xl bg-slate-100 text-slate-600 flex items-center justify-center transition-transform group-hover:scale-110">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                </div>
                <div class="flex items-baseline space-x-2">
                    <span class="text-3xl font-black text-slate-900 tracking-tight">{{ $stats['familles_count'] }}</span>
                    <span class="text-xs text-slate-500 font-medium">catégories</span>
                </div>
                <div class="mt-3 flex items-center justify-between pt-3 border-t border-slate-100 text-xs">
                    <span class="text-slate-500 text-[11px] font-medium">Optiques, Pare-chocs, etc.</span>
                    <a href="{{ route('admin.familles.index') }}" class="font-bold text-brand-red hover:underline inline-flex items-center gap-1">
                        Familles &rarr;
                    </a>
                </div>
            </div>

            <!-- Card 4: Pièces Côtés G/D (RL) -->
            <div class="group bg-white rounded-2xl p-6 border border-slate-200/90 shadow-sm hover:shadow-md hover:border-slate-300 transition-all">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-black text-slate-400 uppercase tracking-wider">Gestion Droit / Gauche</span>
                    <div class="w-11 h-11 rounded-2xl bg-slate-100 text-slate-600 flex items-center justify-center transition-transform group-hover:scale-110">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                    </div>
                </div>
                <div class="flex items-baseline space-x-2">
                    <span class="text-3xl font-black text-slate-900 tracking-tight">{{ $stats['pieces_rl_count'] }}</span>
                    <span class="text-xs text-slate-500 font-medium">pièces bilatérales</span>
                </div>
                <div class="mt-3 flex items-center justify-between pt-3 border-t border-slate-100 text-xs">
                    <span class="text-slate-500 text-[11px] font-medium">Stocks dissociés D/G</span>
                    <a href="{{ route('admin.pieces.index', ['position' => 'RL']) }}" class="font-bold text-brand-red hover:underline inline-flex items-center gap-1">
                        Filtrer RL &rarr;
                    </a>
                </div>
            </div>

        </div>
    </div>

    <!-- 2. Actions Rapides Cards Grid -->
    <div>
        <h2 class="text-xs font-black uppercase tracking-wider text-slate-400 mb-4">Actions Rapides & Raccourcis</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

            <!-- Action Card 1: Ajouter une pièce -->
            <a href="{{ route('admin.pieces.create') }}" 
               class="group relative bg-white hover:bg-slate-900 rounded-2xl p-5 border border-slate-200 hover:border-slate-900 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:shadow-xl">
                <div class="flex items-center space-x-3 mb-2">
                    <div class="w-10 h-10 rounded-xl bg-brand-red text-white flex items-center justify-center shadow-md shadow-brand-red/30">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-slate-900 group-hover:text-white transition-colors">Ajouter une Pièce</h3>
                        <span class="text-[10px] text-slate-400 group-hover:text-slate-300">Formulaire avec photo</span>
                    </div>
                </div>
                <p class="text-xs text-slate-500 group-hover:text-slate-300 transition-colors">
                    Enregistrez une nouvelle référence avec compatibilité véhicule et tarification.
                </p>
            </a>

            <!-- Action Card 2: Catalogue PDF sur mesure -->
            <a href="{{ route('admin.catalogue.builder') }}" 
               class="group relative bg-white hover:bg-slate-900 rounded-2xl p-5 border border-slate-200 hover:border-slate-900 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:shadow-xl">
                <div class="flex items-center space-x-3 mb-2">
                    <div class="w-10 h-10 rounded-xl bg-slate-900 group-hover:bg-brand-red text-brand-red group-hover:text-white flex items-center justify-center shadow-md transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-slate-900 group-hover:text-white transition-colors">Catalogue PDF</h3>
                        <span class="text-[10px] text-slate-400 group-hover:text-slate-300">Générateur sur mesure</span>
                    </div>
                </div>
                <p class="text-xs text-slate-500 group-hover:text-slate-300 transition-colors">
                    Générez un catalogue commercial PDF imprimable avec filtres de marques et modèles.
                </p>
            </a>

            <!-- Action Card 3: Nouvelle Famille de pièces -->
            <a href="{{ route('admin.familles.create') }}" 
               class="group relative bg-white hover:bg-slate-900 rounded-2xl p-5 border border-slate-200 hover:border-slate-900 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:shadow-xl">
                <div class="flex items-center space-x-3 mb-2">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 group-hover:bg-slate-700 text-slate-600 group-hover:text-white flex items-center justify-center shadow-md transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-slate-900 group-hover:text-white transition-colors">Nouvelle Famille</h3>
                        <span class="text-[10px] text-slate-400 group-hover:text-slate-300">Catégorie de pièces</span>
                    </div>
                </div>
                <p class="text-xs text-slate-500 group-hover:text-slate-300 transition-colors">
                    Créez une catégorie de pièces carrosserie (Optiques, Pare-chocs, Rétroviseurs...).
                </p>
            </a>

            <!-- Action Card 4: Ajouter Marque / Modèle -->
            <a href="{{ route('admin.vehicules.create') }}" 
               class="group relative bg-white hover:bg-slate-900 rounded-2xl p-5 border border-slate-200 hover:border-slate-900 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:shadow-xl">
                <div class="flex items-center space-x-3 mb-2">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 group-hover:bg-slate-700 text-slate-600 group-hover:text-white flex items-center justify-center shadow-md transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-slate-900 group-hover:text-white transition-colors">Nouveau Modèle</h3>
                        <span class="text-[10px] text-slate-400 group-hover:text-slate-300">Constructeurs & Autos</span>
                    </div>
                </div>
                <p class="text-xs text-slate-500 group-hover:text-slate-300 transition-colors">
                    Rattachez un nouveau modèle de véhicule à une marque (Dacia, Renault, Peugeot, etc.).
                </p>
            </a>

        </div>
    </div>

    <!-- 3. Constructeurs Référencés Cards -->
    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-7 shadow-sm space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <h2 class="text-sm font-black text-slate-900">Constructeurs au Catalogue</h2>
                <p class="text-xs text-slate-400">Répartition des modèles et pièces par constructeur</p>
            </div>
            <a href="{{ route('admin.marques.index') }}" class="text-xs font-bold text-brand-red hover:underline">
                Gérer les marques &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach ($marquesBreakdown as $marque)
                <a href="{{ route('admin.pieces.index', ['marque_id' => $marque->id]) }}" 
                   class="group p-4 rounded-2xl bg-slate-50 hover:bg-slate-900 border border-slate-200/80 hover:border-slate-900 transition-all duration-200 hover:-translate-y-0.5">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center space-x-3">
                            @if ($marque->logo)
                                <img src="{{ asset('storage/' . $marque->logo) }}" alt="{{ $marque->nom }}" class="w-8 h-8 rounded-lg object-contain bg-white p-1 border border-slate-200 flex-shrink-0">
                            @else
                                <div class="w-8 h-8 rounded-lg bg-slate-200 group-hover:bg-brand-red text-slate-700 group-hover:text-white text-xs font-black flex items-center justify-center transition-colors">
                                    {{ substr($marque->nom, 0, 2) }}
                                </div>
                            @endif
                            <span class="text-sm font-black text-slate-900 group-hover:text-white transition-colors uppercase">
                                {{ $marque->nom }}
                            </span>
                        </div>
                        <span class="text-slate-400 group-hover:text-brand-red font-black text-xs">&rarr;</span>
                    </div>

                    <div class="flex items-center gap-2 pt-2 border-t border-slate-200/60 group-hover:border-slate-800 text-[11px]">
                        <span class="px-2 py-0.5 rounded-md bg-white group-hover:bg-slate-800 text-slate-700 group-hover:text-slate-300 font-bold border border-slate-200/80 group-hover:border-slate-700">
                            {{ $marque->vehicules_count }} modèles
                        </span>
                        <span class="px-2 py-0.5 rounded-md bg-slate-100 group-hover:bg-slate-700 text-slate-600 group-hover:text-slate-300 font-bold border border-slate-200/80 group-hover:border-slate-600">
                            {{ $marque->pieces_count }} pièces
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    <!-- 4. Dernières Pièces Ajoutées — Avec Toggle Cartes / Tableau -->
    <div x-data="{ viewMode: 'cards' }" class="space-y-4">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-sm font-black text-slate-900">Dernières Pièces Ajoutées au Catalogue</h2>
                <p class="text-xs text-slate-400">Vue d'ensemble des ajouts récents avec photos et prix</p>
            </div>

            <!-- View Mode Switcher: Cards vs Table -->
            <div class="flex items-center space-x-3">
                <div class="inline-flex items-center p-1 rounded-xl bg-slate-200 text-slate-600 text-xs font-bold">
                    <button type="button" 
                            @click="viewMode = 'cards'"
                            :class="viewMode === 'cards' ? 'bg-white text-slate-900 shadow-sm font-black' : 'hover:text-slate-900'"
                            class="px-3 py-1.5 rounded-lg transition-all flex items-center gap-1.5 cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                        <span>Grille Cartes</span>
                    </button>
                    <button type="button" 
                            @click="viewMode = 'table'"
                            :class="viewMode === 'table' ? 'bg-white text-slate-900 shadow-sm font-black' : 'hover:text-slate-900'"
                            class="px-3 py-1.5 rounded-lg transition-all flex items-center gap-1.5 cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <span>Tableau</span>
                    </button>
                </div>

                <a href="{{ route('admin.pieces.index') }}" class="text-xs font-black text-brand-red hover:underline">
                    Voir toutes les pièces &rarr;
                </a>
            </div>
        </div>

        <!-- Cards View (Default) -->
        <div x-show="viewMode === 'cards'" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
            @forelse ($recentPieces as $piece)
                <div class="group bg-white rounded-2xl border border-slate-200/90 shadow-sm hover:shadow-xl hover:border-slate-300 transition-all duration-200 flex flex-col overflow-hidden">
                    
                    <!-- Card Top Image & Badges -->
                    <div class="relative bg-slate-950 aspect-[4/3] overflow-hidden flex items-center justify-center">
                        <img src="{{ $piece->image_url }}" 
                             alt="{{ $piece->designation }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        
                        <!-- Status Badge -->
                        <div class="absolute top-2.5 right-2.5">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-black {{ $piece->actif ? 'bg-white/90 text-slate-800' : 'bg-slate-800/90 text-slate-400' }}">
                                {{ $piece->actif ? 'Actif' : 'Inactif' }}
                            </span>
                        </div>

                        <!-- Vehicle Pill Overlay -->
                        <div class="absolute bottom-2.5 left-2.5 right-2.5">
                            <span class="inline-block px-2.5 py-1 rounded-lg text-[10px] font-black bg-slate-900/90 backdrop-blur-md text-white border border-slate-700/80 truncate max-w-full">
                                {{ $piece->vehicule->marque->nom ?? '' }} {{ $piece->vehicule->nom ?? '' }}
                            </span>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-4 flex-1 flex flex-col justify-between space-y-3">
                        <div>
                            <div class="flex items-center justify-between text-[10px] text-slate-400 font-bold mb-1">
                                <span>{{ $piece->famille->nom }}</span>
                                <span>Année {{ $piece->annee_debut }}+</span>
                            </div>

                            <h3 class="text-xs font-bold text-slate-900 group-hover:text-brand-red transition-colors line-clamp-2 leading-relaxed" title="{{ $piece->designation }}">
                                {{ $piece->designation }}
                            </h3>

                            @if ($piece->variante)
                                <span class="text-[10px] text-slate-500 font-medium block mt-1 truncate">
                                    {{ $piece->variante }}
                                </span>
                            @endif
                        </div>

                        <!-- Card Footer: Position + Price + Action -->
                        <div class="pt-3 border-t border-slate-100 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="px-2 py-0.5 rounded text-[10px] font-black bg-slate-100 text-slate-800">
                                    {{ $piece->position }}
                                </span>
                                <span class="text-sm font-black text-slate-900">
                                    {{ $piece->formatted_prix }}
                                </span>
                            </div>

                            <div class="flex items-center gap-2 pt-1">
                                <a href="{{ route('admin.pieces.edit', $piece) }}" 
                                   class="flex-1 py-1.5 rounded-xl bg-slate-100 hover:bg-brand-red text-slate-800 hover:text-white text-[11px] font-bold text-center transition-colors">
                                    Modifier
                                </a>
                                <a href="{{ route('catalog.piece', ['id' => $piece->id, 'slug' => $piece->slug]) }}" 
                                   target="_blank" 
                                   class="p-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 hover:text-slate-900 transition-colors"
                                   title="Aperçu public">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>

                    </div>

                </div>
            @empty
                <div class="col-span-full bg-white rounded-2xl p-8 text-center text-slate-400 border border-slate-200">
                    Aucune pièce au catalogue pour le moment.
                </div>
            @endforelse
        </div>

        <!-- Table View (Optional alternative via toggle) -->
        <div x-show="viewMode === 'table'" class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
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
                                    <img src="{{ $piece->image_url }}" alt="" class="w-10 h-10 rounded-lg object-cover bg-slate-950 flex-shrink-0 border border-slate-200">
                                    <span class="line-clamp-1">{{ $piece->designation }}</span>
                                </td>
                                <td class="p-4">
                                    <span class="font-bold text-slate-800">{{ $piece->vehicule->marque->nom ?? '' }} {{ $piece->vehicule->nom ?? '' }}</span>
                                </td>
                                <td class="p-4 text-slate-600">{{ $piece->famille->nom }}</td>
                                <td class="p-4 font-bold text-slate-800">{{ $piece->position }}</td>
                                <td class="p-4 font-black text-slate-900">{{ $piece->formatted_prix }}</td>
                                <td class="p-4">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $piece->actif ? 'bg-slate-100 text-slate-700' : 'bg-slate-200 text-slate-500' }}">
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

</div>
@endsection
