<x-guest-layout>
    @section('title', 'Connexion Back-Office')

    <div class="w-full max-w-md bg-slate-900/90 backdrop-blur-xl border border-slate-800 rounded-3xl p-6 sm:p-9 shadow-2xl shadow-black/80 relative overflow-hidden">
        <!-- Top Glowing Line -->
        <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-transparent via-brand-red to-transparent"></div>

        <!-- Header / Logo -->
        <div class="text-center mb-7">
            <a href="{{ route('home') }}" class="inline-block transition-transform hover:scale-105 mb-3">
                <img src="{{ asset('images/logo.svg') }}" alt="4K BROTHERS" class="h-12 w-auto mx-auto">
            </a>

            <div class="inline-flex items-center space-x-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-black tracking-widest uppercase bg-brand-red/10 text-brand-red border border-brand-red/20 mb-2">
                <svg class="w-3 h-3 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <span>PANEL ADMINISTRATEUR</span>
            </div>

            <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight">
                Connexion Back-Office
            </h1>
            <p class="text-xs text-slate-400 mt-1">
                Accédez à la gestion du catalogue et des pièces
            </p>
        </div>

        <!-- Session Status (Success) -->
        @if (session('status'))
            <div class="mb-5 p-3 rounded-xl bg-emerald-950/60 border border-emerald-800/80 text-emerald-400 text-xs font-semibold flex items-center space-x-2">
                <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="mb-5 p-3.5 rounded-xl bg-brand-red/10 border border-brand-red/30 text-rose-300 text-xs space-y-1">
                <div class="font-bold flex items-center space-x-1.5 text-brand-red">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Identifiants invalides</span>
                </div>
                <ul class="list-disc list-inside space-y-0.5 text-slate-300 pl-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-xs font-black uppercase tracking-wider text-slate-300 mb-1.5">
                    Adresse Email
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                        </svg>
                    </div>
                    <input id="email" 
                           type="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required 
                           autofocus 
                           autocomplete="username" 
                           placeholder="admin@4kbrothers.ma" 
                           class="w-full bg-slate-950/80 border border-slate-700/80 text-white placeholder-slate-500 rounded-xl pl-10 pr-4 py-3 text-sm focus:border-brand-red focus:ring-2 focus:ring-brand-red/30 transition-all outline-none" />
                </div>
            </div>

            <!-- Password -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label for="password" class="block text-xs font-black uppercase tracking-wider text-slate-300">
                        Mot de passe
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" 
                           class="text-xs font-bold text-brand-red hover:text-red-400 transition-colors">
                            Mot de passe oublié ?
                        </a>
                    @endif
                </div>
                <div class="relative" x-data="{ show: false }">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <input id="password" 
                           :type="show ? 'text' : 'password'" 
                           type="password" 
                           name="password" 
                           required 
                           autocomplete="current-password" 
                           placeholder="••••••••" 
                           class="w-full bg-slate-950/80 border border-slate-700/80 text-white placeholder-slate-500 rounded-xl pl-10 pr-11 py-3 text-sm focus:border-brand-red focus:ring-2 focus:ring-brand-red/30 transition-all outline-none" />
                    
                    <!-- Toggle Password Visibility -->
                    <button type="button" 
                            @click="show = !show"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-500 hover:text-slate-300 transition-colors"
                            tabindex="-1">
                        <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <svg x-show="show" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Remember Me -->
            <div class="flex items-center">
                <label for="remember_me" class="inline-flex items-center cursor-pointer select-none">
                    <input id="remember_me" 
                           type="checkbox" 
                           name="remember" 
                           class="w-4 h-4 rounded border-slate-700 bg-slate-950 text-brand-red focus:ring-brand-red/30 focus:ring-offset-0 focus:ring-1 cursor-pointer">
                    <span class="ms-2 text-xs font-semibold text-slate-300">
                        Rester connecté sur cet appareil
                    </span>
                </label>
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" 
                        class="w-full bg-gradient-to-r from-brand-red to-red-700 hover:from-red-600 hover:to-brand-red text-white font-black py-3.5 px-6 rounded-xl shadow-lg shadow-brand-red/30 hover:shadow-brand-red/50 hover:scale-[1.01] active:scale-[0.99] transition-all duration-200 flex items-center justify-center space-x-2 text-xs uppercase tracking-widest cursor-pointer">
                    <span>Accéder au Back-Office</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </div>
        </form>

        <!-- Security Badge in Card Footer -->
        <div class="mt-7 pt-5 border-t border-slate-800 text-center">
            <div class="inline-flex items-center text-[11px] font-medium text-slate-400 space-x-1.5">
                <svg class="w-3.5 h-3.5 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <span>Plateforme d'administration sécurisée &bull; 4K Brothers</span>
            </div>
        </div>
    </div>
</x-guest-layout>
