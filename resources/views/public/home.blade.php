@extends('public.layouts.public')

@section('title', '4K BROTHERS — Pièces Auto Dacia, Renault & Carrosserie au Maroc')

@section('content')
    <!-- Hero Banner — Full bleed, photo background right, curved bottom -->
    <section class="relative bg-slate-950 text-white" style="padding-bottom: 50px;">

        {{-- Full bleed layout: image on right bleeds into dark bg --}}
        <div class="absolute inset-0 flex">
            {{-- Left dark zone (45%) --}}
            <div class="w-full lg:w-[45%] bg-slate-950 flex-shrink-0"></div>
            {{-- Right image zone (55%) --}}
            <div class="hidden lg:block flex-1 relative">
                <img src="{{ asset('images/hero-kardian-optiques.webp') }}"
                     alt="Pièces automobiles 4K BROTHERS"
                     class="w-full h-full object-cover object-center"
                     loading="eager">
                {{-- Gradient fade left so image blends into dark bg --}}
                <div class="absolute inset-y-0 left-0 w-48"
                     style="background: linear-gradient(to right, #020617 0%, transparent 100%);"></div>
            </div>
        </div>

        {{-- Mobile background image --}}
        <div class="absolute inset-0 lg:hidden">
            <img src="{{ asset('images/hero-kardian-optiques.webp') }}"
                 alt="" class="w-full h-full object-cover object-right opacity-20" loading="eager">
        </div>

        {{-- Content --}}
        <div class="relative z-10 max-w-7xl mx-auto px-6 sm:px-10 lg:px-16 py-16 lg:py-24">
            <div class="max-w-xl">

                {{-- Badge pill --}}
                <div class="mb-5">
                    <span class="inline-flex items-center px-4 py-1.5 rounded-full border border-slate-500 bg-slate-800/80 text-slate-200 text-xs font-black uppercase tracking-widest">
                        4K BROTHERS — VOTRE PARTENAIRE AUTO
                    </span>
                </div>

                {{-- Heading --}}
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black leading-[1.12] tracking-tight mb-4">
                    Importateur et distributeur<br>
                    <span class="text-brand-red">de pièces automobiles,</span><br>
                    carrosseries et accessoires.
                </h1>

                {{-- Description --}}
                <p class="text-slate-300 text-xs sm:text-sm leading-relaxed mb-5 max-w-md">
                    Optiques, pare-chocs, calandres, feux arrière pour <strong class="text-white">Dacia, Renault</strong> et toutes marques. Commandez instantanément sur WhatsApp.
                </p>

                {{-- CTA Button — style "pill with arrow" like reference --}}
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('catalog.marques') }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-brand-red hover:bg-red-700 text-white font-black text-xs shadow-lg shadow-brand-red/40 transition-all hover:-translate-y-0.5">
                        Parcourir le Catalogue &rarr;
                    </a>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', config('shop.whatsapp_number', '212600000000')) }}"
                       target="_blank" rel="noopener"
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full border border-slate-600 hover:border-emerald-500 text-slate-200 hover:text-emerald-400 font-bold text-xs transition-all">
                        <svg class="w-4 h-4 fill-current text-emerald-400 shrink-0" viewBox="0 0 24 24">
                            <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.599 2.679-.702c.971.554 1.71.851 2.781.851 3.18 0 5.766-2.587 5.767-5.766.001-3.182-2.585-5.768-5.767-5.768zm3.349 8.167c-.145.409-.844.757-1.157.777-.306.019-.699.034-2.021-.513-1.688-.698-2.771-2.416-2.856-2.528-.084-.112-.676-.9-.676-1.716 0-.817.429-1.218.582-1.385.153-.166.333-.208.444-.208.111 0 .222.002.319.006.102.005.239-.039.373.284.145.351.498 1.216.542 1.305.044.089.073.193.014.31-.059.117-.089.19-.176.293-.087.103-.183.23-.262.309-.089.088-.182.184-.078.362.104.178.463.764 1.002 1.243.693.616 1.278.808 1.456.897.178.089.282.074.386-.045.105-.118.445-.518.563-.696.119-.178.238-.148.4-.089.163.059 1.036.488 1.214.577.178.089.297.133.341.208.044.074.044.43-.101.839z"/>
                        </svg>
                        Demande WhatsApp
                    </a>
                </div>

            </div>
        </div>



        {{-- Bottom wave curve (la "bulle" du bas) --}}
        <div class="absolute bottom-0 left-0 right-0 z-20" style="height: 62px; margin-bottom: -2px;">
            <svg viewBox="0 0 1440 60" preserveAspectRatio="none"
                 class="w-full h-full"
                 xmlns="http://www.w3.org/2000/svg">
                <path d="M0,0 Q360,60 720,60 Q1080,60 1440,0 L1440,60 L0,60 Z" fill="#f8fafc"/>
            </svg>
        </div>
    </section>

    <!-- Brands Selection Section -->
    <section id="marques" class="py-8 sm:py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8">
            <div>
                <span class="text-xs font-extrabold uppercase tracking-wider text-brand-red">Parcourir par Constructeur</span>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 mt-1">Choisissez une Marque</h2>
                <p class="text-sm text-slate-500 mt-1">Sélectionnez le constructeur de votre véhicule pour voir les modèles</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <a href="{{ route('catalog.marques') }}" class="inline-flex items-center px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-black transition-colors">
                    Toutes les marques ({{ count($marques) }}) &rarr;
                </a>
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 sm:gap-6">
            @foreach ($marques as $marque)
                <a href="{{ route('catalog.marque', $marque->slug) }}" 
                   class="group relative bg-white rounded-2xl p-6 border border-slate-200 hover:border-brand-red/50 shadow-sm hover:shadow-xl transition-all flex flex-col items-center text-center overflow-hidden">
                    
                    <!-- Decorative corner accent -->
                    <div class="absolute -right-8 -top-8 w-16 h-16 bg-slate-50 group-hover:bg-brand-red/10 rounded-full transition-colors"></div>

                    <!-- Brand Icon / Logo -->
                    <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-slate-100 group-hover:bg-brand-red/5 border border-slate-200 flex items-center justify-center mb-3 transition-transform group-hover:scale-105 p-2 overflow-hidden">
                        @if ($marque->logo_url)
                            <img src="{{ $marque->logo_url }}" alt="{{ $marque->nom }}" class="w-full h-full object-contain">
                        @else
                            <span class="text-2xl sm:text-3xl font-black tracking-wider text-slate-800 group-hover:text-brand-red transition-colors">
                                {{ strtoupper(substr($marque->nom, 0, 2)) }}
                            </span>
                        @endif
                    </div>

                    <h3 class="text-sm font-black text-slate-900 group-hover:text-brand-red transition-colors">
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
        <section class="py-8 sm:py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8">
                <div>
                    <span class="text-xs font-extrabold uppercase tracking-wider text-brand-red">Nouveautés & Références Clés</span>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900 mt-1">Derniers Articles en Ligne</h2>
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
