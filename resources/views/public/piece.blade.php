@extends('public.layouts.public')

@section('title', $piece->designation . ' — 4K BROTHERS')
@section('meta_description', $piece->designation . ' pour ' . ($piece->vehicule ? $piece->vehicule->marque->nom . ' ' . $piece->vehicule->nom : 'véhicules') . '. Prix TTC : ' . $piece->formatted_prix . '. Commande rapide WhatsApp.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

    <!-- Breadcrumb -->
    <nav class="flex items-center space-x-2 text-xs font-semibold text-slate-500 mb-6 overflow-x-auto whitespace-nowrap">
        <a href="{{ route('home') }}" class="hover:text-brand-red transition-colors">Accueil</a>
        <span>/</span>
        @if ($piece->vehicule)
            <a href="{{ route('catalog.marque', $piece->vehicule->marque->slug) }}" class="hover:text-brand-red transition-colors">
                {{ $piece->vehicule->marque->nom }}
            </a>
            <span>/</span>
            <a href="{{ route('catalog.vehicule', ['marque' => $piece->vehicule->marque->slug, 'vehicule' => $piece->vehicule->slug]) }}" class="hover:text-brand-red transition-colors">
                {{ $piece->vehicule->nom }}
            </a>
            <span>/</span>
        @endif
        <span class="text-slate-800">{{ $piece->designation }}</span>
    </nav>

    <!-- Piece Detail Card -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden mb-12">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 p-6 sm:p-10">
            
            <!-- Left: Piece Image Preview -->
            <div class="flex flex-col">
                <div class="relative aspect-4/3 sm:aspect-square bg-slate-950 rounded-2xl overflow-hidden border border-slate-800 shadow-inner group">
                    <img id="main-image" 
                         src="{{ $piece->image_url }}" 
                         alt="{{ $piece->designation }}" 
                         class="w-full h-full object-cover">
                    
                    <!-- Floating Position Badge -->
                    <span class="absolute top-4 left-4 px-3 py-1 rounded-lg text-xs font-black tracking-wider bg-slate-900/90 text-white backdrop-blur-sm border border-slate-700 shadow-lg">
                        Position : {{ $piece->position_label }}
                    </span>

                    <!-- Stock Status Badge -->
                    <span class="absolute top-4 right-4 px-3 py-1 rounded-lg text-xs font-bold shadow-lg {{ $piece->isDisponible() ? 'bg-emerald-500 text-white' : 'bg-rose-500 text-white' }}">
                        {{ $piece->isDisponible() ? 'Disponible' : 'Rupture de Stock' }}
                    </span>
                </div>

                <div class="mt-4 flex items-center justify-between text-xs text-slate-500 bg-slate-50 p-3 rounded-xl border border-slate-100">
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Pièce certifiée neuve adaptable
                    </span>
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Expédition rapide au Maroc
                    </span>
                </div>
            </div>

            <!-- Right: Piece Ordering & Attributes -->
            <div class="flex flex-col justify-between">
                <div>
                    <!-- Vehicle and Family Badges -->
                    <div class="flex flex-wrap items-center gap-2 mb-3">
                        @if ($piece->vehicule)
                            <a href="{{ route('catalog.vehicule', ['marque' => $piece->vehicule->marque->slug, 'vehicule' => $piece->vehicule->slug]) }}" 
                               class="px-2.5 py-1 rounded-md text-xs font-black bg-brand-50 text-brand-700 border border-brand-200 hover:bg-brand-100 transition-colors">
                                {{ $piece->vehicule->marque->nom }} {{ $piece->vehicule->nom }}
                            </a>
                        @else
                            <span class="px-2.5 py-1 rounded-md text-xs font-black bg-amber-50 text-amber-800 border border-amber-200">
                                Pièce universelle / À classer
                            </span>
                        @endif

                        <span class="px-2.5 py-1 rounded-md text-xs font-bold bg-slate-100 text-slate-700">
                            Famille : {{ $piece->famille->nom }}
                        </span>

                        <span class="px-2.5 py-1 rounded-md text-xs font-bold bg-slate-100 text-slate-700">
                            Année {{ $piece->annee_debut }}+
                        </span>
                    </div>

                    <!-- Title -->
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 leading-snug mb-4">
                        {{ $piece->designation }}
                    </h1>

                    <!-- Price Block -->
                    <div class="bg-slate-50 rounded-2xl p-4 sm:p-5 border border-slate-100 mb-6 flex items-baseline justify-between">
                        <div>
                            <span class="text-xs text-slate-500 font-semibold uppercase block">Prix unitaire TTC</span>
                            <span id="display-price" class="text-3xl sm:text-4xl font-black text-brand-red">
                                {{ $piece->formatted_prix }}
                            </span>
                            @if ($piece->isDoubleCote())
                                <span class="text-xs text-slate-500 block mt-1">
                                    * Prix indiqué par côté unitaire.
                                </span>
                            @endif
                        </div>
                        <span class="text-xs text-slate-400 font-medium">TVA incluse</span>
                    </div>

                    <!-- Add to Cart Form -->
                    <form action="{{ route('cart.add') }}" method="POST" id="add-to-cart-form">
                        @csrf
                        <input type="hidden" name="piece_id" value="{{ $piece->id }}">

                        <!-- Side Choice for RL Pieces -->
                        @if ($piece->isDoubleCote())
                            <div class="mb-6 p-4 rounded-2xl bg-slate-50/80 border border-slate-200/80">
                                <label class="block text-xs font-extrabold text-slate-900 uppercase tracking-wider mb-3">
                                    1. Choisissez votre Côté <span class="text-brand-red">*</span>
                                </label>

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    
                                    <!-- Option Droit -->
                                    <label class="relative flex flex-col p-3 rounded-xl border-2 cursor-pointer transition-all {{ $piece->dispo_droite ? 'border-slate-200 bg-white hover:border-brand-red/50 has-[:checked]:border-brand-red has-[:checked]:bg-brand-red/5' : 'border-slate-100 bg-slate-100/70 opacity-60 cursor-not-allowed' }}">
                                        <input type="radio" 
                                               name="cote" 
                                               value="droite" 
                                               {{ $piece->dispo_droite ? 'checked' : 'disabled' }}
                                               class="sr-only">
                                        <span class="text-xs font-black text-slate-900">Côté Droit</span>
                                        <span class="text-[10px] font-bold mt-1 {{ $piece->dispo_droite ? 'text-emerald-600' : 'text-rose-600' }}">
                                            {{ $piece->dispo_droite ? 'En stock' : 'Rupture' }}
                                        </span>
                                    </label>

                                    <!-- Option Gauche -->
                                    <label class="relative flex flex-col p-3 rounded-xl border-2 cursor-pointer transition-all {{ $piece->dispo_gauche ? 'border-slate-200 bg-white hover:border-brand-red/50 has-[:checked]:border-brand-red has-[:checked]:bg-brand-red/5' : 'border-slate-100 bg-slate-100/70 opacity-60 cursor-not-allowed' }}">
                                        <input type="radio" 
                                               name="cote" 
                                               value="gauche" 
                                               {{ !$piece->dispo_droite && $piece->dispo_gauche ? 'checked' : '' }}
                                               {{ $piece->dispo_gauche ? '' : 'disabled' }}
                                               class="sr-only">
                                        <span class="text-xs font-black text-slate-900">Côté Gauche</span>
                                        <span class="text-[10px] font-bold mt-1 {{ $piece->dispo_gauche ? 'text-emerald-600' : 'text-rose-600' }}">
                                            {{ $piece->dispo_gauche ? 'En stock' : 'Rupture' }}
                                        </span>
                                    </label>

                                    <!-- Option Les Deux (Paire) -->
                                    @php
                                        $pairAvailable = $piece->dispo_droite && $piece->dispo_gauche;
                                    @endphp
                                    <label class="relative flex flex-col p-3 rounded-xl border-2 cursor-pointer transition-all {{ $pairAvailable ? 'border-slate-200 bg-white hover:border-brand-red/50 has-[:checked]:border-brand-red has-[:checked]:bg-brand-red/5' : 'border-slate-100 bg-slate-100/70 opacity-60 cursor-not-allowed' }}">
                                        <input type="radio" 
                                               name="cote" 
                                               value="les_deux" 
                                               {{ $pairAvailable ? '' : 'disabled' }}
                                               class="sr-only">
                                        <span class="text-xs font-black text-slate-900">Les Deux (Paire)</span>
                                        <span class="text-[10px] font-bold mt-1 {{ $pairAvailable ? 'text-emerald-600' : 'text-rose-600' }}">
                                            {{ $pairAvailable ? '2 côtés dispo' : 'Incomplet' }}
                                        </span>
                                    </label>

                                </div>
                            </div>
                        @endif

                        <!-- Quantity Selector and Action Buttons -->
                        <div class="space-y-4">
                            <div class="flex items-center space-x-4">
                                <div class="w-32">
                                    <label class="block text-xs font-extrabold text-slate-900 uppercase tracking-wider mb-1.5">
                                        Quantité
                                    </label>
                                    <div class="flex items-center border-2 border-slate-200 rounded-xl overflow-hidden bg-white">
                                        <button type="button" 
                                                onclick="let q = document.getElementById('quantite-input'); if(q.value > 1) q.value--;"
                                                class="w-10 h-10 flex items-center justify-center text-slate-600 hover:bg-slate-100 font-black text-base">
                                            -
                                        </button>
                                        <input type="number" 
                                               id="quantite-input" 
                                               name="quantite" 
                                               value="1" 
                                               min="1" 
                                               max="50" 
                                               class="w-12 text-center text-sm font-black border-none focus:ring-0 p-0">
                                        <button type="button" 
                                                onclick="let q = document.getElementById('quantite-input'); q.value++;"
                                                class="w-10 h-10 flex items-center justify-center text-slate-600 hover:bg-slate-100 font-black text-base">
                                            +
                                        </button>
                                    </div>
                                </div>

                                <div class="flex-1 self-end">
                                    @if ($piece->isDisponible())
                                        <button type="submit" 
                                                id="add-to-cart-btn"
                                                class="w-full py-3.5 px-6 rounded-xl bg-brand-red hover:bg-brand-redHover text-white text-sm font-black shadow-lg shadow-brand-red/30 transition-all flex items-center justify-center active:scale-95">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                            Ajouter au Panier
                                        </button>
                                    @else
                                        <button type="button" 
                                                disabled 
                                                class="w-full py-3.5 px-6 rounded-xl bg-slate-300 text-slate-500 text-sm font-black cursor-not-allowed flex items-center justify-center">
                                            Article en Rupture de Stock
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </form>
                </div>

                <!-- Technical Specs Table -->
                <div class="mt-8 pt-6 border-t border-slate-200">
                    <h3 class="text-xs font-black uppercase text-slate-400 tracking-wider mb-3">Caractéristiques Techniques</h3>
                    <div class="grid grid-cols-2 gap-y-2 text-xs">
                        <span class="text-slate-500 font-medium">Référence / Désignation :</span>
                        <span class="font-bold text-slate-800">{{ $piece->designation }}</span>

                        <span class="text-slate-500 font-medium">Constructeur :</span>
                        <span class="font-bold text-slate-800">{{ $piece->vehicule?->marque?->nom ?? 'Multimarque' }}</span>

                        <span class="text-slate-500 font-medium">Véhicule compatible :</span>
                        <span class="font-bold text-slate-800">{{ $piece->vehicule?->nom ?? 'À classer' }}</span>

                        <span class="text-slate-500 font-medium">Position :</span>
                        <span class="font-bold text-slate-800">{{ $piece->position_label }}</span>

                        @if ($piece->variante)
                            <span class="text-slate-500 font-medium">Finition / Variante :</span>
                            <span class="font-bold text-slate-800">{{ $piece->variante }}</span>
                        @endif

                        <span class="text-slate-500 font-medium">Année de mise en circulation :</span>
                        <span class="font-bold text-slate-800">À partir de {{ $piece->annee_debut }}</span>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- Related Pieces -->
    @if ($relatedPieces->isNotEmpty())
        <section class="mt-12">
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 mb-6">
                Pièces souvent consultées pour ce modèle
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($relatedPieces as $rel)
                    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-lg transition-all flex flex-col justify-between group">
                        <a href="{{ route('catalog.piece', ['id' => $rel->id, 'slug' => $rel->slug]) }}" class="block relative aspect-4/3 bg-slate-950 overflow-hidden">
                            <img src="{{ $rel->image_url }}" alt="{{ $rel->designation }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                            <span class="absolute top-2 left-2 px-2 py-0.5 rounded text-[10px] font-black bg-slate-900/90 text-white">
                                {{ $rel->position }}
                            </span>
                        </a>
                        <div class="p-4 flex-1 flex flex-col justify-between">
                            <div>
                                <span class="text-[10px] text-brand-red font-bold uppercase">{{ $rel->famille->nom }}</span>
                                <h3 class="font-bold text-xs text-slate-900 line-clamp-2 mt-1">
                                    <a href="{{ route('catalog.piece', ['id' => $rel->id, 'slug' => $rel->slug]) }}">
                                        {{ $rel->designation }}
                                    </a>
                                </h3>
                            </div>
                            <div class="mt-3 pt-2 border-t border-slate-100 flex items-center justify-between">
                                <span class="text-xs font-black text-slate-900">{{ $rel->formatted_prix }}</span>
                                <a href="{{ route('catalog.piece', ['id' => $rel->id, 'slug' => $rel->slug]) }}" class="text-[11px] font-bold text-brand-red hover:underline">
                                    Voir &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

</div>
@endsection
