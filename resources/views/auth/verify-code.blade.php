<x-guest-layout>
    @section('title', 'Vérification du code')

    <div class="w-full max-w-md bg-slate-900/90 backdrop-blur-xl border border-slate-800 rounded-3xl p-6 sm:p-9 shadow-2xl shadow-black/80 relative overflow-hidden">
        <!-- Top Glowing Line -->
        <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-transparent via-brand-red to-transparent"></div>

        <!-- Header -->
        <div class="text-center mb-6">
            <a href="{{ route('home') }}" class="inline-block transition-transform hover:scale-105 mb-3">
                <img src="{{ asset('images/logo.svg') }}" alt="4K BROTHERS" class="h-12 w-auto mx-auto">
            </a>

            <div class="inline-flex items-center space-x-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-black tracking-widest uppercase bg-brand-red/10 text-brand-red border border-brand-red/20 mb-2">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span>VÉRIFICATION PAR CODE</span>
            </div>

            <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight">
                Entrez votre code
            </h1>
            <p class="text-xs text-slate-400 mt-2 leading-relaxed">
                Un code à 6 chiffres a été envoyé à<br>
                <strong class="text-slate-200">{{ $email }}</strong>
            </p>
        </div>

        <!-- Status -->
        @if (session('status'))
            <div class="mb-5 p-3.5 rounded-xl bg-emerald-950/60 border border-emerald-800/80 text-emerald-400 text-xs font-semibold flex items-center space-x-2">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        <!-- Errors -->
        @if ($errors->any())
            <div class="mb-5 p-3.5 rounded-xl bg-brand-red/10 border border-brand-red/30 text-rose-300 text-xs space-y-1">
                <div class="font-bold flex items-center space-x-1.5 text-brand-red">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Code invalide</span>
                </div>
                @foreach ($errors->all() as $error)
                    <p class="pl-6 text-slate-300">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.code.verify') }}" class="space-y-5">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">

            <!-- Code input -->
            <div>
                <label for="code" class="block text-xs font-black uppercase tracking-wider text-slate-300 mb-1.5">
                    Code à 6 chiffres
                </label>
                <input id="code"
                       type="text"
                       name="code"
                       value="{{ old('code') }}"
                       required
                       autofocus
                       maxlength="6"
                       placeholder="_ _ _ _ _ _"
                       inputmode="numeric"
                       class="w-full bg-slate-950/80 border border-slate-700/80 text-white placeholder-slate-600 rounded-xl px-4 py-4 text-3xl font-black tracking-[1rem] text-center focus:border-brand-red focus:ring-2 focus:ring-brand-red/30 transition-all outline-none" />
                <p class="text-[11px] text-slate-500 mt-1.5 text-center">Vérifiez votre boîte mail (et les spams)</p>
            </div>

            <button type="submit"
                    class="w-full bg-gradient-to-r from-brand-red to-red-700 hover:from-red-600 hover:to-brand-red text-white font-black py-3.5 px-6 rounded-xl shadow-lg shadow-brand-red/30 hover:scale-[1.01] active:scale-[0.99] transition-all duration-200 flex items-center justify-center space-x-2 text-xs uppercase tracking-widest cursor-pointer">
                <span>Vérifier le code</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </button>
        </form>

        <!-- Back -->
        <div class="mt-6 pt-5 border-t border-slate-800 text-center">
            <a href="{{ route('password.request') }}"
               class="inline-flex items-center text-xs font-bold text-slate-400 hover:text-white transition-colors">
                <svg class="w-3.5 h-3.5 mr-1.5 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Retour — renvoyer le code</span>
            </a>
        </div>
    </div>
</x-guest-layout>

