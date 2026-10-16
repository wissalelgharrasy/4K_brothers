@extends('public.layouts.public')

@section('title', 'Résultats de recherche pour "' . $query . '" — 4K BROTHERS')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    
    <!-- Breadcrumb -->
    <nav class="flex items-center space-x-2 text-xs font-semibold text-slate-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-brand-red transition-colors">Accueil</a>
        <span>/</span>
        <span class="text-slate-800">Recherche</span>
    </nav>

    <!-- Search Header -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 rounded-3xl p-6 sm:p-8 text-white mb-8 border border-slate-700/80 shadow-md">
        <div class="max-w-2xl">
            <span class="text-xs font-black uppercase text-brand-red tracking-wider">Recherche au catalogue</span>
            <h1 class="text-2xl sm:text-3xl font-black mt-2">
                @if (!empty($query))
                    Résultats pour : <span class="text-brand-red">« {{ $query }} »</span>
                @else
                    Rechercher une pièce détachée
                @endif
            </h1>
            <p class="text-xs sm:text-sm text-slate-300 mt-2">
                {{ $totalCount }} pièce(s) trouvée(s) groupée(s) par véhicule compatible.
            </p>
        </div>

        <!-- Search Bar -->
        <div class="mt-6 max-w-xl">
            <form action="{{ route('catalog.search') }}" method="GET" class="relative">
                <input type="text" 
                       name="q" 
                       value="{{ $query }}" 
                       placeholder="Rechercher : optique duster, pare-choc clio, calandre..." 
                       class="w-full bg-slate-800 text-white placeholder-slate-400 text-sm rounded-full pl-5 pr-12 py-3 border border-slate-700 focus:outline-none focus:border-brand-red focus:ring-2 focus:ring-brand-red/30">
                <button type="submit" class="absolute right-1.5 top-1.5 w-9 h-9 rounded-full bg-brand-red text-white flex items-center justify-center hover:bg-brand-redHover transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <!-- Results Grouped by Vehicle -->
    @if ($totalCount > 0 && isset($groupedResults))
        <div class="space-y-12">
            @foreach ($groupedResults as $groupName => $items)
                <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                        <div class="flex items-center space-x-3">
                            <span class="w-3 h-3 rounded-full bg-brand-red"></span>
                            <h2 class="text-lg sm:text-xl font-black text-slate-900">{{ $groupName }}</h2>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700">
                            {{ count($items) }} pièce(s)
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach ($items as $piece)
                            <div class="bg-slate-50/50 rounded-2xl border border-slate-200/80 overflow-hidden shadow-sm hover:shadow-md hover:border-brand-red/40 transition-all flex flex-col justify-between group">
                                <a href="{{ route('catalog.piece', ['id' => $piece->id, 'slug' => $piece->slug]) }}" class="block relative aspect-4/3 bg-slate-950 overflow-hidden">
                                    <img src="{{ $piece->image_url }}" alt="{{ $piece->designation }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                    <span class="absolute top-2 left-2 px-2 py-0.5 rounded text-[10px] font-black bg-slate-900/90 text-white">
                                        {{ $piece->position }}
                                    </span>
                                </a>

                                <div class="p-4 flex-1 flex flex-col justify-between">
                                    <div>
                                        <span class="text-[10px] text-brand-red font-bold uppercase">{{ $piece->famille->nom }}</span>
                                        <h3 class="font-bold text-xs text-slate-900 line-clamp-2 mt-1">
                                            <a href="{{ route('catalog.piece', ['id' => $piece->id, 'slug' => $piece->slug]) }}">
                                                {{ $piece->designation }}
                                            </a>
                                        </h3>
                                    </div>

                                    <div class="mt-4 pt-3 border-t border-slate-200/60 flex items-center justify-between">
                                        <span class="text-xs font-black text-slate-900">{{ $piece->formatted_prix }}</span>
                                        <a href="{{ route('catalog.piece', ['id' => $piece->id, 'slug' => $piece->slug]) }}" class="px-2.5 py-1 bg-slate-900 group-hover:bg-brand-red text-white text-[11px] font-bold rounded-lg transition-colors">
                                            Voir &rarr;
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center max-w-lg mx-auto">
            <svg class="w-16 h-16 mx-auto text-slate-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <h3 class="text-xl font-black text-slate-900">Aucun résultat trouvé</h3>
            <p class="text-xs text-slate-500 mt-2">
                Nous n'avons pas trouvé de pièces correspondant à votre recherche. Vérifiez l'orthographe ou essayez un mot plus général (ex. « pare choc », « optique », « duster »).
            </p>
            <a href="{{ route('home') }}" class="inline-block mt-6 px-6 py-2.5 rounded-xl bg-slate-900 hover:bg-brand-red text-white text-xs font-bold transition-colors">
                Retour à l'accueil
            </a>
        </div>
    @endif

</div>
@endsection
