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
                        <img src="{{ asset('images/logo.svg') }}" alt="4K BROTHERS" class="h-14 w-auto transition-transform group-hover:scale-105">
                    </a>
                </div>

                <!-- Desktop Navigation Menu (Accueil & Marques) -->
                <nav class="hidden md:flex items-center space-x-2 ml-4 lg:ml-6">
                    <!-- Accueil -->
                    <a href="{{ route('home') }}" 
                       class="inline-flex items-center px-3.5 py-2 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('home') ? 'bg-brand-red text-white shadow-md shadow-brand-red/30' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Accueil
                    </a>

                    <!-- Marques -->
                    <a href="{{ route('catalog.marques') }}" 
                       class="inline-flex items-center px-3.5 py-2 rounded-xl text-sm font-bold transition-all {{ (request()->routeIs('catalog.marques') || request()->routeIs('catalog.marque') || request()->routeIs('catalog.vehicule')) ? 'bg-brand-red text-white shadow-md shadow-brand-red/30' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        Marques
                    </a>
                </nav>

                <!-- Global Search Bar (Desktop) -->
                <div class="hidden lg:flex flex-1 max-w-md mx-6">
                    <form action="{{ route('catalog.search') }}" method="GET" class="w-full relative">
                        <input type="text" 
                               name="q" 
                               value="{{ request('q') }}" 
                               placeholder="Rechercher une pièce, optique, pare-choc, Logan, Clio..." 
                               class="w-full bg-slate-800 text-white placeholder-slate-400 text-sm rounded-full pl-5 pr-12 py-2.5 border border-slate-700 focus:outline-none focus:border-brand-red focus:ring-2 focus:ring-brand-red/30 transition-all">
                        <button type="submit" class="absolute right-1.5 top-1.5 w-8 h-8 rounded-full bg-brand-red text-white flex items-center justify-center hover:bg-brand-redHover transition-colors cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </button>
                    </form>
                </div>

                <!-- Navigation Actions & Cart -->
                <div class="flex items-center space-x-2 sm:space-x-3">
                    
                    <!-- Cart Button -->
                    @php
                        $cartService = app(\App\Services\CartService::class);
                        $cartCount = $cartService->count();
                    @endphp
                    <a href="{{ route('cart.index') }}" 
                       class="relative inline-flex items-center px-3.5 sm:px-4 py-2 rounded-xl bg-slate-800 border border-slate-700 text-white hover:border-brand-red transition-all group">
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
                       class="hidden sm:inline-flex items-center px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-bold shadow-md transition-colors">
                        <svg class="w-4 h-4 mr-1.5 fill-current" viewBox="0 0 24 24">
                            <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.599 2.679-.702c.971.554 1.71.851 2.781.851 3.18 0 5.766-2.587 5.767-5.766.001-3.182-2.585-5.768-5.767-5.768zm3.349 8.167c-.145.409-.844.757-1.157.777-.306.019-.699.034-2.021-.513-1.688-.698-2.771-2.416-2.856-2.528-.084-.112-.676-.9-.676-1.716 0-.817.429-1.218.582-1.385.153-.166.333-.208.444-.208.111 0 .222.002.319.006.102.005.239-.039.373.284.145.351.498 1.216.542 1.305.044.089.073.193.014.31-.059.117-.089.19-.176.293-.087.103-.183.23-.262.309-.089.088-.182.184-.078.362.104.178.463.764 1.002 1.243.693.616 1.278.808 1.456.897.178.089.282.074.386-.045.105-.118.445-.518.563-.696.119-.178.238-.148.4-.089.163.059 1.036.488 1.214.577.178.089.297.133.341.208.044.074.044.43-.101.839z"/>
                        </svg>
                        Commander
                    </a>

                    <!-- Mobile Menu Hamburger Button -->
                    <button type="button" 
                            id="mobile-menu-toggle"
                            onclick="toggleMobileMenu()" 
                            class="md:hidden p-2 rounded-xl bg-slate-800 text-slate-300 hover:text-white hover:bg-slate-700 transition-colors cursor-pointer"
                            aria-label="Menu principal">
                        <svg id="hamburger-icon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <svg id="close-icon" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation Drawer / Menu -->
            <div id="mobile-menu" class="hidden md:hidden pb-4 pt-2 border-t border-slate-800/80 space-y-3 animate-in fade-in slide-in-from-top-2 duration-150">
                <!-- Links -->
                <div class="grid grid-cols-2 gap-2">
                    <a href="{{ route('home') }}" 
                       class="flex items-center justify-center px-4 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('home') ? 'bg-brand-red text-white shadow-md shadow-brand-red/30' : 'bg-slate-800 text-slate-300 hover:text-white' }}">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Accueil
                    </a>

                    <a href="{{ route('catalog.marques') }}" 
                       class="flex items-center justify-center px-4 py-2.5 rounded-xl text-xs font-bold transition-all {{ (request()->routeIs('catalog.marques') || request()->routeIs('catalog.marque') || request()->routeIs('catalog.vehicule')) ? 'bg-brand-red text-white shadow-md shadow-brand-red/30' : 'bg-slate-800 text-slate-300 hover:text-white' }}">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        Marques
                    </a>
                </div>

                <!-- Mobile Search Bar -->
                <div>
                    <form action="{{ route('catalog.search') }}" method="GET" class="w-full relative">
                        <input type="text" 
                               name="q" 
                               value="{{ request('q') }}" 
                               placeholder="Rechercher une pièce, modèle, optique..." 
                               class="w-full bg-slate-800 text-white placeholder-slate-400 text-xs sm:text-sm rounded-xl pl-4 pr-11 py-2.5 border border-slate-700 focus:outline-none focus:border-brand-red focus:ring-2 focus:ring-brand-red/30">
                        <button type="submit" class="absolute right-1.5 top-1.5 w-7 h-7 rounded-lg bg-brand-red text-white flex items-center justify-center cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Flash Messages -->
    @if (session('success') || session('error') || session('info'))
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
    @endif

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

    <!-- Footer — Style Tiwizi: dark, centered logo, nav, social icons, contact, bottom bar -->
    <footer class="bg-slate-950 text-slate-400 mt-auto">

        {{-- Main footer body --}}
        <div class="max-w-5xl mx-auto px-6 py-14 flex flex-col items-center text-center">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="mb-8 inline-block">
                <img src="{{ asset('images/logo.svg') }}" alt="4K BROTHERS" class="h-16 w-auto mx-auto">
            </a>

            {{-- Nav links --}}
            <nav class="flex flex-wrap justify-center gap-x-8 gap-y-2 text-sm font-semibold text-slate-300 mb-8">
                <a href="{{ route('home') }}" class="hover:text-brand-red transition-colors">Accueil</a>
                <a href="{{ route('catalog.marques') }}" class="hover:text-brand-red transition-colors">Catalogue</a>
                <a href="{{ route('catalog.marque', 'dacia') }}" class="hover:text-brand-red transition-colors">Dacia</a>
                <a href="{{ route('catalog.marque', 'renault') }}" class="hover:text-brand-red transition-colors">Renault</a>
                <a href="{{ route('catalog.marque', 'peugeot') }}" class="hover:text-brand-red transition-colors">Peugeot</a>
                <a href="{{ route('catalog.marque', 'fiat') }}" class="hover:text-brand-red transition-colors">Fiat</a>
                <a href="{{ route('login') }}" class="hover:text-brand-red transition-colors">Admin</a>
            </nav>

            {{-- Social icons (square bordered) --}}
            <div class="flex items-center gap-3 mb-10">
                {{-- Facebook --}}
                <a href="#" target="_blank" rel="noopener"
                   class="w-11 h-11 flex items-center justify-center border border-slate-600 hover:border-brand-red text-slate-300 hover:text-brand-red transition-all">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                        <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
                    </svg>
                </a>
                {{-- WhatsApp --}}
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', config('shop.whatsapp_number', '212600000000')) }}"
                   target="_blank" rel="noopener"
                   class="w-11 h-11 flex items-center justify-center border border-slate-600 hover:border-emerald-500 text-slate-300 hover:text-emerald-400 transition-all">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.599 2.679-.702c.971.554 1.71.851 2.781.851 3.18 0 5.766-2.587 5.767-5.766.001-3.182-2.585-5.768-5.767-5.768zm3.349 8.167c-.145.409-.844.757-1.157.777-.306.019-.699.034-2.021-.513-1.688-.698-2.771-2.416-2.856-2.528-.084-.112-.676-.9-.676-1.716 0-.817.429-1.218.582-1.385.153-.166.333-.208.444-.208.111 0 .222.002.319.006.102.005.239-.039.373.284.145.351.498 1.216.542 1.305.044.089.073.193.014.31-.059.117-.089.19-.176.293-.087.103-.183.23-.262.309-.089.088-.182.184-.078.362.104.178.463.764 1.002 1.243.693.616 1.278.808 1.456.897.178.089.282.074.386-.045.105-.118.445-.518.563-.696.119-.178.238-.148.4-.089.163.059 1.036.488 1.214.577.178.089.297.133.341.208.044.074.044.43-.101.839z"/>
                    </svg>
                </a>
                {{-- Instagram --}}
                <a href="#" target="_blank" rel="noopener"
                   class="w-11 h-11 flex items-center justify-center border border-slate-600 hover:border-pink-500 text-slate-300 hover:text-pink-400 transition-all">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                        <rect x="2" y="2" width="20" height="20" rx="5" ry="5" fill="none" stroke="currentColor" stroke-width="2"/>
                        <circle cx="12" cy="12" r="4" fill="none" stroke="currentColor" stroke-width="2"/>
                        <circle cx="17.5" cy="6.5" r="1" fill="currentColor"/>
                    </svg>
                </a>
            </div>

            {{-- Contact info row --}}
            <div class="flex flex-wrap justify-center gap-6 mb-8 text-sm text-slate-400">
                {{-- Email --}}
                <a href="mailto:4kb.parts@gmail.com"
                   class="flex items-center gap-2 hover:text-brand-red transition-colors">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <rect x="2" y="4" width="20" height="16" rx="2" stroke-width="1.8"/>
                        <path d="M2 7l10 7 10-7" stroke-width="1.8" stroke-linecap="round"/>
                    </svg>
                    4kb.parts@gmail.com
                </a>
                {{-- WhatsApp number --}}
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', config('shop.whatsapp_number', '212600000000')) }}"
                   target="_blank" rel="noopener"
                   class="flex items-center gap-2 hover:text-emerald-400 transition-colors">
                    <svg class="w-5 h-5 fill-current text-emerald-500 shrink-0" viewBox="0 0 24 24">
                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.599 2.679-.702c.971.554 1.71.851 2.781.851 3.18 0 5.766-2.587 5.767-5.766.001-3.182-2.585-5.768-5.767-5.768zm3.349 8.167c-.145.409-.844.757-1.157.777-.306.019-.699.034-2.021-.513-1.688-.698-2.771-2.416-2.856-2.528-.084-.112-.676-.9-.676-1.716 0-.817.429-1.218.582-1.385.153-.166.333-.208.444-.208.111 0 .222.002.319.006.102.005.239-.039.373.284.145.351.498 1.216.542 1.305.044.089.073.193.014.31-.059.117-.089.19-.176.293-.087.103-.183.23-.262.309-.089.088-.182.184-.078.362.104.178.463.764 1.002 1.243.693.616 1.278.808 1.456.897.178.089.282.074.386-.045.105-.118.445-.518.563-.696.119-.178.238-.148.4-.089.163.059 1.036.488 1.214.577.178.089.297.133.341.208.044.074.044.43-.101.839z"/>
                    </svg>
                    {{ config('shop.whatsapp_number', '+212 600 000 000') }}
                </a>
                {{-- Location --}}
                <a href="https://www.google.com/maps?q=33.5926828,-7.5905401&z=17&hl=en"
                   target="_blank" rel="noopener"
                   class="flex items-center gap-2 hover:text-brand-red transition-colors">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Casablanca, Maroc
                </a>
            </div>

            {{-- Divider --}}
            <div class="w-full border-t border-slate-800 mb-6"></div>

            {{-- Bottom bar --}}
            <div class="w-full flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500">
                <div class="flex items-center gap-3">
                    <a href="{{ route('login') }}" class="hover:text-slate-300 transition-colors">Espace Admin</a>
                    <span class="text-slate-700">|</span>
                    <span>Catalogue en ligne</span>
                </div>
                <p class="text-center">
                    &copy; 2022 – {{ date('Y') }} {{ config('shop.name', '4K BROTHERS') }}. Tous Droits Réservés.
                </p>
                <p>Importateur &amp; Distributeur au Maroc</p>
            </div>

        </div>
    </footer>

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            const hamburger = document.getElementById('hamburger-icon');
            const close = document.getElementById('close-icon');

            if (menu.classList.contains('hidden')) {
                menu.classList.remove('hidden');
                hamburger.classList.add('hidden');
                close.classList.remove('hidden');
            } else {
                menu.classList.add('hidden');
                hamburger.classList.remove('hidden');
                close.classList.add('hidden');
            }
        }
    </script>

    @stack('scripts')
</body>
</html>
