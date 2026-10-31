@extends('admin.layouts.admin')

@section('title', 'Gestion des Marques')
@section('header_title', 'Gestion des Marques')

@section('content')
<div class="space-y-6">

    <!-- Header Actions & Search -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.marques.index') }}" class="w-full sm:w-72">
            <input type="text" 
                   name="search" 
                   value="{{ request('search') }}" 
                   placeholder="Rechercher une marque..." 
                   class="w-full text-xs rounded-xl border-slate-300 focus:border-brand-red focus:ring-brand-red/30 py-2.5">
        </form>

        <a href="{{ route('admin.marques.create') }}" 
           class="inline-flex items-center px-4 py-2.5 rounded-xl bg-brand-red hover:bg-brand-redHover text-white text-xs font-black shadow-md shadow-brand-red/20 transition-all">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            Nouvelle Marque
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-500 uppercase font-black text-[10px] tracking-wider border-b border-slate-100">
                <tr>
                    <th class="p-4">Logo</th>
                    <th class="p-4">Nom</th>
                    <th class="p-4">Slug URL</th>
                    <th class="p-4">Modèles</th>
                    <th class="p-4">Pièces associées</th>
                    <th class="p-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($marques as $marque)
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="p-4">
                            @if ($marque->logo_url)
                                <img src="{{ $marque->logo_url }}" alt="{{ $marque->nom }}" class="w-10 h-10 object-contain rounded-lg border border-slate-200 p-1 bg-white" title="{{ $marque->logo }}">
                            @else
                                <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center font-black text-slate-600 text-xs">
                                    {{ strtoupper(substr($marque->nom, 0, 2)) }}
                                </div>
                            @endif
                        </td>
                        <td class="p-4 font-black text-slate-900 text-sm">
                            {{ $marque->nom }}
                        </td>
                        <td class="p-4 text-slate-500 font-mono text-[11px]">
                            /{{ $marque->slug }}
                        </td>
                        <td class="p-4">
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-bold bg-slate-100 text-slate-700">
                                {{ $marque->vehicules_count }} modèles
                            </span>
                        </td>
                        <td class="p-4">
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-bold bg-slate-100 text-slate-700">
                                {{ $marque->pieces_count }} pièces
                            </span>
                        </td>
                        <td class="p-4 text-right space-x-2">
                            <a href="{{ route('admin.marques.edit', $marque) }}" class="text-xs font-bold text-slate-600 hover:text-brand-red">
                                Modifier
                            </a>

                            <form action="{{ route('admin.marques.destroy', $marque) }}" method="POST" class="inline-block" onsubmit="return confirm('Confirmer la suppression de la marque {{ $marque->nom }} ?');">
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
                        <td colspan="6" class="p-8 text-center text-slate-400">
                            Aucune marque trouvée.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($marques->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $marques->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
