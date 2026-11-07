@extends('public.layouts.public')

@section('title', 'Toutes les Marques & Modèles Automobiles — 4K BROTHERS')
@section('meta_description', 'Découvrez toutes les marques automobiles disponibles chez 4K BROTHERS : Dacia, Renault, Peugeot, Fiat et plus. Pièces de carrosserie, optiques et pare-chocs.')

@section('content')
<div class="py-8 sm:py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <!-- Breadcrumb -->
    <nav class="flex items-center text-xs font-semibold text-slate-500 mb-6 space-x-2">
        <a href="{{ route('home') }}" class="hover:text-brand-red transition-colors">Accueil</a>
        <span>/</span>
        <span class="text-slate-900 font-bold">Constructeurs Automobiles</span>
    </nav>

    <!-- Hero Header -->
    <div class="relative bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 rounded-3xl p-6 sm:p-10 border border-slate-800 shadow-2xl overflow-hidden mb-10">
        <!-- Background Glow Accents -->
        <div class="absolute -right-20 -top-20 w-80 h-80 bg-brand-red/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-slate-700/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 max-w-3xl">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold bg-brand-red/20 text-brand-red border border-brand-red/30 mb-4 uppercase tracking-wider">
                Catalogue Officiel 4K BROTHERS
            </span>
            <h1 class="text-3xl sm:text-5xl font-black text-white tracking-tight leading-tight">
                Constructeurs & Marques Automobiles
            </h1>
            <p class="mt-3 text-sm sm:text-base text-slate-300 leading-relaxed">
                Sélectionnez votre constructeur automobile pour parcourir l’ensemble des modèles disponibles, comparer les optiques, pare-chocs, calandres et commander directement sur WhatsApp.
            </p>
        </div>
    </div>

    <!-- Filter & Search Bar for Brands -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-xl sm:text-2xl font-black text-slate-900">
                Toutes nos marques (<span id="marques-count">{{ count($marques) }}</span>)
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                Cliquez sur une marque pour découvrir les véhicules et pièces associées
            </p>
        </div>

        <div class="w-full sm:w-80 relative">
            <input type="text" 
                   id="marque-filter-input" 
                   placeholder="Filtrer une marque (Dacia, Renault...)" 
                   autocomplete="off"
                   class="w-full bg-white text-slate-900 placeholder-slate-400 text-xs sm:text-sm rounded-xl pl-10 pr-4 py-2.5 border border-slate-300 focus:outline-none focus:border-brand-red focus:ring-2 focus:ring-brand-red/20 shadow-sm transition-all">
            <div class="absolute left-3 top-3 text-slate-400 pointer-events-none">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Brands Grid -->
    <div id="marques-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse ($marques as $marque)
            <div class="marque-card group bg-white rounded-2xl p-6 border border-slate-200 hover:border-brand-red/50 shadow-sm hover:shadow-xl transition-all flex flex-col justify-between"
                 data-nom="{{ strtolower($marque->nom) }}">
                
                <div>
                    <!-- Logo & Badge Header -->
                    <div class="flex items-center justify-between mb-5">
                        <div class="w-16 h-16 rounded-2xl bg-slate-50 group-hover:bg-brand-red/5 border border-slate-200 p-2 flex items-center justify-center transition-all group-hover:scale-105">
                            @if ($marque->logo_url)
                                <img src="{{ $marque->logo_url }}" alt="{{ $marque->nom }}" class="w-full h-full object-contain">
                            @else
                                <span class="text-xl font-black text-slate-800 group-hover:text-brand-red transition-colors">
                                    {{ strtoupper(substr($marque->nom, 0, 2)) }}
                                </span>
                            @endif
                        </div>

                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 group-hover:bg-brand-red/10 group-hover:text-brand-red text-slate-700 transition-colors">
                            {{ $marque->vehicules_count }} modèles
                        </span>
                    </div>

                    <!-- Marque Title -->
                    <h3 class="text-lg font-black text-slate-900 group-hover:text-brand-red transition-colors">
                        {{ $marque->nom }}
                    </h3>

                    <!-- Models Preview -->
                    @if ($marque->vehicules->isNotEmpty())
                        <div class="mt-3">
                            <span class="text-[10px] uppercase font-bold text-slate-400 block mb-1.5">Modèles populaires :</span>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach ($marque->vehicules->take(4) as $vehicule)
                                    <span class="inline-block px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 text-[11px] font-medium">
                                        {{ $vehicule->nom }}
                                    </span>
                                @endforeach
                                @if ($marque->vehicules->count() > 4)
                                    <span class="inline-block px-2 py-0.5 rounded-md bg-slate-50 text-slate-400 text-[11px] font-medium">
                                        +{{ $marque->vehicules->count() - 4 }} autres
                                    </span>
                                @endif
                            </div>
                        </div>
                    @else
                        <p class="text-xs text-slate-400 mt-2 italic">Modèles en cours d'ajout.</p>
                    @endif
                </div>

                <!-- Footer with pieces count & action -->
                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-600">
                        <strong class="font-black text-slate-900">{{ $marque->pieces_count }}</strong> pièce(s)
                    </span>

                    <a href="{{ route('catalog.marque', $marque->slug) }}" 
                       class="inline-flex items-center text-xs font-black text-brand-red group-hover:translate-x-1 transition-transform">
                        Voir les pièces
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>

            </div>
        @empty
            <div class="col-span-full bg-white rounded-2xl p-12 text-center text-slate-400 border border-slate-200">
                <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <p class="text-base font-bold text-slate-600">Aucune marque disponible pour le moment.</p>
            </div>
        @endforelse
    </div>

    <!-- Empty Search State -->
    <div id="no-search-results" class="hidden bg-white rounded-2xl p-12 text-center border border-slate-200 mt-6">
        <p class="text-sm font-bold text-slate-700">Aucune marque ne correspond à votre recherche.</p>
        <button type="button" onclick="document.getElementById('marque-filter-input').value = ''; filterMarques();" class="mt-2 text-xs font-bold text-brand-red hover:underline cursor-pointer">
            Réinitialiser le filtre
        </button>
    </div>

</div>

@push('scripts')
<script>
    const filterInput = document.getElementById('marque-filter-input');
    const cards = document.querySelectorAll('.marque-card');
    const noResults = document.getElementById('no-search-results');
    const countSpan = document.getElementById('marques-count');

    function filterMarques() {
        const query = filterInput.value.trim().toLowerCase();
        let visible = 0;

        cards.forEach(card => {
            const nom = card.getAttribute('data-nom') || '';
            if (nom.includes(query)) {
                card.classList.remove('hidden');
                visible++;
            } else {
                card.classList.add('hidden');
            }
        });

        countSpan.textContent = visible;
        if (visible === 0) {
            noResults.classList.remove('hidden');
        } else {
            noResults.classList.add('hidden');
        }
    }

    if (filterInput) {
        filterInput.addEventListener('input', filterMarques);
    }
</script>
@endpush
@endsection
