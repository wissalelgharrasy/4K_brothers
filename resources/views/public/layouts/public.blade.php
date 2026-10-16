<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', '4K BROTHERS — Pièces de Carrosserie & Éclairage Auto')</title>
    <meta name="description" content="@yield('meta_description', 'Catalogue officiel 4K BROTHERS : pare-chocs, optiques, calandres, feux arrière pour Dacia, Renault et toutes marques. Commande directe sur WhatsApp sans paiement en ligne.')">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-800 min-h-screen flex flex-col selection:bg-brand-red selection:text-white">

    <!-- Top Bar Contact Info -->
    <div class="bg-slate-900 text-slate-300 text-xs py-2 px-4 border-b border-slate-800">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <span class="inline-flex items-center text-emerald-400 font-medium">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse mr-1.5"></span>
                    Catalogue en ligne disponible
                </span>
                <span class="hidden sm:inline text-slate-600">|</span>
                <span class="hidden sm:inline text-slate-400">Vente de pièces carrosserie & éclairage</span>
            </div>
            <div class="flex items-center space-x-4">
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', config('shop.whatsapp_number', '212600000000')) }}" 
                   target="_blank" 
                   rel="noopener"
                   class="inline-flex items-center text-slate-300 hover:text-emerald-400 font-semibold transition-colors">
                    <svg class="w-3.5 h-3.5 mr-1 text-emerald-500 fill-current" viewBox="0 0 24 24">
                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.599 2.679-.702c.971.554 1.71.851 2.781.851 3.18 0 5.766-2.587 5.767-5.766.001-3.182-2.585-5.768-5.767-5.768zm3.349 8.167c-.145.409-.844.757-1.157.777-.306.019-.699.034-2.021-.513-1.688-.698-2.771-2.416-2.856-2.528-.084-.112-.676-.9-.676-1.716 0-.817.429-1.218.582-1.385.153-.166.333-.208.444-.208.111 0 .222.002.319.006.102.005.239-.039.373.284.145.351.498 1.216.542 1.305.044.089.073.193.014.31-.059.117-.089.19-.176.293-.087.103-.183.23-.262.309-.089.088-.182.184-.078.362.104.178.463.764 1.002 1.243.693.616 1.278.808 1.456.897.178.089.282.074.386-.045.105-.118.445-.518.563-.696.119-.178.238-.148.4-.089.163.059 1.036.488 1.214.577.178.089.297.133.341.208.044.074.044.43-.101.839z"/>
                    </svg>
                    WhatsApp : {{ config('shop.whatsapp_number', '212600000000') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <header class="sticky top-0 z-40 bg-slate-900 border-b border-slate-800 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2 group">
                        <img src="{{ asset('images/logo.svg') }}" alt="4K BROTHERS" class="h-12 w-auto transition-transform group-hover:scale-105">
                    </a>
                </div>

                <!-- Global Search Bar (Desktop) -->
                <div class="hidden md:flex flex-1 max-w-lg mx-8">
                    <form action="{{ route('catalog.search') }}" method="GET" class="w-full relative">
                        <input type="text" 
                               name="q" 
                               value="{{ request('q') }}" 
                               placeholder="Rechercher une pièce, optique, pare-choc, Logan, Clio..." 
                               class="w-full bg-slate-800 text-white placeholder-slate-400 text-sm rounded-full pl-5 pr-12 py-2.5 border border-slate-700 focus:outline-none focus:border-brand-red focus:ring-2 focus:ring-brand-red/30 transition-all">
                        <button type="submit" class="absolute right-1.5 top-1.5 w-8 h-8 rounded-full bg-brand-red text-white flex items-center justify-center hover:bg-brand-redHover transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </button>
                    </form>
                </div>

                <!-- Navigation Links & Cart -->
                <div class="flex items-center space-x-3 sm:space-x-4">
                    
                    <!-- Brands Quick Dropdown / Link -->
                    <a href="{{ route('home') }}#marques" class="hidden sm:inline-flex items-center px-3 py-1.5 text-sm font-semibold text-slate-300 hover:text-white transition-colors">
                        Marques
                    </a>

                    <!-- Cart Button -->
                    @php
                        $cartService = app(\App\Services\CartService::class);
                        $cartCount = $cartService->count();
                    @endphp
                    <a href="{{ route('cart.index') }}" 
                       class="relative inline-flex items-center px-4 py-2 rounded-xl bg-slate-800 border border-slate-700 text-white hover:border-brand-red transition-all group">
                        <svg class="w-5 h-5 text-brand-red group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span class="hidden sm:inline ml-2 text-sm font-bold">Panier</span>
                        
                        <span id="cart-badge" class="{{ $cartCount > 0 ? 'inline-flex' : 'hidden' }} ml-2 px-2 py-0.5 text-xs font-black rounded-full bg-brand-red text-white">
                            {{ $cartCount }}
                        </span>
                    </a>

                    <!-- Direct WhatsApp Order Header Button -->
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', config('shop.whatsapp_number', '212600000000')) }}" 
                       target="_blank" 
                       rel="noopener"
                       class="hidden lg:inline-flex items-center px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-bold shadow-md transition-colors">
                        <svg class="w-4 h-4 mr-1.5 fill-current" viewBox="0 0 24 24">
                            <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.599 2.679-.702c.971.554 1.71.851 2.781.851 3.18 0 5.766-2.587 5.767-5.766.001-3.182-2.585-5.768-5.767-5.768zm3.349 8.167c-.145.409-.844.757-1.157.777-.306.019-.699.034-2.021-.513-1.688-.698-2.771-2.416-2.856-2.528-.084-.112-.676-.9-.676-1.716 0-.817.429-1.218.582-1.385.153-.166.333-.208.444-.208.111 0 .222.002.319.006.102.005.239-.039.373.284.145.351.498 1.216.542 1.305.044.089.073.193.014.31-.059.117-.089.19-.176.293-.087.103-.183.23-.262.309-.089.088-.182.184-.078.362.104.178.463.764 1.002 1.243.693.616 1.278.808 1.456.897.178.089.282.074.386-.045.105-.118.445-.518.563-.696.119-.178.238-.148.4-.089.163.059 1.036.488 1.214.577.178.089.297.133.341.208.044.074.044.43-.101.839z"/>
                        </svg>
                        Commander
                    </a>
                </div>
            </div>

            <!-- Mobile Search Bar -->
            <div class="md:hidden pb-4 pt-1">
                <form action="{{ route('catalog.search') }}" method="GET" class="w-full relative">
                    <input type="text" 
                           name="q" 
                           value="{{ request('q') }}" 
                           placeholder="Rechercher une pièce, modèle, optique..." 
                           class="w-full bg-slate-800 text-white placeholder-slate-400 text-sm rounded-full pl-4 pr-11 py-2.5 border border-slate-700 focus:outline-none focus:border-brand-red focus:ring-2 focus:ring-brand-red/30">
                    <button type="submit" class="absolute right-1.5 top-1.5 w-7 h-7 rounded-full bg-brand-red text-white flex items-center justify-center">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Flash Messages -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if (session('success'))
            <div class="p-4 mb-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-800 flex items-center justify-between" role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-emerald-600 mr-2.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-sm font-semibold">{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900 text-sm font-bold">&times;</button>
            </div>
        @endif

        @if (session('error'))
            <div class="p-4 mb-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-800 flex items-center justify-between" role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-rose-600 mr-2.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-sm font-semibold">{{ session('error') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-rose-700 hover:text-rose-900 text-sm font-bold">&times;</button>
            </div>
        @endif

        @if (session('info'))
            <div class="p-4 mb-4 rounded-xl bg-sky-500/10 border border-sky-500/30 text-sky-800 flex items-center justify-between" role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-sky-600 mr-2.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-sm font-semibold">{{ session('info') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-sky-700 hover:text-sky-900 text-sm font-bold">&times;</button>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="flex-grow pb-16">
        @yield('content')
    </main>

    <!-- Mobile Sticky Cart Pill -->
    <div class="fixed bottom-4 right-4 z-50 md:hidden">
        <a href="{{ route('cart.index') }}" 
           class="inline-flex items-center space-x-2 bg-brand-red text-white px-5 py-3 rounded-full shadow-2xl border-2 border-white hover:bg-brand-redHover transition-transform active:scale-95">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <span class="font-black text-sm">Panier</span>
            <span class="px-2 py-0.5 text-xs font-black bg-white text-brand-red rounded-full">
                {{ $cartCount }}
            </span>
        </a>
    </div>

    <!-- Footer -->
    <footer class="bg-slate-900 border-t border-slate-800 text-slate-400 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                
                <!-- Brand Bio -->
                <div class="md:col-span-2">
                    <img src="{{ asset('images/logo.svg') }}" alt="4K BROTHERS" class="h-12 w-auto mb-4">
                    <p class="text-sm text-slate-400 max-w-sm">
                        Votre partenaire auto privilégié pour les pièces de carrosserie, pare-chocs, optiques, calandres et feux de signalisation Dacia, Renault et toutes marques.
                    </p>
                    <div class="mt-4 flex items-center space-x-2 text-xs text-slate-500">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>Sans intermédiaire ni paiement en ligne : validation directe sur WhatsApp</span>
                    </div>
                </div>

                <!-- Marques -->
                <div>
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-3">Marques populaires</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('catalog.marque', 'dacia') }}" class="hover:text-brand-red transition-colors">Dacia (Duster, Sandero, Logan...)</a></li>
                        <li><a href="{{ route('catalog.marque', 'renault') }}" class="hover:text-brand-red transition-colors">Renault (Clio, Express, Kangoo...)</a></li>
                        <li><a href="{{ route('catalog.marque', 'peugeot') }}" class="hover:text-brand-red transition-colors">Peugeot</a></li>
                        <li><a href="{{ route('catalog.marque', 'fiat') }}" class="hover:text-brand-red transition-colors">Fiat</a></li>
                    </ul>
                </div>

                <!-- Contact & WhatsApp -->
                <div>
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-3">Commande Rapide</h3>
                    <p class="text-xs text-slate-400 mb-3">
                        Composez votre panier et envoyez directement votre demande sur WhatsApp.
                    </p>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', config('shop.whatsapp_number', '212600000000')) }}" 
                       target="_blank" 
                       rel="noopener"
                       class="inline-flex items-center justify-center w-full px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm transition-colors shadow-lg shadow-emerald-900/30">
                        <svg class="w-4 h-4 mr-2 fill-current" viewBox="0 0 24 24">
                            <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.599 2.679-.702c.971.554 1.71.851 2.781.851 3.18 0 5.766-2.587 5.767-5.766.001-3.182-2.585-5.768-5.767-5.768zm3.349 8.167c-.145.409-.844.757-1.157.777-.306.019-.699.034-2.021-.513-1.688-.698-2.771-2.416-2.856-2.528-.084-.112-.676-.9-.676-1.716 0-.817.429-1.218.582-1.385.153-.166.333-.208.444-.208.111 0 .222.002.319.006.102.005.239-.039.373.284.145.351.498 1.216.542 1.305.044.089.073.193.014.31-.059.117-.089.19-.176.293-.087.103-.183.23-.262.309-.089.088-.182.184-.078.362.104.178.463.764 1.002 1.243.693.616 1.278.808 1.456.897.178.089.282.074.386-.045.105-.118.445-.518.563-.696.119-.178.238-.148.4-.089.163.059 1.036.488 1.214.577.178.089.297.133.341.208.044.074.044.43-.101.839z"/>
                        </svg>
                        Ouvrir WhatsApp
                    </a>
                    <div class="mt-4 text-xs text-slate-500">
                        <a href="{{ route('login') }}" class="hover:text-slate-300 transition-colors">Espace Administrateur</a>
                    </div>
                </div>

            </div>

            <div class="mt-12 pt-6 border-t border-slate-800 text-xs text-slate-500 flex flex-col sm:flex-row justify-between items-center">
                <p>&copy; {{ date('Y') }} {{ config('shop.name', '4K BROTHERS') }}. Tous droits réservés.</p>
                <p class="mt-2 sm:mt-0">Spécialiste de la pièce détachée automobile au Maroc.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
