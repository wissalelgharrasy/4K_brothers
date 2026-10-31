@extends('admin.layouts.admin')

@section('title', 'Exporter le Catalogue')
@section('header_title', 'Exporter le Catalogue')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">

    <!-- Header description -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Exporter le Catalogue</h1>
            <p class="text-xs text-slate-500 mt-1">
                Générez votre catalogue en PDF (avec photos et pastilles DISP) ou en tableur Excel (.xlsx).
            </p>
        </div>
        <a href="{{ route('admin.catalogue.builder') }}" 
           class="inline-flex items-center px-4 py-2.5 rounded-xl bg-brand-red hover:bg-brand-redHover text-white text-xs font-black shadow-md shadow-brand-red/30 transition-all self-start sm:self-auto">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            Composer un catalogue sur mesure
        </a>
    </div>

    <!-- Main Export Options Card -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-6">
        <div class="border-b border-slate-100 pb-4">
            <h2 class="text-base font-black text-slate-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export par filtres (Marque, Famille, Disponibilité)
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">
                Choisissez vos critères puis sélectionnez le format souhaité (PDF prêt à imprimer ou tableur Excel).
            </p>
        </div>

        <form action="{{ route('admin.export') }}" method="GET" target="_blank" class="space-y-5">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- Filter Marque -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Marque</label>
                    <select name="marque_id" class="w-full text-xs rounded-xl border-slate-300 focus:border-brand-red focus:ring-brand-red/30 py-2.5">
                        <option value="">Toutes les marques</option>
                        @foreach($marques as $m)
                            <option value="{{ $m->id }}">{{ $m->nom }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Famille -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Famille de pièce</label>
                    <select name="famille_id" class="w-full text-xs rounded-xl border-slate-300 focus:border-brand-red focus:ring-brand-red/30 py-2.5">
                        <option value="">Toutes les familles</option>
                        @foreach($familles as $f)
                            <option value="{{ $f->id }}">{{ $f->nom }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Only Active -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Statut des pièces</label>
                    <select name="only_active" class="w-full text-xs rounded-xl border-slate-300 focus:border-brand-red focus:ring-brand-red/30 py-2.5">
                        <option value="1">Pièces actives uniquement</option>
                        <option value="0">Toutes les pièces (y compris inactives)</option>
                    </select>
                </div>
            </div>

            <!-- Export Buttons -->
            <div class="flex flex-wrap items-center gap-3 pt-4 border-t border-slate-100">
                <!-- PDF Export (Primary) -->
                <button type="submit" 
                        name="format" 
                        value="pdf"
                        class="inline-flex items-center px-5 py-3 rounded-xl bg-brand-red hover:bg-brand-redHover text-white text-xs font-black shadow-md shadow-brand-red/30 transition-all cursor-pointer">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Enregistrer en Catalogue PDF
                </button>

                <!-- Excel Export -->
                <button type="submit" 
                        name="format" 
                        value="xlsx"
                        class="inline-flex items-center px-5 py-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold shadow-sm transition-colors cursor-pointer">
                    <svg class="w-4 h-4 mr-2 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Télécharger en format Excel (.xlsx)
                </button>
            </div>
        </form>
    </div>

    <!-- Fast Methods Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Method 1: Custom Builder -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col justify-between">
            <div class="space-y-2">
                <div class="w-10 h-10 rounded-xl bg-brand-red/10 text-brand-red flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <h3 class="text-sm font-black text-slate-900">Créateur de Catalogue sur mesure</h3>
                <p class="text-xs text-slate-500">
                    Cochez pièce par pièce celles que vous souhaitez inclure, définissez un titre personnalisé et générez votre catalogue sur mesure.
                </p>
            </div>
            <div class="pt-5 mt-4 border-t border-slate-100">
                <a href="{{ route('admin.catalogue.builder') }}" class="text-xs font-bold text-brand-red hover:text-brand-redHover flex items-center">
                    Accéder au créateur sur mesure &rarr;
                </a>
            </div>
        </div>

        <!-- Method 2: Selection from Pieces Index -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col justify-between">
            <div class="space-y-2">
                <div class="w-10 h-10 rounded-xl bg-brand-red/10 text-brand-red flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                </div>
                <h3 class="text-sm font-black text-slate-900">Export direct depuis les pièces</h3>
                <p class="text-xs text-slate-500">
                    Dans votre tableau de gestion des pièces, cochez simplement les lignes voulues pour faire apparaître la barre d'export rapide.
                </p>
            </div>
            <div class="pt-5 mt-4 border-t border-slate-100">
                <a href="{{ route('admin.pieces.index') }}" class="text-xs font-bold text-brand-red hover:underline flex items-center">
                    Aller à la gestion des pièces &rarr;
                </a>
            </div>
        </div>

    </div>

</div>
@endsection
