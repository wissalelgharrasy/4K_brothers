@extends('public.layouts.public')

@section('title', 'Votre Panier — 4K BROTHERS')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

    <!-- Breadcrumb -->
    <nav class="flex items-center space-x-2 text-xs font-semibold text-slate-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-brand-red transition-colors">Accueil</a>
        <span>/</span>
        <span class="text-slate-800">Panier</span>
    </nav>

    <div class="flex items-baseline justify-between mb-8 pb-4 border-b border-slate-200">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900">Mon Panier d'Achat</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Vérifiez vos pièces avant d'envoyer votre commande directement sur WhatsApp.
            </p>
        </div>
        @if (!empty($items))
            <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment vider tout le panier ?');">
                @csrf
                <button type="submit" class="text-xs font-bold text-slate-400 hover:text-rose-600 transition-colors">
                    Vider le panier
                </button>
            </form>
        @endif
    </div>

    @if (empty($items))
        <!-- Empty Cart -->
        <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center max-w-lg mx-auto shadow-sm">
            <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mx-auto text-slate-400 mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <h2 class="text-xl font-black text-slate-900">Votre panier est actuellement vide</h2>
            <p class="text-xs text-slate-500 mt-2">
                Parcourez notre catalogue pour ajouter des optiques, pare-chocs et accessoires pour votre véhicule.
            </p>
            <a href="{{ route('home') }}" class="inline-flex items-center mt-6 px-6 py-3 rounded-xl bg-brand-red hover:bg-brand-redHover text-white text-xs font-bold shadow-lg shadow-brand-red/20 transition-all">
                &larr; Découvrir le catalogue
            </a>
        </div>
    @else
        <!-- Cart Layout with Items & Summary -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Items List -->
            <div class="lg:col-span-2 space-y-4">
                @foreach ($items as $key => $item)
                    <div class="bg-white rounded-2xl border border-slate-200 p-4 sm:p-5 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        
                        <!-- Thumbnail & Info -->
                        <div class="flex items-center space-x-4 flex-1">
                            <div class="w-20 h-20 rounded-xl bg-slate-950 overflow-hidden flex-shrink-0 border border-slate-800">
                                <img src="{{ $item['image'] }}" alt="{{ $item['designation'] }}" class="w-full h-full object-cover">
                            </div>

                            <div>
                                <span class="text-[10px] font-bold text-brand-red uppercase tracking-wider block">
                                    {{ $item['marque_nom'] }} &bull; {{ $item['vehicule_nom'] }}
                                </span>
                                
                                <h3 class="font-bold text-sm text-slate-900 leading-snug">
                                    <a href="{{ route('catalog.piece', ['id' => $item['piece_id'], 'slug' => $item['slug']]) }}" class="hover:text-brand-red transition-colors">
                                        {{ $item['designation'] }}
                                    </a>
                                </h3>

                                @if (!empty($item['cote_label']) && $item['cote_label'] !== 'Standard')
                                    <span class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-extrabold bg-slate-100 text-slate-700">
                                        {{ $item['cote_label'] }}
                                    </span>
                                @endif

                                <div class="text-xs font-medium text-slate-500 mt-1">
                                    {{ number_format($item['prix_par_unite_vendue'], 2, ',', ' ') }} {{ config('shop.currency_label', 'DH') }} / unité
                                </div>
                            </div>
                        </div>

                        <!-- Quantity & Total -->
                        <div class="flex items-center justify-between sm:justify-end w-full sm:w-auto space-x-6 border-t sm:border-t-0 pt-3 sm:pt-0 border-slate-100">
                            
                            <!-- Quantity Form -->
                            <form action="{{ route('cart.update', $key) }}" method="POST" class="flex items-center border border-slate-300 rounded-xl overflow-hidden bg-white">
                                @csrf
                                @method('PATCH')
                                
                                <button type="submit" 
                                        name="quantite" 
                                        value="{{ $item['quantite'] - 1 }}" 
                                        class="w-8 h-8 flex items-center justify-center text-slate-600 hover:bg-slate-100 font-black text-sm">
                                    -
                                </button>
                                
                                <span class="w-8 text-center text-xs font-black text-slate-800">
                                    {{ $item['quantite'] }}
                                </span>

                                <button type="submit" 
                                        name="quantite" 
                                        value="{{ $item['quantite'] + 1 }}" 
                                        class="w-8 h-8 flex items-center justify-center text-slate-600 hover:bg-slate-100 font-black text-sm">
                                    +
                                </button>
                            </form>

                            <!-- Line Total -->
                            <div class="text-right min-w-[90px]">
                                <span class="text-sm font-black text-slate-900 block">
                                    {{ number_format($item['total_ligne'], 2, ',', ' ') }} {{ config('shop.currency_label', 'DH') }}
                                </span>
                            </div>

                            <!-- Remove Line Button -->
                            <form action="{{ route('cart.remove', $key) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-slate-400 hover:text-rose-600 transition-colors p-1" title="Supprimer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>

                        </div>

                    </div>
                @endforeach
            </div>

            <!-- Order Summary Column -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm sticky top-28">
                    <h2 class="text-base font-black text-slate-900 pb-3 border-b border-slate-100 mb-4">
                        Récapitulatif de Commande
                    </h2>

                    <div class="space-y-3 text-xs mb-6">
                        <div class="flex justify-between text-slate-500">
                            <span>Articles ({{ $count }})</span>
                            <span class="font-bold text-slate-800">{{ $formattedTotal }}</span>
                        </div>
                        <div class="flex justify-between text-slate-500">
                            <span>Livraison</span>
                            <span class="font-bold text-emerald-600">Calculée avec le vendeur</span>
                        </div>
                        <div class="pt-3 border-t border-slate-100 flex justify-between items-baseline">
                            <span class="text-sm font-black text-slate-900">Total estimé</span>
                            <span class="text-xl font-black text-brand-red">{{ $formattedTotal }}</span>
                        </div>
                    </div>

                    <a href="{{ route('checkout.index') }}" 
                       class="w-full py-3.5 px-6 rounded-xl bg-brand-red hover:bg-brand-redHover text-white text-xs font-black uppercase tracking-wider shadow-lg shadow-brand-red/30 transition-all flex items-center justify-center active:scale-95">
                        Passer la commande &rarr;
                    </a>

                    <div class="mt-4 p-3 bg-slate-50 rounded-xl border border-slate-100 text-[11px] text-slate-500 flex items-start space-x-2">
                        <svg class="w-4 h-4 text-emerald-500 flex-shrink-0 mt-0.5 fill-current" viewBox="0 0 24 24">
                            <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.599 2.679-.702c.971.554 1.71.851 2.781.851 3.18 0 5.766-2.587 5.767-5.766.001-3.182-2.585-5.768-5.767-5.768zm3.349 8.167c-.145.409-.844.757-1.157.777-.306.019-.699.034-2.021-.513-1.688-.698-2.771-2.416-2.856-2.528-.084-.112-.676-.9-.676-1.716 0-.817.429-1.218.582-1.385.153-.166.333-.208.444-.208.111 0 .222.002.319.006.102.005.239-.039.373.284.145.351.498 1.216.542 1.305.044.089.073.193.014.31-.059.117-.089.19-.176.293-.087.103-.183.23-.262.309-.089.088-.182.184-.078.362.104.178.463.764 1.002 1.243.693.616 1.278.808 1.456.897.178.089.282.074.386-.045.105-.118.445-.518.563-.696.119-.178.238-.148.4-.089.163.059 1.036.488 1.214.577.178.089.297.133.341.208.044.074.044.43-.101.839z"/>
                        </svg>
                        <span>Pas de carte bancaire requise. Vous confirmerez les détails de livraison directement avec le vendeur sur WhatsApp.</span>
                    </div>

                    <a href="{{ route('home') }}" class="block text-center mt-4 text-xs font-bold text-slate-500 hover:text-brand-red transition-colors">
                        &larr; Continuer mes achats
                    </a>
                </div>
            </div>

        </div>
    @endif

</div>
@endsection
