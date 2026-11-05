@extends('admin.layouts.admin')

@section('title', 'Mon Profil & Sécurité')
@section('header_title', 'Mon Profil & Sécurité')

@section('content')
<div class="max-w-5xl mx-auto space-y-8">

    <!-- Top Admin Profile Hero Banner -->
    <div class="relative overflow-hidden bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 rounded-2xl p-6 sm:p-8 border border-slate-800 shadow-xl">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-brand-red/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div class="flex items-center space-x-5">
                <!-- User Avatar / Initials -->
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-brand-red to-rose-600 text-white font-black text-2xl flex items-center justify-center shadow-lg shadow-brand-red/30 ring-4 ring-white/10 flex-shrink-0">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <div class="flex items-center space-x-3 mb-1">
                        <h2 class="text-xl font-black text-white tracking-tight">{{ $user->name }}</h2>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-brand-red text-white shadow-sm">
                            ADMINISTRATEUR
                        </span>
                    </div>
                    <p class="text-xs text-slate-400 flex items-center">
                        <svg class="w-3.5 h-3.5 mr-1.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        {{ $user->email }}
                    </p>
                </div>
            </div>

            <!-- Quick Status Chips -->
            <div class="flex flex-wrap items-center gap-3">
                <div class="px-3.5 py-2 rounded-xl bg-slate-800/80 border border-slate-700/60 flex items-center space-x-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-xs font-bold text-slate-200">Connecté</span>
                </div>
                <div class="px-3.5 py-2 rounded-xl bg-slate-800/80 border border-slate-700/60 flex items-center space-x-2">
                    <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span class="text-xs font-bold text-slate-200">{{ $users->count() }} {{ Str::plural('Admin', $users->count()) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 1: Gestion des Profils Administrateurs (Liste, Ajout, Mdp, Suppression) -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm">
        <div class="flex items-center space-x-3 mb-6 pb-4 border-b border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center flex-shrink-0 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-base font-black text-slate-900">Comptes & Profils Administrateurs</h3>
                <p class="text-xs text-slate-500">Gérez les accès à l'administration 4K Brothers : ajoutez un profil, modifiez ses informations ou changez son mot de passe.</p>
            </div>
        </div>

        @include('profile.partials.manage-users')
    </div>

    <!-- Section 2: Mon Compte Connecté (Informations & Mot de Passe) -->
    <div class="space-y-4">
        <div class="flex items-center space-x-2 px-1">
            <span class="text-xs font-black uppercase tracking-wider text-slate-400">Paramètres de mon compte</span>
            <div class="h-px bg-slate-200 flex-1"></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <!-- Informations Personnelles -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center space-x-3 mb-6 pb-4 border-b border-slate-100">
                        <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-black text-slate-900">Mes Informations Personnelles</h3>
                            <p class="text-xs text-slate-500">Mettez à jour votre nom d'affichage et votre adresse e-mail.</p>
                        </div>
                    </div>

                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Mon Mot de Passe -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center space-x-3 mb-6 pb-4 border-b border-slate-100">
                        <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-black text-slate-900">Modifier mon Mot de Passe</h3>
                            <p class="text-xs text-slate-500">Mettez à jour votre propre mot de passe administrateur.</p>
                        </div>
                    </div>

                    @include('profile.partials.update-password-form')
                </div>
            </div>

        </div>
    </div>

    <!-- Section 3: Zone Sensible / Suppression de Mon Compte -->
    <div class="bg-white rounded-2xl border border-rose-200/80 p-6 sm:p-8 shadow-sm">
        <div class="flex items-center space-x-3 mb-4 pb-4 border-b border-rose-100">
            <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <div>
                <h3 class="text-base font-black text-rose-900">Zone Sensible — Suppression de mon Compte</h3>
                <p class="text-xs text-slate-500">Supprimez définitivement votre propre compte administrateur connecté.</p>
            </div>
        </div>

        @include('profile.partials.delete-user-form')
    </div>

</div>
@endsection
