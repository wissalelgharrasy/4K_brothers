<!DOCTYPE html>
<html lang="fr" class="h-full bg-slate-950">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Connexion Administrateur') — 4K BROTHERS</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased bg-slate-950 text-slate-100 flex flex-col justify-between selection:bg-brand-red selection:text-white relative overflow-x-hidden">

    <!-- Ambient Glowing Lights -->
    <div class="fixed -top-48 left-1/2 -translate-x-1/2 w-[650px] h-[350px] bg-brand-red/15 blur-[140px] rounded-full pointer-events-none -z-0"></div>
    <div class="fixed bottom-0 right-0 w-[450px] h-[250px] bg-slate-800/20 blur-[120px] rounded-full pointer-events-none -z-0"></div>

    <!-- Top Navigation Header -->
    <header class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-2 flex items-center justify-between">
        <!-- Back to Public Site -->
        <a href="{{ route('home') }}" 
           class="inline-flex items-center text-xs font-bold text-slate-400 hover:text-white bg-slate-900/80 hover:bg-slate-800 px-3.5 py-2 rounded-xl border border-slate-800 transition-all group shadow-sm">
            <svg class="w-4 h-4 mr-2 text-brand-red transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Retour au catalogue</span>
        </a>

        <!-- Brand Security Pill -->
        <div class="hidden sm:inline-flex items-center space-x-2 text-[11px] font-extrabold uppercase tracking-widest text-slate-400 bg-slate-900/60 px-3 py-1.5 rounded-full border border-slate-800/80">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span>Portail Sécurisé 4K Brothers</span>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="relative z-10 flex-1 flex items-center justify-center px-4 py-8 sm:px-6 lg:px-8">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="relative z-10 w-full text-center py-6 text-xs text-slate-500 border-t border-slate-900/80 bg-slate-950/80 backdrop-blur-sm">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-center space-y-2 sm:space-y-0 sm:space-x-4">
            <span class="font-medium text-slate-400">&copy; {{ date('Y') }} 4K BROTHERS &mdash; Votre Partenaire Auto</span>
            <span class="hidden sm:inline text-slate-700">&bull;</span>
            <span class="text-slate-500 text-[11px]">Accès restreint au personnel habilité &bull; Chiffrement SSL 256-bit</span>
        </div>
    </footer>

</body>
</html>
