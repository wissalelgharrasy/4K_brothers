@extends('admin.layouts.admin')

@section('title', 'Gestion des Véhicules')
@section('header_title', 'Gestion des Véhicules & Modèles')

@section('content')
<div class="space-y-6">

    <!-- Filters & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.vehicules.index') }}" class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
            <select name="marque_id" onchange="this.form.submit()" class="text-xs rounded-xl border-slate-300 focus:border-brand-red focus:ring-brand-red/30 py-2.5">
                <option value="">Toutes les marques</option>
                @foreach ($marques as $m)
                    <option value="{{ $m->id }}" {{ request('marque_id') == $m->id ? 'selected' : '' }}>
                        {{ $m->nom }}
                    </option>
                @endforeach
            </select>

            <input type="text" 
                   name="search" 
                   value="{{ request('search') }}" 
                   placeholder="Rechercher un modèle..." 
                   class="text-xs rounded-xl border-slate-300 focus:border-brand-red focus:ring-brand-red/30 py-2.5 w-48 sm:w-60">
            
            <button type="submit" class="px-3 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold">
                Filtrer
            </button>
        </form>

        <a href="{{ route('admin.vehicules.create') }}" 
           class="inline-flex items-center px-4 py-2.5 rounded-xl bg-brand-red hover:bg-brand-redHover text-white text-xs font-black shadow-md shadow-brand-red/20 transition-all">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            Nouveau Modèle
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-500 uppercase font-black text-[10px] tracking-wider border-b border-slate-100">
                <tr>
                    <th class="p-4">Constructeur</th>
                    <th class="p-4">Modèle (Véhicule)</th>
                    <th class="p-4">Slug URL</th>
                    <th class="p-4">Pièces associées</th>
                    <th class="p-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($vehicules as $vehicule)
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="p-4 font-bold text-brand-red">
                            {{ $vehicule->marque->nom }}
                        </td>
                        <td class="p-4 font-black text-slate-900 text-sm">
                            {{ $vehicule->nom }}
                        </td>
                        <td class="p-4 text-slate-500 font-mono text-[11px]">
                            /{{ $vehicule->marque->slug }}/{{ $vehicule->slug }}
                        </td>
                        <td class="p-4">
                            <a href="{{ route('admin.pieces.index', ['vehicule_id' => $vehicule->id]) }}" class="px-2.5 py-1 rounded-md text-[11px] font-bold bg-slate-100 text-slate-700 hover:bg-slate-200 transition-colors">
                                {{ $vehicule->pieces_count }} pièces &rarr;
                            </a>
                        </td>
                        <td class="p-4 text-right space-x-2">
                            <a href="{{ route('admin.vehicules.edit', $vehicule) }}" class="text-xs font-bold text-slate-600 hover:text-brand-red">
                                Modifier
                            </a>

                            <form action="{{ route('admin.vehicules.destroy', $vehicule) }}" method="POST" class="inline-block" onsubmit="return confirm('Confirmer la suppression du modèle {{ $vehicule->nom }} ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs font-bold text-rose-600 hover:text-rose-800">
                                    Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-slate-400">
                            Aucun véhicule trouvé.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($vehicules->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $vehicules->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
