@extends('admin.layouts.admin')

@section('title', 'Commandes')
@section('header_title', 'Commandes Clients')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-sm font-black text-slate-900">Commandes reçues</h1>
            <p class="text-xs text-slate-400 mt-0.5">Chaque commande passée depuis le site public</p>
        </div>
        <span class="text-xs font-bold text-slate-500">{{ $commandes->total() }} commande(s)</span>
    </div>

    @if (session('success'))
        <div class="px-4 py-3 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold border border-slate-200">
            {{ session('success') }}
        </div>
    @endif

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase font-black text-[10px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="p-4">Référence</th>
                        <th class="p-4">Client</th>
                        <th class="p-4">Téléphone</th>
                        <th class="p-4">Total</th>
                        <th class="p-4">Statut</th>
                        <th class="p-4">Date</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($commandes as $commande)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="p-4 font-black text-slate-900">{{ $commande->reference }}</td>
                            <td class="p-4 font-bold text-slate-800">{{ $commande->nom }}</td>
                            <td class="p-4">
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $commande->telephone) }}"
                                   target="_blank"
                                   class="inline-flex items-center gap-1.5 text-slate-700 hover:text-brand-red font-bold transition-colors">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                                        <path d="M12 0C5.373 0 0 5.373 0 12c0 2.112.544 4.1 1.5 5.828L0 24l6.336-1.47A11.955 11.955 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.007-1.37l-.36-.213-3.76.872.912-3.658-.234-.375A9.818 9.818 0 1112 21.818z"/>
                                    </svg>
                                    {{ $commande->telephone }}
                                </a>
                            </td>
                            <td class="p-4 font-black text-slate-900">{{ $commande->formatted_prix }}</td>
                            <td class="p-4">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $commande->statut_badge }}">
                                    {{ ucfirst($commande->statut) }}
                                </span>
                            </td>
                            <td class="p-4 text-slate-500">{{ $commande->created_at->format('d/m/Y H:i') }}</td>
                            <td class="p-4 text-right">
                                <a href="{{ route('admin.commandes.show', $commande) }}"
                                   class="inline-flex items-center gap-1 text-slate-600 hover:text-brand-red font-bold transition-colors">
                                    Voir &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-400">
                                Aucune commande reçue pour le moment.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($commandes->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $commandes->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

