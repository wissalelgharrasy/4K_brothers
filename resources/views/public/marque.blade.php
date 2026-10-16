@extends('public.layouts.public')

@section('title', 'Pièces détachées ' . $marque->nom . ' — 4K BROTHERS')
@section('meta_description', 'Découvrez toutes les pièces de carrosserie et optiques pour les modèles ' . $marque->nom . ' : pare-chocs, phares, calandres. Commande WhatsApp directe.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    
    <!-- Breadcrumb -->
    <nav class="flex items-center space-x-2 text-xs font-semibold text-slate-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-brand-red transition-colors">Accueil</a>
        <span>/</span>
        <span class="text-slate-800">{{ $marque->nom }}</span>
    </nav>

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 rounded-3xl p-8 sm:p-10 text-white mb-10 shadow-lg border border-slate-700/80">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div>
                <span class="px-3 py-1 rounded-full bg-brand-red text-white text-xs font-black uppercase tracking-wider">
                    Constructeur Automobile
                </span>
                <h1 class="text-3xl sm:text-4xl font-black mt-3">Pièces Détachées {{ $marque->nom }}</h1>
                <p class="text-slate-300 text-sm mt-2 max-w-xl">
                    Sélectionnez votre modèle {{ $marque->nom }} ci-dessous pour accéder au catalogue complet des pièces de carrosserie, optiques et accessoires disponibles.
                </p>
            </div>
            <div class="bg-slate-800/80 backdrop-blur-sm border border-slate-700 rounded-2xl p-4 text-center min-w-[140px]">
                <span class="text-3xl font-black text-brand-red">{{ $vehicules->count() }}</span>
                <span class="text-xs font-bold text-slate-400 block mt-1">Modèles référencés</span>
            </div>
        </div>
    </div>

    <!-- Vehicles Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse ($vehicules as $vehicule)
            <a href="{{ route('catalog.vehicule', ['marque' => $marque->slug, 'vehicule' => $vehicule->slug]) }}" 
               class="group bg-white rounded-2xl border border-slate-200 hover:border-brand-red/50 p-6 shadow-sm hover:shadow-xl transition-all flex flex-col justify-between">
                
                <div>
                    <!-- Vehicle Silhouette Icon / Badge -->
                    <div class="w-14 h-14 rounded-2xl bg-slate-900 group-hover:bg-brand-red text-white flex items-center justify-center font-black text-xl mb-4 transition-colors">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                        </svg>
                    </div>

                    <h2 class="text-xl font-black text-slate-900 group-hover:text-brand-red transition-colors">
                        {{ $vehicule->nom }}
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">Gamme {{ $marque->nom }}</p>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-slate-100 text-slate-700">
                        {{ $vehicule->pieces_count }} pièces
                    </span>

                    <span class="text-xs font-bold text-brand-red flex items-center group-hover:translate-x-1 transition-transform">
                        Voir le catalogue
                        <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                        </svg>
                    </span>
                </div>
            </a>
        @empty
            <div class="col-span-full py-16 text-center bg-white rounded-2xl border border-slate-200">
                <p class="text-slate-500 font-medium">Aucun modèle n'est actuellement disponible pour cette marque.</p>
                <a href="{{ route('home') }}" class="inline-block mt-4 text-brand-red font-bold text-sm hover:underline">
                    &larr; Retour à l'accueil
                </a>
            </div>
        @endforelse
    </div>

</div>
@endsection
