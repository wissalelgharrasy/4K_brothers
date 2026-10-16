@extends('admin.layouts.admin')

@section('title', 'Familles de Pièces')
@section('header_title', 'Familles de Pièces (Catégories)')

@section('content')
<div class="space-y-6">

    <!-- Actions & Search -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.familles.index') }}" class="w-full sm:w-72">
            <input type="text" 
                   name="search" 
                   value="{{ request('search') }}" 
                   placeholder="Rechercher une famille..." 
                   class="w-full text-xs rounded-xl border-slate-300 focus:border-brand-red focus:ring-brand-red/30 py-2.5">
        </form>

        <a href="{{ route('admin.familles.create') }}" 
           class="inline-flex items-center px-4 py-2.5 rounded-xl bg-brand-red hover:bg-brand-redHover text-white text-xs font-black shadow-md shadow-brand-red/20 transition-all">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            Nouvelle Famille
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-500 uppercase font-black text-[10px] tracking-wider border-b border-slate-100">
                <tr>
                    <th class="p-4">Famille</th>
                    <th class="p-4">Slug</th>
                    <th class="p-4">Nombre de Pièces</th>
                    <th class="p-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($familles as $famille)
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="p-4 font-black text-slate-900 text-sm">
                            {{ $famille->nom }}
                        </td>
                        <td class="p-4 text-slate-500 font-mono text-[11px]">
                            {{ $famille->slug }}
                        </td>
                        <td class="p-4">
                            <a href="{{ route('admin.pieces.index', ['famille_id' => $famille->id]) }}" class="px-2.5 py-1 rounded-md text-[11px] font-bold bg-slate-100 text-slate-700 hover:bg-slate-200 transition-colors">
                                {{ $famille->pieces_count }} pièces &rarr;
                            </a>
                        </td>
                        <td class="p-4 text-right space-x-2">
                            <a href="{{ route('admin.familles.edit', $famille) }}" class="text-xs font-bold text-slate-600 hover:text-brand-red">
                                Modifier
                            </a>

                            <form action="{{ route('admin.familles.destroy', $famille) }}" method="POST" class="inline-block" onsubmit="return confirm('Confirmer la suppression de la famille {{ $famille->nom }} ?');">
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
                        <td colspan="4" class="p-8 text-center text-slate-400">
                            Aucune famille trouvée.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($familles->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $familles->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
