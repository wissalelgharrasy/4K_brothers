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

            <!-- Key Stats -->
            <div class="mt-6 pt-6 border-t border-slate-800/80 flex flex-wrap gap-4 sm:gap-8">
                <div>
                    <div class="text-2xl sm:text-3xl font-black text-white">{{ $stats['total_marques'] }}</div>
                    <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Marques actives</div>
                </div>
                <div class="w-px h-10 bg-slate-800 hidden sm:block"></div>
                <div>
                    <div class="text-2xl sm:text-3xl font-black text-brand-red">{{ $stats['total_vehicules'] }}</div>
                    <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Modèles référencés</div>
                </div>
                <div class="w-px h-10 bg-slate-800 hidden sm:block"></div>
                <div>
                    <div class="text-2xl sm:text-3xl font-black text-emerald-400">{{ $stats['total_pieces'] }}</div>
                    <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Pièces certifiées</div>
                </div>
            </div>
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

    <!-- Assistance Box -->
    <div class="mt-12 bg-white rounded-2xl p-6 sm:p-8 border border-slate-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-6">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.599 2.679-.702c.971.554 1.71.851 2.781.851 3.18 0 5.766-2.587 5.767-5.766.001-3.182-2.585-5.768-5.767-5.768zm3.349 8.167c-.145.409-.844.757-1.157.777-.306.019-.699.034-2.021-.513-1.688-.698-2.771-2.416-2.856-2.528-.084-.112-.676-.9-.676-1.716 0-.817.429-1.218.582-1.385.153-.166.333-.208.444-.208.111 0 .222.002.319.006.102.005.239-.039.373.284.145.351.498 1.216.542 1.305.044.089.073.193.014.31-.059.117-.089.19-.176.293-.087.103-.183.23-.262.309-.089.088-.182.184-.078.362.104.178.463.764 1.002 1.243.693.616 1.278.808 1.456.897.178.089.282.074.386-.045.105-.118.445-.518.563-.696.119-.178.238-.148.4-.089.163.059 1.036.488 1.214.577.178.089.297.133.341.208.044.074.044.43-.101.839z"/>
                </svg>
            </div>
            <div>
                <h4 class="text-base font-black text-slate-900">Votre marque ou modèle ne figure pas dans la liste ?</h4>
                <p class="text-xs text-slate-500 mt-0.5">Notre équipe commerciale peut vous trouver la référence exacte sous 24-48h.</p>
            </div>
        </div>

        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', config('shop.whatsapp_number', '212600000000')) }}?text={{ rawurlencode('Bonjour 4K BROTHERS, je recherche des pièces pour une marque non listée.') }}" 
           target="_blank" 
           rel="noopener"
           class="w-full sm:w-auto px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-black shadow-lg shadow-emerald-900/20 transition-all flex items-center justify-center">
            Demande spécifique sur WhatsApp &rarr;
        </a>
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
