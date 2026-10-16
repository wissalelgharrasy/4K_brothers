@extends('public.layouts.public')

@section('title', 'Pièces ' . $marque->nom . ' ' . $vehicule->nom . ' — 4K BROTHERS')
@section('meta_description', 'Catalogue des pièces auto pour ' . $marque->nom . ' ' . $vehicule->nom . ' : optiques, pare-chocs, calandres. Filtrez par année et côté. Commande WhatsApp.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Breadcrumb -->
    <nav class="flex items-center space-x-2 text-xs font-semibold text-slate-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-brand-red transition-colors">Accueil</a>
        <span>/</span>
        <a href="{{ route('catalog.marque', $marque->slug) }}" class="hover:text-brand-red transition-colors">{{ $marque->nom }}</a>
        <span>/</span>
        <span class="text-slate-800">{{ $vehicule->nom }}</span>
    </nav>

    <!-- Vehicle Hero Card -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 rounded-3xl p-6 sm:p-8 text-white mb-8 border border-slate-700/80 shadow-md">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center space-x-2">
                    <span class="px-2.5 py-0.5 rounded-md bg-brand-red text-white text-[10px] font-black uppercase tracking-wider">
                        {{ $marque->nom }}
                    </span>
                    <span class="text-xs text-slate-400 font-bold">Catalogue Pièces</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black mt-2">
                    {{ $marque->nom }} {{ $vehicule->nom }}
                </h1>
                <p class="text-xs sm:text-sm text-slate-300 mt-1">
                    Pièces de carrosserie, phares, feux arrière, calandres et pare-chocs garantis compatibles.
                </p>
            </div>
            <div class="text-left sm:text-right">
                <span class="text-2xl font-black text-brand-red">{{ $pieces->total() }}</span>
                <span class="text-xs font-bold text-slate-400 block">référence(s) trouvée(s)</span>
            </div>
        </div>
    </div>

    <!-- Layout with Filters & Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Sidebar Filters -->
        <aside class="lg:col-span-1">
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm sticky top-28">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                    <h2 class="text-base font-black text-slate-900 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        Filtres
                    </h2>

                    @if (request()->hasAny(['famille_id', 'annee', 'position', 'disponible', 'search', 'sort']))
                        <a href="{{ route('catalog.vehicule', ['marque' => $marque->slug, 'vehicule' => $vehicule->slug]) }}" 
                           class="text-xs font-bold text-rose-600 hover:underline">
                            Réinitialiser
                        </a>
                    @endif
                </div>

                <form method="GET" action="{{ route('catalog.vehicule', ['marque' => $marque->slug, 'vehicule' => $vehicule->slug]) }}" class="space-y-4">
                    
                    <!-- Search input inside vehicle -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Mot-clé</label>
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Ex. Optique, Grille..."
                               class="w-full text-xs rounded-xl border-slate-300 focus:border-brand-red focus:ring-brand-red/30">
                    </div>

                    <!-- Filter by Family -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Famille</label>
                        <select name="famille_id" class="w-full text-xs rounded-xl border-slate-300 focus:border-brand-red focus:ring-brand-red/30">
                            <option value="">Toutes les familles</option>
                            @foreach ($familles as $fam)
                                <option value="{{ $fam->id }}" {{ request('famille_id') == $fam->id ? 'selected' : '' }}>
                                    {{ $fam->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter by Year -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Année de début</label>
                        <select name="annee" class="w-full text-xs rounded-xl border-slate-300 focus:border-brand-red focus:ring-brand-red/30">
                            <option value="">Toutes les années</option>
                            @foreach ($annees as $yr)
                                <option value="{{ $yr }}" {{ request('annee') == $yr ? 'selected' : '' }}>
                                    {{ $yr }} et +
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter by Position -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Position</label>
                        <select name="position" class="w-full text-xs rounded-xl border-slate-300 focus:border-brand-red focus:ring-brand-red/30">
                            <option value="">Toutes positions</option>
                            <option value="RL" {{ request('position') == 'RL' ? 'selected' : '' }}>Droite / Gauche (R/L)</option>
                            <option value="AV" {{ request('position') == 'AV' ? 'selected' : '' }}>Avant (AV)</option>
                            <option value="AR" {{ request('position') == 'AR' ? 'selected' : '' }}>Arrière (AR)</option>
                            <option value="CTRL" {{ request('position') == 'CTRL' ? 'selected' : '' }}>Centrale (CTRL)</option>
                        </select>
                    </div>

                    <!-- Filter by Availability -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Disponibilité</label>
                        <select name="disponible" class="w-full text-xs rounded-xl border-slate-300 focus:border-brand-red focus:ring-brand-red/30">
                            <option value="">Tous les statuts</option>
                            <option value="1" {{ request('disponible') === '1' ? 'selected' : '' }}>En stock / Disponible</option>
                            <option value="0" {{ request('disponible') === '0' ? 'selected' : '' }}>Rupture de stock</option>
                        </select>
                    </div>

                    <!-- Sort -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Trier par</label>
                        <select name="sort" class="w-full text-xs rounded-xl border-slate-300 focus:border-brand-red focus:ring-brand-red/30">
                            <option value="default" {{ request('sort') == 'default' ? 'selected' : '' }}>Nom (A à Z)</option>
                            <option value="prix_asc" {{ request('sort') == 'prix_asc' ? 'selected' : '' }}>Prix croissant</option>
                            <option value="prix_desc" {{ request('sort') == 'prix_desc' ? 'selected' : '' }}>Prix décroissant</option>
                            <option value="nom_desc" {{ request('sort') == 'nom_desc' ? 'selected' : '' }}>Nom (Z à A)</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full py-2.5 rounded-xl bg-slate-900 hover:bg-brand-red text-white text-xs font-black tracking-wider uppercase transition-colors shadow-sm">
                        Appliquer les filtres
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Products Column -->
        <section class="lg:col-span-3">
            
            @if ($pieces->isEmpty())
                <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
                    <svg class="w-12 h-12 mx-auto text-slate-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="text-lg font-black text-slate-800">Aucune pièce ne correspond à vos critères</h3>
                    <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                        Essayez de modifier vos filtres ou de réinitialiser la recherche pour voir toutes les pièces disponibles pour ce modèle.
                    </p>
                    <a href="{{ route('catalog.vehicule', ['marque' => $marque->slug, 'vehicule' => $vehicule->slug]) }}" 
                       class="inline-block mt-4 px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold">
                        Réinitialiser tous les filtres
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach ($pieces as $piece)
                        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-xl hover:border-brand-red/40 transition-all flex flex-col group">
                            
                            <!-- Image Container -->
                            <a href="{{ route('catalog.piece', ['id' => $piece->id, 'slug' => $piece->slug]) }}" class="block relative aspect-4/3 bg-slate-950 overflow-hidden">
                                <img src="{{ $piece->image_url }}" 
                                     alt="{{ $piece->designation }}" 
                                     loading="lazy"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                
                                <!-- Position Badge -->
                                <span class="absolute top-3 left-3 px-2 py-0.5 rounded-md text-[10px] font-black tracking-wide bg-slate-900/90 text-white backdrop-blur-sm border border-slate-700">
                                    {{ $piece->position }}
                                </span>

                                <!-- Global Availability Badge -->
                                <span class="absolute top-3 right-3 px-2 py-0.5 rounded-md text-[10px] font-bold {{ $piece->isDisponible() ? 'bg-emerald-500 text-white' : 'bg-rose-500 text-white' }}">
                                    {{ $piece->isDisponible() ? 'En stock' : 'Rupture' }}
                                </span>
                            </a>

                            <!-- Card Body -->
                            <div class="p-5 flex-1 flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center justify-between text-[11px] font-bold text-slate-400 mb-1">
                                        <span class="text-brand-red">{{ $piece->famille->nom }}</span>
                                        <span>Année {{ $piece->annee_debut }}+</span>
                                    </div>

                                    <h3 class="font-bold text-slate-900 text-sm line-clamp-2 group-hover:text-brand-red transition-colors mb-2">
                                        <a href="{{ route('catalog.piece', ['id' => $piece->id, 'slug' => $piece->slug]) }}">
                                            {{ $piece->designation }}
                                        </a>
                                    </h3>

                                    @if ($piece->variante)
                                        <p class="text-xs text-slate-500 mb-2 font-medium">
                                            Variante : <span class="text-slate-700">{{ $piece->variante }}</span>
                                        </p>
                                    @endif

                                    <!-- RL Sides Availability indicator -->
                                    @if ($piece->isDoubleCote())
                                        <div class="text-[11px] bg-slate-50 border border-slate-100 rounded-lg p-2 mb-3 flex items-center justify-between">
                                            <span class="text-slate-500 font-semibold">Côtés :</span>
                                            <div class="flex items-center space-x-2">
                                                <span class="inline-flex items-center {{ $piece->dispo_droite ? 'text-emerald-700 font-bold' : 'text-slate-400 line-through' }}">
                                                    Droit {!! $piece->dispo_droite ? '&#10003;' : '&times;' !!}
                                                </span>
                                                <span class="text-slate-300">|</span>
                                                <span class="inline-flex items-center {{ $piece->dispo_gauche ? 'text-emerald-700 font-bold' : 'text-slate-400 line-through' }}">
                                                    Gauche {!! $piece->dispo_gauche ? '&#10003;' : '&times;' !!}
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="pt-3 border-t border-slate-100 flex items-center justify-between mt-auto">
                                    <div>
                                        <span class="text-[10px] text-slate-400 block font-medium">Prix unitaire TTC</span>
                                        <span class="text-base font-black text-slate-900">{{ $piece->formatted_prix }}</span>
                                    </div>

                                    <a href="{{ route('catalog.piece', ['id' => $piece->id, 'slug' => $piece->slug]) }}" 
                                       class="px-3.5 py-1.5 rounded-xl bg-slate-900 group-hover:bg-brand-red text-white text-xs font-bold transition-colors">
                                        Voir &rarr;
                                    </a>
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $pieces->links() }}
                </div>
            @endif

        </section>

    </div>

</div>
@endsection
