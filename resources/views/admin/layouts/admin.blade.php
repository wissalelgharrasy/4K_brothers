<!DOCTYPE html>
<html lang="fr" class="h-full bg-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Administration') — 4K BROTHERS</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased text-slate-800 flex flex-col md:flex-row">

    <!-- Admin Sidebar -->
    <aside class="w-full md:w-64 bg-slate-900 border-r border-slate-800 text-slate-300 flex-shrink-0 flex flex-col min-h-screen">
        
        <!-- Sidebar Brand Header -->
        <div class="p-6 border-b border-slate-800 flex items-center justify-between">
            <a href="{{ route('admin.dashboard') }}" class="block">
                <img src="{{ asset('images/logo.svg') }}" alt="4K BROTHERS" class="h-10 w-auto">
                <span class="text-[10px] font-black uppercase tracking-widest text-brand-red block mt-1.5">
                    PANEL ADMINISTRATEUR
                </span>
            </a>
        </div>

        <!-- Sidebar Navigation Menu -->
        <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
            
            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center px-4 py-3 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-brand-red text-white shadow-lg shadow-brand-red/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Tableau de bord
            </a>

            <!-- Pieces (with À Classer Counter) -->
            @php
                $aClasserCount = \App\Models\Piece::aClasser()->count();
            @endphp
            <a href="{{ route('admin.pieces.index') }}" 
               class="flex items-center justify-between px-4 py-3 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.pieces.*') ? 'bg-brand-red text-white shadow-lg shadow-brand-red/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    Pièces du catalogue
                </div>
                @if ($aClasserCount > 0)
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-amber-500 text-slate-950" title="{{ $aClasserCount }} pièce(s) à classer">
                        {{ $aClasserCount }}
                    </span>
                @endif
            </a>

            <!-- Marques -->
            <a href="{{ route('admin.marques.index') }}" 
               class="flex items-center px-4 py-3 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.marques.*') ? 'bg-brand-red text-white shadow-lg shadow-brand-red/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                Marques
            </a>

            <!-- Véhicules -->
            <a href="{{ route('admin.vehicules.index') }}" 
               class="flex items-center px-4 py-3 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.vehicules.*') ? 'bg-brand-red text-white shadow-lg shadow-brand-red/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                </svg>
                Véhicules (Modèles)
            </a>

            <!-- Familles -->
            <a href="{{ route('admin.familles.index') }}" 
               class="flex items-center px-4 py-3 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.familles.*') ? 'bg-brand-red text-white shadow-lg shadow-brand-red/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                Familles de pièces
            </a>

            <!-- Exporter le Catalogue -->
            <a href="{{ route('admin.exports.index') }}" 
               class="flex items-center px-4 py-3 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.exports.*') ? 'bg-brand-red text-white shadow-lg shadow-brand-red/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Exporter le Catalogue
            </a>

            <!-- Créateur Catalogue PDF -->
            <a href="{{ route('admin.catalogue.builder') }}" 
               class="flex items-center px-4 py-3 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.catalogue.builder') ? 'bg-brand-red text-white shadow-lg shadow-brand-red/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Catalogue sur mesure
            </a>

            <div class="pt-4 mt-4 border-t border-slate-800">
                <span class="px-4 text-[10px] font-black uppercase tracking-wider text-slate-500">
                    Outils & Accès
                </span>
            </div>

            <!-- Public Site Link -->
            <a href="{{ route('home') }}" target="_blank" class="flex items-center px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-400 hover:bg-slate-800 hover:text-white transition-colors">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                Voir le site public
            </a>

            <!-- Profile Settings -->
            <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-400 hover:bg-slate-800 hover:text-white transition-colors">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Mon profil / Sécurité
            </a>

        </nav>

        <!-- Sidebar User Footer -->
        <div class="p-4 border-t border-slate-800 bg-slate-950/40">
            <div class="flex items-center justify-between">
                <div class="truncate mr-2">
                    <span class="block text-xs font-bold text-white truncate">{{ auth()->user()->name }}</span>
                    <span class="block text-[10px] text-slate-500 truncate">{{ auth()->user()->email }}</span>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="p-2 rounded-lg bg-slate-800 hover:bg-rose-900/60 text-slate-400 hover:text-rose-200 transition-colors" title="Déconnexion">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>

    </aside>

    <!-- Main Admin Workspace -->
    <div class="flex-1 flex flex-col min-h-screen overflow-x-hidden">
        
        <!-- Top Workspace Bar -->
        <header class="bg-white border-b border-slate-200 py-4 px-6 sm:px-8 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <h1 class="text-xl font-black text-slate-900">
                    @yield('header_title', 'Administration')
                </h1>
            </div>

            <div class="flex items-center space-x-4">
                <span class="inline-flex items-center text-xs text-slate-500 font-medium">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span>
                    En ligne
                </span>
                <span class="text-xs text-slate-400 font-semibold">4K BROTHERS v1.0</span>
            </div>
        </header>

        <!-- Flash Messages -->
        <div class="px-6 sm:px-8 pt-6">
            @if (session('success'))
                <div class="p-4 mb-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-800 text-xs font-bold flex items-center justify-between" role="alert">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-emerald-600 mr-2.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900 font-bold">&times;</button>
                </div>
            @endif

            @if (session('error'))
                <div class="p-4 mb-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-800 text-xs font-bold flex items-center justify-between" role="alert">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-rose-600 mr-2.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-rose-700 hover:text-rose-900 font-bold">&times;</button>
                </div>
            @endif
        </div>

        <!-- Content Area -->
        <main class="flex-1 p-6 sm:px-8">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
