<x-guest-layout>
    @section('title', 'Nouveau mot de passe')

    <div class="w-full max-w-md bg-slate-900/90 backdrop-blur-xl border border-slate-800 rounded-3xl p-6 sm:p-9 shadow-2xl shadow-black/80 relative overflow-hidden">
        <!-- Top Glowing Line -->
        <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-transparent via-brand-red to-transparent"></div>

        <!-- Header -->
        <div class="text-center mb-6">
            <a href="{{ route('home') }}" class="inline-block transition-transform hover:scale-105 mb-3">
                <img src="{{ asset('images/logo.svg') }}" alt="4K BROTHERS" class="h-12 w-auto mx-auto">
            </a>

            <div class="inline-flex items-center space-x-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-black tracking-widest uppercase bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 mb-2">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>CODE VÉRIFIÉ</span>
            </div>

            <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight">
                Nouveau mot de passe
            </h1>
            <p class="text-xs text-slate-400 mt-2">
                Choisissez un mot de passe sécurisé pour<br>
                <strong class="text-slate-200">{{ $email }}</strong>
            </p>
        </div>

        <!-- Errors -->
        @if ($errors->any())
            <div class="mb-5 p-3.5 rounded-xl bg-brand-red/10 border border-brand-red/30 text-rose-300 text-xs space-y-1">
                <div class="font-bold flex items-center space-x-1.5 text-brand-red">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Erreur</span>
                </div>
                @foreach ($errors->all() as $error)
                    <p class="pl-6 text-slate-300">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.new.save') }}" class="space-y-5">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">

            <!-- New password -->
            <div>
                <label for="password" class="block text-xs font-black uppercase tracking-wider text-slate-300 mb-1.5">
                    Nouveau mot de passe
                </label>
                <input id="password"
                       type="password"
                       name="password"
                       required
                       autofocus
                       placeholder="••••••••"
                       class="w-full bg-slate-950/80 border border-slate-700/80 text-white placeholder-slate-500 rounded-xl px-4 py-3 text-sm focus:border-brand-red focus:ring-2 focus:ring-brand-red/30 transition-all outline-none" />
            </div>

            <!-- Confirm password -->
            <div>
                <label for="password_confirmation" class="block text-xs font-black uppercase tracking-wider text-slate-300 mb-1.5">
                    Confirmer le mot de passe
                </label>
                <input id="password_confirmation"
                       type="password"
                       name="password_confirmation"
                       required
                       placeholder="••••••••"
                       class="w-full bg-slate-950/80 border border-slate-700/80 text-white placeholder-slate-500 rounded-xl px-4 py-3 text-sm focus:border-brand-red focus:ring-2 focus:ring-brand-red/30 transition-all outline-none" />
            </div>

            <button type="submit"
                    class="w-full bg-gradient-to-r from-brand-red to-red-700 hover:from-red-600 hover:to-brand-red text-white font-black py-3.5 px-6 rounded-xl shadow-lg shadow-brand-red/30 hover:scale-[1.01] active:scale-[0.99] transition-all duration-200 flex items-center justify-center space-x-2 text-xs uppercase tracking-widest cursor-pointer">
                <span>Enregistrer le mot de passe</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </button>
        </form>
    </div>
</x-guest-layout>

