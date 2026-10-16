<x-guest-layout>
    @section('title', 'Zone Sécurisée')

    <div class="w-full max-w-md bg-slate-900/90 backdrop-blur-xl border border-slate-800 rounded-3xl p-6 sm:p-9 shadow-2xl shadow-black/80 relative overflow-hidden">
        <!-- Top Glowing Line -->
        <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-transparent via-brand-red to-transparent"></div>

        <!-- Header / Logo -->
        <div class="text-center mb-6">
            <a href="{{ route('home') }}" class="inline-block transition-transform hover:scale-105 mb-3">
                <img src="{{ asset('images/logo.svg') }}" alt="4K BROTHERS" class="h-12 w-auto mx-auto">
            </a>

            <div class="inline-flex items-center space-x-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-black tracking-widest uppercase bg-brand-red/10 text-brand-red border border-brand-red/20 mb-2">
                <svg class="w-3 h-3 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <span>SÉCURITÉ RENFORCÉE</span>
            </div>

            <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight">
                Zone Sécurisée
            </h1>
            <p class="text-xs text-slate-400 mt-2 leading-relaxed">
                Cette zone nécessite une vérification d'identité. Veuillez confirmer votre mot de passe pour poursuivre.
            </p>
        </div>

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="mb-5 p-3.5 rounded-xl bg-brand-red/10 border border-brand-red/30 text-rose-300 text-xs space-y-1">
                <div class="font-bold flex items-center space-x-1.5 text-brand-red">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Mot de passe incorrect</span>
                </div>
                <ul class="list-disc list-inside space-y-0.5 text-slate-300 pl-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
            @csrf

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-black uppercase tracking-wider text-slate-300 mb-1.5">
                    Mot de passe
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <input id="password" 
                           type="password" 
                           name="password" 
                           required 
                           autocomplete="current-password" 
                           placeholder="••••••••" 
                           class="w-full bg-slate-950/80 border border-slate-700/80 text-white placeholder-slate-500 rounded-xl pl-10 pr-4 py-3 text-sm focus:border-brand-red focus:ring-2 focus:ring-brand-red/30 transition-all outline-none" />
                </div>
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" 
                        class="w-full bg-gradient-to-r from-brand-red to-red-700 hover:from-red-600 hover:to-brand-red text-white font-black py-3.5 px-6 rounded-xl shadow-lg shadow-brand-red/30 hover:shadow-brand-red/50 hover:scale-[1.01] active:scale-[0.99] transition-all duration-200 flex items-center justify-center space-x-2 text-xs uppercase tracking-widest cursor-pointer">
                    <span>Confirmer l'accès</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
