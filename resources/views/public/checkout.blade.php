@extends('public.layouts.public')

@section('title', 'Finaliser ma Commande sur WhatsApp — 4K BROTHERS')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

    <!-- Breadcrumb -->
    <nav class="flex items-center space-x-2 text-xs font-semibold text-slate-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-brand-red transition-colors">Accueil</a>
        <span>/</span>
        <a href="{{ route('cart.index') }}" class="hover:text-brand-red transition-colors">Panier</a>
        <span>/</span>
        <span class="text-slate-800">Commande WhatsApp</span>
    </nav>

    <div class="mb-8">
        <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-black uppercase tracking-wider mb-2 border border-emerald-200">
            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.599 2.679-.702c.971.554 1.71.851 2.781.851 3.18 0 5.766-2.587 5.767-5.766.001-3.182-2.585-5.768-5.767-5.768zm3.349 8.167c-.145.409-.844.757-1.157.777-.306.019-.699.034-2.021-.513-1.688-.698-2.771-2.416-2.856-2.528-.084-.112-.676-.9-.676-1.716 0-.817.429-1.218.582-1.385.153-.166.333-.208.444-.208.111 0 .222.002.319.006.102.005.239-.039.373.284.145.351.498 1.216.542 1.305.044.089.073.193.014.31-.059.117-.089.19-.176.293-.087.103-.183.23-.262.309-.089.088-.182.184-.078.362.104.178.463.764 1.002 1.243.693.616 1.278.808 1.456.897.178.089.282.074.386-.045.105-.118.445-.518.563-.696.119-.178.238-.148.4-.089.163.059 1.036.488 1.214.577.178.089.297.133.341.208.044.074.044.43-.101.839z"/>
            </svg>
            <span>Commande Directe WhatsApp</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900">Coordonnées de Livraison</h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">
            Renseignez vos coordonnées ci-dessous. En cliquant sur le bouton vert, votre message de commande sera généré automatiquement et vous serez redirigé vers l'application WhatsApp.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        
        <!-- Form Section -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm">
                
                @if ($errors->any())
                    <div class="p-4 mb-6 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs">
                        <span class="font-bold block mb-1">Veuillez corriger les champs suivants :</span>
                        <ul class="list-disc list-inside space-y-0.5">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form" class="space-y-5">
                    @csrf

                    <div>
                        <label for="nom" class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-2">
                            Nom et Prénom <span class="text-brand-red">*</span>
                        </label>
                        <input type="text" 
                               id="nom" 
                               name="nom" 
                               value="{{ old('nom') }}" 
                               required 
                               placeholder="Ex. Mohamed Benali" 
                               class="w-full text-sm rounded-xl border-slate-300 focus:border-brand-red focus:ring-brand-red/30 py-3">
                    </div>

                    <div>
                        <label for="telephone" class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-2">
                            Numéro de Téléphone (WhatsApp) <span class="text-brand-red">*</span>
                        </label>
                        <input type="tel" 
                               id="telephone" 
                               name="telephone" 
                               value="{{ old('telephone') }}" 
                               required 
                               placeholder="Ex. 06 12 34 56 78" 
                               class="w-full text-sm rounded-xl border-slate-300 focus:border-brand-red focus:ring-brand-red/30 py-3">
                        <span class="text-[11px] text-slate-400 mt-1 block">Le vendeur vous contactera sur ce numéro pour confirmer l'expédition.</span>
                    </div>

                    <div>
                        <label for="adresse" class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-2">
                            Ville & Adresse de Livraison <span class="text-brand-red">*</span>
                        </label>
                        <input type="text" 
                               id="adresse" 
                               name="adresse" 
                               value="{{ old('adresse') }}" 
                               required 
                               placeholder="Ex. Casablanca, Boulevard Zerktouni..." 
                               class="w-full text-sm rounded-xl border-slate-300 focus:border-brand-red focus:ring-brand-red/30 py-3">
                    </div>

                    <div>
                        <label for="note" class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-2">
                            Note pour le vendeur (facultatif)
                        </label>
                        <textarea id="note" 
                                  name="note" 
                                  rows="3" 
                                  placeholder="Précision sur votre modèle, immatriculation, numéro de châssis ou créneau de livraison..." 
                                  class="w-full text-sm rounded-xl border-slate-300 focus:border-brand-red focus:ring-brand-red/30 py-2.5">{{ old('note') }}</textarea>
                    </div>

                    <div class="pt-4">
                        <button type="submit" 
                                class="w-full py-4 px-6 rounded-2xl bg-emerald-600 hover:bg-emerald-500 active:scale-98 text-white text-base font-black shadow-xl shadow-emerald-700/25 transition-all flex items-center justify-center space-x-3">
                            <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                                <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.599 2.679-.702c.971.554 1.71.851 2.781.851 3.18 0 5.766-2.587 5.767-5.766.001-3.182-2.585-5.768-5.767-5.768zm3.349 8.167c-.145.409-.844.757-1.157.777-.306.019-.699.034-2.021-.513-1.688-.698-2.771-2.416-2.856-2.528-.084-.112-.676-.9-.676-1.716 0-.817.429-1.218.582-1.385.153-.166.333-.208.444-.208.111 0 .222.002.319.006.102.005.239-.039.373.284.145.351.498 1.216.542 1.305.044.089.073.193.014.31-.059.117-.089.19-.176.293-.087.103-.183.23-.262.309-.089.088-.182.184-.078.362.104.178.463.764 1.002 1.243.693.616 1.278.808 1.456.897.178.089.282.074.386-.045.105-.118.445-.518.563-.696.119-.178.238-.148.4-.089.163.059 1.036.488 1.214.577.178.089.297.133.341.208.044.074.044.43-.101.839z"/>
                            </svg>
                            <span>Commander sur WhatsApp</span>
                        </button>
                        <p class="text-[11px] text-center text-slate-400 mt-2">
                            L'application WhatsApp s'ouvrira avec le récapitulatif complet de votre commande.
                        </p>
                    </div>

                </form>

            </div>
        </div>

        <!-- Order Summary Card -->
        <div class="md:col-span-1">
            <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm sticky top-28">
                <h2 class="text-sm font-black text-slate-900 pb-3 border-b border-slate-100 mb-4">
                    Articles commandés
                </h2>

                <div class="space-y-3 mb-6 max-h-64 overflow-y-auto pr-1">
                    @foreach ($items as $item)
                        <div class="flex items-start justify-between text-xs pb-2 border-b border-slate-100 last:border-none">
                            <div class="flex-1 pr-2">
                                <span class="font-bold text-slate-800 line-clamp-1 block">{{ $item['designation'] }}</span>
                                <span class="text-[10px] text-slate-500">
                                    {{ $item['cote_label'] !== 'Standard' ? $item['cote_label'] . ' — ' : '' }}Qté: x{{ $item['quantite'] }}
                                </span>
                            </div>
                            <span class="font-black text-slate-900 whitespace-nowrap">
                                {{ number_format($item['total_ligne'], 2, ',', ' ') }} {{ config('shop.currency_label', 'DH') }}
                            </span>
                        </div>
                    @endforeach
                </div>

                <div class="pt-3 border-t border-slate-200 flex justify-between items-baseline mb-4">
                    <span class="text-xs font-black uppercase text-slate-500">Total</span>
                    <span class="text-2xl font-black text-brand-red">{{ $formattedTotal }}</span>
                </div>

                <a href="{{ route('cart.index') }}" class="block text-center text-xs font-bold text-slate-500 hover:text-brand-red transition-colors">
                    &larr; Modifier le panier
                </a>
            </div>
        </div>

    </div>

</div>
@endsection
