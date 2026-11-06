@extends('admin.layouts.admin')

@section('title', 'Commande ' . $commande->reference)
@section('header_title', 'Détail — ' . $commande->reference)

@section('content')
<div class="space-y-6 max-w-3xl">

    <!-- Back -->
    <a href="{{ route('admin.commandes.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-brand-red transition-colors">
        &larr; Retour aux commandes
    </a>

    @if (session('success'))
        <div class="px-4 py-3 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold border border-slate-200">
            {{ session('success') }}
        </div>
    @endif

    <!-- Client info + statut -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <h2 class="text-sm font-black text-slate-900">{{ $commande->reference }}</h2>
                <p class="text-xs text-slate-400 mt-0.5">{{ $commande->created_at->format('d/m/Y à H:i') }}</p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $commande->statut_badge }}">
                {{ ucfirst($commande->statut) }}
            </span>
        </div>

        <!-- Infos client -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
            <div>
                <p class="text-slate-400 font-bold uppercase tracking-wider text-[10px] mb-1">Client</p>
                <p class="font-black text-slate-900 text-sm">{{ $commande->nom }}</p>
            </div>
            <div>
                <p class="text-slate-400 font-bold uppercase tracking-wider text-[10px] mb-1">Téléphone</p>
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $commande->telephone) }}"
                   target="_blank"
                   class="inline-flex items-center gap-1.5 font-black text-slate-900 hover:text-brand-red transition-colors">
                    <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                        <path d="M12 0C5.373 0 0 5.373 0 12c0 2.112.544 4.1 1.5 5.828L0 24l6.336-1.47A11.955 11.955 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.007-1.37l-.36-.213-3.76.872.912-3.658-.234-.375A9.818 9.818 0 1112 21.818z"/>
                    </svg>
                    {{ $commande->telephone }}
                </a>
            </div>
            <div>
                <p class="text-slate-400 font-bold uppercase tracking-wider text-[10px] mb-1">Adresse / Ville</p>
                <p class="font-bold text-slate-800">{{ $commande->adresse }}</p>
            </div>
            @if ($commande->note)
                <div>
                    <p class="text-slate-400 font-bold uppercase tracking-wider text-[10px] mb-1">Note</p>
                    <p class="font-bold text-slate-800">{{ $commande->note }}</p>
                </div>
            @endif
        </div>

        <!-- Actions rapides -->
        <div class="flex flex-wrap gap-3 pt-3 border-t border-slate-100">
            <!-- Contacter sur WhatsApp -->
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $commande->telephone) }}"
               target="_blank"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-700 text-white text-xs font-black transition-colors">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                    <path d="M12 0C5.373 0 0 5.373 0 12c0 2.112.544 4.1 1.5 5.828L0 24l6.336-1.47A11.955 11.955 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.007-1.37l-.36-.213-3.76.872.912-3.658-.234-.375A9.818 9.818 0 1112 21.818z"/>
                </svg>
                Contacter sur WhatsApp
            </a>

            <!-- Changer le statut -->
            <form method="POST" action="{{ route('admin.commandes.statut', $commande) }}" class="flex items-center gap-2">
                @csrf
                @method('PATCH')
                <select name="statut" class="text-xs font-bold border border-slate-200 rounded-xl px-3 py-2 text-slate-700 bg-white focus:outline-none focus:border-slate-400">
                    @foreach (['nouvelle', 'contacté', 'confirmée', 'annulée'] as $statut)
                        <option value="{{ $statut }}" {{ $commande->statut === $statut ? 'selected' : '' }}>
                            {{ ucfirst($statut) }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold transition-colors">
                    Mettre à jour
                </button>
            </form>
        </div>
    </div>

    <!-- Articles commandés -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-100">
            <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider">Articles commandés</h3>
        </div>
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-500 uppercase font-black text-[10px] tracking-wider border-b border-slate-100">
                <tr>
                    <th class="p-4">Désignation</th>
                    <th class="p-4">Côté</th>
                    <th class="p-4">Qté</th>
                    <th class="p-4 text-right">Prix ligne</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($commande->items as $item)
                    <tr class="hover:bg-slate-50/60">
                        <td class="p-4 font-bold text-slate-900">{{ $item['designation'] ?? '—' }}</td>
                        <td class="p-4 text-slate-500">
                            {{ (isset($item['cote_label']) && $item['cote_label'] !== 'Standard') ? $item['cote_label'] : '—' }}
                        </td>
                        <td class="p-4 font-bold text-slate-800">{{ $item['quantite'] ?? 1 }}</td>
                        <td class="p-4 font-black text-slate-900 text-right">
                            {{ number_format((float) ($item['total_ligne'] ?? 0), 2, ',', ' ') }} {{ config('shop.currency_label', 'DH') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="border-t-2 border-slate-200 bg-slate-50">
                <tr>
                    <td colspan="3" class="p-4 font-black text-slate-900 uppercase tracking-wider text-[11px]">Total</td>
                    <td class="p-4 font-black text-slate-900 text-right text-sm">{{ $commande->formatted_prix }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Supprimer -->
    <div class="flex justify-end">
        <form method="POST" action="{{ route('admin.commandes.destroy', $commande) }}"
              onsubmit="return confirm('Supprimer cette commande ?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-xs font-bold text-slate-400 hover:text-brand-red transition-colors">
                Supprimer la commande
            </button>
        </form>
    </div>

</div>
@endsection

