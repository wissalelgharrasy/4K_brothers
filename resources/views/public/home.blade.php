@extends('public.layouts.public')

@section('title', '4K BROTHERS — Pièces Auto Dacia, Renault & Carrosserie au Maroc')

@section('content')
    <!-- Hero Banner with Automotive Vibe -->
    <section class="relative bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 text-white overflow-hidden py-16 sm:py-24 border-b border-slate-800">
        <!-- Background Glow Accent -->
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-brand-red/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-brand-red/10 rounded-full blur-3xl pointer-events-none"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-3xl">
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-brand-red/10 border border-brand-red/30 text-brand-500 text-xs font-bold uppercase tracking-wider mb-6">
                    <span class="w-2 h-2 rounded-full bg-brand-red animate-pulse"></span>
                    <span>4K BROTHERS — Votre Partenaire Auto</span>
                </div>
                
                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black tracking-tight leading-tight mb-6">
                    Pièces de <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-red via-rose-500 to-red-400">Carrosserie & Éclairage</span> Automobiles.
                </h1>
                
                <p class="text-base sm:text-lg text-slate-300 leading-relaxed mb-8">
                    Optiques, pare-chocs, calandres, feux arrière, grilles et spoilers pour <strong>Dacia, Renault</strong> et véhicules multimarques. Trouvez votre référence et commandez instantanément sur WhatsApp.
                </p>

                <!-- Value Props Badges -->
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 pt-4 border-t border-slate-800/80">
                    <div class="flex items-center space-x-2.5">
                        <div class="w-8 h-8 rounded-lg bg-slate-800 border border-slate-700 flex items-center justify-center text-brand-red">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <span class="text-xs font-semibold text-slate-300">Catalogue Certifié</span>
                    </div>

                    <div class="flex items-center space-x-2.5">
                        <div class="w-8 h-8 rounded-lg bg-slate-800 border border-slate-700 flex items-center justify-center text-emerald-400">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.599 2.679-.702c.971.554 1.71.851 2.781.851 3.18 0 5.766-2.587 5.767-5.766.001-3.182-2.585-5.768-5.767-5.768zm3.349 8.167c-.145.409-.844.757-1.157.777-.306.019-.699.034-2.021-.513-1.688-.698-2.771-2.416-2.856-2.528-.084-.112-.676-.9-.676-1.716 0-.817.429-1.218.582-1.385.153-.166.333-.208.444-.208.111 0 .222.002.319.006.102.005.239-.039.373.284.145.351.498 1.216.542 1.305.044.089.073.193.014.31-.059.117-.089.19-.176.293-.087.103-.183.23-.262.309-.089.088-.182.184-.078.362.104.178.463.764 1.002 1.243.693.616 1.278.808 1.456.897.178.089.282.074.386-.045.105-.118.445-.518.563-.696.119-.178.238-.148.4-.089.163.059 1.036.488 1.214.577.178.089.297.133.341.208.044.074.044.43-.101.839z"/>
                            </svg>
                        </div>
                        <span class="text-xs font-semibold text-slate-300">Commande WhatsApp Directe</span>
                    </div>

                    <div class="flex items-center space-x-2.5 col-span-2 sm:col-span-1">
                        <div class="w-8 h-8 rounded-lg bg-slate-800 border border-slate-700 flex items-center justify-center text-amber-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="text-xs font-semibold text-slate-300">Prix Transparents en {{ config('shop.currency_label', 'DH') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Brands Selection Section -->
    <section id="marques" class="py-12 sm:py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8">
            <div>
                <span class="text-xs font-extrabold uppercase tracking-wider text-brand-red">Parcourir par Constructeur</span>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 mt-1">Choisissez une Marque</h2>
            </div>
            <p class="text-sm text-slate-500 mt-2 sm:mt-0">Sélectionnez le constructeur de votre véhicule pour voir les modèles</p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 sm:gap-6">
            @foreach ($marques as $marque)
                <a href="{{ route('catalog.marque', $marque->slug) }}" 
                   class="group relative bg-white rounded-2xl p-6 border border-slate-200 hover:border-brand-red/50 shadow-sm hover:shadow-xl transition-all flex flex-col items-center text-center overflow-hidden">
                    
                    <!-- Decorative corner accent -->
                    <div class="absolute -right-8 -top-8 w-16 h-16 bg-slate-50 group-hover:bg-brand-red/10 rounded-full transition-colors"></div>

                    <!-- Brand Icon Placeholder / Logo -->
                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-slate-100 group-hover:bg-brand-red/5 border border-slate-200 flex items-center justify-center mb-4 transition-transform group-hover:scale-105">
                        <span class="text-2xl sm:text-3xl font-black tracking-wider text-slate-800 group-hover:text-brand-red transition-colors">
                            {{ strtoupper(substr($marque->nom, 0, 2)) }}
                        </span>
                    </div>

                    <h3 class="text-lg font-black text-slate-900 group-hover:text-brand-red transition-colors">
                        {{ $marque->nom }}
                    </h3>

                    <div class="mt-2 flex items-center space-x-2 text-xs text-slate-500 font-medium">
                        <span>{{ $marque->vehicules_count }} modèles</span>
                        <span>&bull;</span>
                        <span class="font-bold text-slate-700">{{ $marque->pieces_count }} pièces</span>
                    </div>

                    <span class="mt-4 inline-flex items-center text-xs font-bold text-brand-red group-hover:translate-x-1 transition-transform">
                        Voir les modèles
                        <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                        </svg>
                    </span>
                </a>
            @endforeach
        </div>
    </section>

    <!-- Popular Families Filter Chips -->
    @if ($familles->isNotEmpty())
        <section class="py-8 bg-slate-100/70 border-y border-slate-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-extrabold text-slate-700 uppercase tracking-wider">
                        Recherche par Famille de Pièce
                    </h3>
                </div>
                <div class="flex flex-wrap gap-2">
                    @foreach ($familles as $famille)
                        <a href="{{ route('catalog.search', ['q' => $famille->nom]) }}" 
                           class="inline-flex items-center px-4 py-2 rounded-xl bg-white border border-slate-200 hover:border-brand-red text-slate-700 hover:text-brand-red text-xs font-bold shadow-sm transition-all hover:-translate-y-0.5">
                            <span>{{ $famille->nom }}</span>
                            <span class="ml-2 px-1.5 py-0.5 rounded-md bg-slate-100 text-slate-600 text-[10px]">
                                {{ $famille->pieces_count }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Featured Pieces Showcase -->
    @if ($featuredPieces->isNotEmpty())
        <section class="py-12 sm:py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8">
                <div>
                    <span class="text-xs font-extrabold uppercase tracking-wider text-brand-red">Nouveautés & Références Clés</span>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 mt-1">Derniers Articles en Ligne</h2>
                </div>
                <a href="{{ route('catalog.marque', 'dacia') }}" class="text-sm font-bold text-brand-red hover:underline mt-2 sm:mt-0 flex items-center">
                    Parcourir tout le catalogue
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($featuredPieces as $piece)
                    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-xl hover:border-brand-red/40 transition-all flex flex-col group">
                        
                        <!-- Image Container -->
                        <a href="{{ route('catalog.piece', ['id' => $piece->id, 'slug' => $piece->slug]) }}" class="block relative aspect-4/3 bg-slate-950 overflow-hidden">
                            <img src="{{ $piece->image_url }}" 
                                 alt="{{ $piece->designation }}" 
                                 loading="lazy"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            
                            <!-- Position Badge -->
                            <span class="absolute top-3 left-3 px-2.5 py-1 rounded-md text-[11px] font-black tracking-wide bg-slate-900/90 text-white backdrop-blur-sm border border-slate-700">
                                {{ $piece->position }}
                            </span>

                            <!-- Stock / Dispo Badge -->
                            <span class="absolute top-3 right-3 px-2.5 py-1 rounded-md text-[11px] font-bold {{ $piece->isDisponible() ? 'bg-emerald-500 text-white' : 'bg-rose-500 text-white' }}">
                                {{ $piece->isDisponible() ? 'Disponible' : 'Rupture' }}
                            </span>
                        </a>

                        <!-- Body Content -->
                        <div class="p-5 flex-1 flex flex-col justify-between">
                            <div>
                                <!-- Vehicle & Family Tag -->
                                <div class="flex items-center space-x-2 text-xs font-semibold text-slate-500 mb-1.5">
                                    <span class="text-brand-red">{{ $piece->vehicule?->marque?->nom ?? 'Générique' }}</span>
                                    <span>&bull;</span>
                                    <span>{{ $piece->vehicule?->nom ?? 'Universel' }}</span>
                                </div>

                                <h3 class="font-bold text-slate-900 text-sm line-clamp-2 group-hover:text-brand-red transition-colors mb-2">
                                    <a href="{{ route('catalog.piece', ['id' => $piece->id, 'slug' => $piece->slug]) }}">
                                        {{ $piece->designation }}
                                    </a>
                                </h3>

                                @if ($piece->isDoubleCote())
                                    <div class="text-[11px] text-slate-500 mb-3 flex items-center space-x-2">
                                        <span>Côtés :</span>
                                        <span class="{{ $piece->dispo_droite ? 'text-emerald-700 font-bold' : 'text-slate-400 line-through' }}">Droit</span>
                                        <span>/</span>
                                        <span class="{{ $piece->dispo_gauche ? 'text-emerald-700 font-bold' : 'text-slate-400 line-through' }}">Gauche</span>
                                    </div>
                                @endif
                            </div>

                            <div class="pt-3 border-t border-slate-100 flex items-center justify-between mt-auto">
                                <div>
                                    <span class="text-[11px] text-slate-400 block font-medium">Prix TTC</span>
                                    <span class="text-base font-black text-slate-900">{{ $piece->formatted_prix }}</span>
                                </div>

                                <a href="{{ route('catalog.piece', ['id' => $piece->id, 'slug' => $piece->slug]) }}" 
                                   class="px-3.5 py-1.5 rounded-xl bg-slate-900 group-hover:bg-brand-red text-white text-xs font-bold transition-colors">
                                    Détails &rarr;
                                </a>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        </section>
    @endif
@endsection
