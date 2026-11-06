@extends('admin.layouts.admin')

@section('title', 'Tableau de bord')
@section('header_title', 'Tableau de bord')

@section('content')
<div class="space-y-6">

    <!-- Top Compact Header & Quick Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <div>
            <h1 class="text-base font-black text-slate-900 tracking-tight">Vue d'ensemble</h1>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.pieces.create') }}" 
               class="inline-flex items-center px-3.5 py-2 rounded-xl bg-brand-red hover:bg-brand-redHover text-white text-xs font-bold shadow-md shadow-brand-red/25 transition-all">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Nouvelle Pièce
            </a>
            <a href="{{ route('admin.catalogue.builder') }}" 
               class="inline-flex items-center px-3.5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition-all">
                <svg class="w-4 h-4 mr-1.5 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Catalogue PDF
            </a>
            <a href="{{ route('admin.marques.create') }}" 
               class="inline-flex items-center px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all">
                + Marque
            </a>
            <a href="{{ route('admin.vehicules.create') }}" 
               class="inline-flex items-center px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all">
                + Modèle
            </a>
        </div>
    </div>

    <!-- 1. KPI Stats Cards (Compactes, sans texte superflu) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Card 1: Pièces -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Pièces Catalogue</span>
                <span class="text-2xl font-black text-slate-900 tracking-tight block mt-0.5">{{ $stats['pieces_count'] }}</span>
                <span class="inline-flex items-center text-[11px] font-bold text-emerald-600 mt-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                    {{ $stats['pieces_actives_count'] }} actives
                </span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
        </div>

        <!-- Card 2: Modèles & Marques -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Modèles Autos</span>
                <span class="text-2xl font-black text-slate-900 tracking-tight block mt-0.5">{{ $stats['vehicules_count'] }}</span>
                <span class="inline-flex items-center text-[11px] font-bold text-slate-500 mt-1">
                    {{ $stats['marques_count'] }} constructeurs
                </span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                </svg>
            </div>
        </div>

        <!-- Card 3: Familles -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Familles Pièces</span>
                <span class="text-2xl font-black text-slate-900 tracking-tight block mt-0.5">{{ $stats['familles_count'] }}</span>
                <span class="inline-flex items-center text-[11px] font-bold text-slate-500 mt-1">
                    Catégories
                </span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
            </div>
        </div>

        <!-- Card 4: Commandes / Bilatérales -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Commandes</span>
                <span class="text-2xl font-black text-slate-900 tracking-tight block mt-0.5">{{ $stats['commandes_count'] }}</span>
                <span class="inline-flex items-center text-[11px] font-bold text-brand-red mt-1">
                    {{ $stats['pieces_rl_count'] }} pièces D/G (RL)
                </span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-brand-red/10 text-brand-red flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
        </div>

    </div>

    <!-- 2. Graphiques Statistiques (Visualisation claire) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Graphique 1: Répartition par Famille (Barres) -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xs font-black uppercase tracking-wider text-slate-700">Par Famille</h2>
                <span class="text-[10px] font-bold text-slate-400 bg-slate-100 px-2 py-0.5 rounded-md">Top Catégories</span>
            </div>
            <div class="relative h-64 w-full">
                <canvas id="chartFamillesCanvas"></canvas>
            </div>
        </div>

        <!-- Graphique 2: Répartition par Constructeur (Donut) -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <h2 class="text-xs font-black uppercase tracking-wider text-slate-700">Par Constructeur</h2>
                <span class="text-[10px] font-bold text-slate-400 bg-slate-100 px-2 py-0.5 rounded-md">Marques</span>
            </div>
            <div class="relative h-56 w-full flex items-center justify-center my-auto">
                <canvas id="chartMarquesCanvas"></canvas>
            </div>
            <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span><strong>{{ $stats['pieces_count'] }}</strong> pièces</span>
                <a href="{{ route('admin.marques.index') }}" class="font-bold text-brand-red hover:underline">Détails &rarr;</a>
            </div>
        </div>

    </div>

    <!-- 3. Dernières Pièces Référencées (Tableau épuré et compact) -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-xs font-black uppercase tracking-wider text-slate-700">Dernières Pièces Ajoutées</h2>
            <a href="{{ route('admin.pieces.index') }}" class="text-xs font-bold text-brand-red hover:underline">
                Voir tout &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase font-black text-[10px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4">Pièce</th>
                        <th class="py-3 px-4">Véhicule</th>
                        <th class="py-3 px-4">Famille</th>
                        <th class="py-3 px-4">Position</th>
                        <th class="py-3 px-4">Prix</th>
                        <th class="py-3 px-4">Statut</th>
                        <th class="py-3 px-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($recentPieces as $piece)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-3 px-4">
                                <div class="flex items-center space-x-3">
                                    <img src="{{ $piece->image_url }}" alt="" class="w-9 h-9 rounded-lg object-cover bg-slate-900 flex-shrink-0 border border-slate-200">
                                    <div class="truncate max-w-xs">
                                        <span class="font-bold text-slate-900 block truncate">{{ $piece->designation }}</span>
                                        @if ($piece->variante)
                                            <span class="text-[10px] text-slate-400 block truncate">{{ $piece->variante }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <span class="font-bold text-slate-800">{{ $piece->vehicule->marque->nom ?? '' }} {{ $piece->vehicule->nom ?? '' }}</span>
                            </td>
                            <td class="py-3 px-4 text-slate-600 font-medium">
                                {{ $piece->famille->nom }}
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-black {{ $piece->position === 'RL' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-700' }}">
                                    {{ $piece->position }}
                                </span>
                            </td>
                            <td class="py-3 px-4 font-black text-slate-900">
                                {{ $piece->formatted_prix }}
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $piece->actif ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $piece->actif ? 'Actif' : 'Inactif' }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <a href="{{ route('admin.pieces.edit', $piece) }}" class="inline-flex items-center px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-brand-red hover:text-white font-bold text-[11px] text-slate-700 transition-colors">
                                    Modifier
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">
                                Aucune pièce au catalogue.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Chart Familles (Bar Chart)
    const ctxFamilles = document.getElementById('chartFamillesCanvas');
    if (ctxFamilles) {
        new Chart(ctxFamilles, {
            type: 'bar',
            data: {
                labels: @json($chartFamilles['labels']),
                datasets: [{
                    label: 'Nombre de pièces',
                    data: @json($chartFamilles['data']),
                    backgroundColor: [
                        'rgba(229, 26, 26, 0.85)',
                        'rgba(30, 41, 59, 0.85)',
                        'rgba(71, 85, 105, 0.85)',
                        'rgba(100, 116, 139, 0.85)',
                        'rgba(148, 163, 184, 0.85)',
                        'rgba(203, 213, 225, 0.85)'
                    ],
                    borderColor: [
                        '#e51a1a',
                        '#1e293b',
                        '#475569',
                        '#64748b',
                        '#94a3b8',
                        '#cbd5e1'
                    ],
                    borderWidth: 1.5,
                    borderRadius: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleFont: { size: 12, weight: 'bold' },
                        bodyFont: { size: 12 },
                        padding: 10,
                        cornerRadius: 8
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, color: '#94a3b8', font: { size: 11 } },
                        grid: { color: '#f1f5f9' }
                    },
                    x: {
                        ticks: { color: '#475569', font: { size: 11, weight: 'bold' } },
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // 2. Chart Marques (Doughnut Chart)
    const ctxMarques = document.getElementById('chartMarquesCanvas');
    if (ctxMarques) {
        new Chart(ctxMarques, {
            type: 'doughnut',
            data: {
                labels: @json($chartMarques['labels']),
                datasets: [{
                    data: @json($chartMarques['data']),
                    backgroundColor: [
                        '#e51a1a',
                        '#0f172a',
                        '#334155',
                        '#64748b',
                        '#94a3b8',
                        '#cbd5e1'
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            padding: 12,
                            font: { size: 11, weight: 'bold' },
                            color: '#475569'
                        }
                    },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleFont: { size: 12, weight: 'bold' },
                        bodyFont: { size: 12 },
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return ' ' + context.label + ' : ' + context.raw + ' pièces';
                            }
                        }
                    }
                },
                cutout: '68%'
            }
        });
    }
});
</script>
@endpush
