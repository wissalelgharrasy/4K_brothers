<section x-data="{ openModal: @js($errors->userDeletion->isNotEmpty()) }">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="max-w-xl">
            <p class="text-xs text-slate-600 leading-relaxed">
                Une fois votre compte administrateur supprimé, l'ensemble de ses privilèges et données associées seront définitivement révoqués. Cette action ne peut pas être annulée.
            </p>
        </div>

        <button type="button" 
                @click="openModal = true"
                class="flex-shrink-0 inline-flex items-center space-x-2 bg-rose-50 hover:bg-rose-600 text-rose-700 hover:text-white border border-rose-200 hover:border-rose-600 text-xs font-bold uppercase tracking-wider py-2.5 px-5 rounded-xl transition-all shadow-sm">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
            Supprimer mon compte
        </button>
    </div>

    <!-- Modal de confirmation de suppression -->
    <div x-show="openModal" 
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @keydown.escape.window="openModal = false"
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4"
         style="display: none;">
        
        <div class="relative w-full max-w-lg bg-white rounded-2xl border border-slate-200 shadow-2xl p-6 sm:p-8 transform transition-all"
             @click.away="openModal = false"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

            <div class="flex items-center space-x-3 mb-4">
                <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-black text-slate-900">Confirmation de suppression</h3>
                    <p class="text-xs text-rose-600 font-semibold">Cette opération est irréversible.</p>
                </div>
            </div>

            <p class="text-xs text-slate-600 mb-6 leading-relaxed">
                Êtes-vous certain de vouloir supprimer définitivement votre compte administrateur ? Veuillez saisir votre mot de passe pour confirmer cette opération.
            </p>

            <form method="post" action="{{ route('profile.destroy') }}" class="space-y-5">
                @csrf
                @method('delete')

                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Mot de passe administrateur <span class="text-brand-red">*</span>
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           required
                           placeholder="Saisissez votre mot de passe"
                           class="w-full text-sm rounded-xl border-slate-300 px-4 py-2.5 bg-slate-50 focus:bg-white focus:border-brand-red focus:ring-brand-red/30 transition-all font-medium text-slate-900 @if($errors->userDeletion->has('password')) border-rose-400 bg-rose-50/30 @endif">
                    
                    @if ($errors->userDeletion->has('password'))
                        <p class="mt-1.5 text-xs text-rose-600 font-semibold flex items-center">
                            <svg class="w-3.5 h-3.5 mr-1 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $errors->userDeletion->first('password') }}
                        </p>
                    @endif
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                    <button type="button" 
                            @click="openModal = false"
                            class="px-4 py-2.5 rounded-xl border border-slate-300 text-xs font-bold text-slate-700 hover:bg-slate-100 transition-colors">
                        Annuler
                    </button>

                    <button type="submit" 
                            class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold uppercase tracking-wider shadow-md shadow-rose-600/30 transition-all">
                        Supprimer définitivement
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
