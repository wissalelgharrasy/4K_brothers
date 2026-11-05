<section x-data="{ 
    showCreateModal: @js($errors->has('create_user_error') || ($errors->any() && old('_form') === 'create_user')),
    showEditModal: false,
    showPasswordModal: false,
    showDeleteModal: false,
    selectedUser: { id: null, name: '', email: '' },
    openEdit(user) {
        this.selectedUser = user;
        this.showEditModal = true;
    },
    openPassword(user) {
        this.selectedUser = user;
        this.showPasswordModal = true;
    },
    openDelete(user) {
        this.selectedUser = user;
        this.showDeleteModal = true;
    }
}">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center space-x-2">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-black bg-slate-900 text-white">
                    {{ $users->count() }} {{ Str::plural('administrateur', $users->count()) }}
                </span>
                <span class="text-xs text-slate-500">ont accès au panel 4K Brothers</span>
            </div>
        </div>

        <button type="button" 
                @click="showCreateModal = true"
                class="inline-flex items-center justify-center space-x-2 bg-brand-red hover:bg-brand-redHover text-white text-xs font-bold uppercase tracking-wider py-2.5 px-5 rounded-xl shadow-md shadow-brand-red/25 hover:shadow-lg hover:shadow-brand-red/35 transition-all">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Ajouter un administrateur
        </button>
    </div>

    <!-- Error Summary if any -->
    @if ($errors->any() && old('_form') === 'create_user')
        <div class="p-4 mb-6 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs">
            <p class="font-bold mb-1">Erreur lors de la création de l'administrateur :</p>
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Users Table -->
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-200 text-[11px] font-black uppercase tracking-wider text-slate-500">
                        <th class="py-3.5 px-4 sm:px-6">Administrateur</th>
                        <th class="py-3.5 px-4 hidden md:table-cell">Rôle & Droits</th>
                        <th class="py-3.5 px-4 hidden sm:table-cell">Date d'ajout</th>
                        <th class="py-3.5 px-4 sm:px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                    @foreach ($users as $adminUser)
                        @php
                            $isCurrentUser = ($adminUser->id === auth()->id());
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors {{ $isCurrentUser ? 'bg-amber-50/30' : '' }}">
                            
                            <!-- User Name & Email -->
                            <td class="py-4 px-4 sm:px-6">
                                <div class="flex items-center space-x-3.5">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr {{ $isCurrentUser ? 'from-brand-red to-rose-600 text-white shadow-sm shadow-brand-red/30 ring-2 ring-brand-red/20' : 'from-slate-800 to-slate-900 text-slate-200' }} font-bold text-sm flex items-center justify-center flex-shrink-0">
                                        {{ strtoupper(substr($adminUser->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center space-x-2">
                                            <span class="font-bold text-slate-900 truncate">{{ $adminUser->name }}</span>
                                            @if ($isCurrentUser)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-brand-red text-white">
                                                    Vous
                                                </span>
                                            @endif
                                        </div>
                                        <span class="text-slate-500 text-[11px] block truncate">{{ $adminUser->email }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Role -->
                            <td class="py-4 px-4 hidden md:table-cell">
                                <div class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                    Super Admin
                                </div>
                            </td>

                            <!-- Date Created -->
                            <td class="py-4 px-4 hidden sm:table-cell text-slate-500 text-[11px]">
                                {{ $adminUser->created_at ? $adminUser->created_at->format('d/m/Y à H:i') : 'Inconnue' }}
                            </td>

                            <!-- Actions -->
                            <td class="py-4 px-4 sm:px-6 text-right">
                                <div class="inline-flex items-center justify-end space-x-2">
                                    
                                    <!-- Edit Name/Email Button -->
                                    <button type="button" 
                                            @click="openEdit({ id: {{ $adminUser->id }}, name: '{{ addslashes($adminUser->name) }}', email: '{{ addslashes($adminUser->email) }}' })"
                                            class="p-2 rounded-xl text-slate-500 hover:text-slate-900 hover:bg-slate-100 transition-colors"
                                            title="Modifier les coordonnées">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                        </svg>
                                    </button>

                                    <!-- Change Password Button -->
                                    <button type="button" 
                                            @click="openPassword({ id: {{ $adminUser->id }}, name: '{{ addslashes($adminUser->name) }}' })"
                                            class="inline-flex items-center space-x-1 px-2.5 py-1.5 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-200 transition-colors"
                                            title="Changer le mot de passe">
                                        <svg class="w-3.5 h-3.5 text-slate-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                        </svg>
                                        <span class="hidden lg:inline">Mot de passe</span>
                                    </button>

                                    <!-- Delete Button -->
                                    @if ($isCurrentUser)
                                        <button type="button" 
                                                disabled 
                                                class="p-2 rounded-xl text-slate-300 cursor-not-allowed" 
                                                title="Vous ne pouvez pas supprimer votre propre compte depuis ce tableau">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    @else
                                        <button type="button" 
                                                @click="openDelete({ id: {{ $adminUser->id }}, name: '{{ addslashes($adminUser->name) }}' })"
                                                class="p-2 rounded-xl text-rose-500 hover:text-rose-700 hover:bg-rose-50 transition-colors"
                                                title="Supprimer ce profil">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL 1: Ajouter un nouvel administrateur -->
    <div x-show="showCreateModal" 
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @keydown.escape.window="showCreateModal = false"
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4"
         style="display: none;">
        
        <div class="relative w-full max-w-lg bg-white rounded-2xl border border-slate-200 shadow-2xl p-6 sm:p-8 transform transition-all"
             @click.away="showCreateModal = false"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

            <div class="flex items-center justify-between mb-5 pb-3 border-b border-slate-100">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-brand-red/10 text-brand-red flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-slate-900">Ajouter un Administrateur</h3>
                        <p class="text-xs text-slate-500">Créez un nouvel accès au panel d'administration 4K Brothers.</p>
                    </div>
                </div>
                <button type="button" @click="showCreateModal = false" class="text-slate-400 hover:text-slate-600 text-lg font-bold">&times;</button>
            </div>

            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="_form" value="create_user">

                <div>
                    <label for="create_user_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Nom complet <span class="text-brand-red">*</span>
                    </label>
                    <input type="text" 
                           id="create_user_name" 
                           name="name" 
                           value="{{ old('name') }}" 
                           required 
                           placeholder="Ex. Omar Tazi"
                           class="w-full text-sm rounded-xl border-slate-300 px-4 py-2.5 bg-slate-50 focus:bg-white focus:border-brand-red focus:ring-brand-red/30 transition-all font-medium text-slate-900">
                </div>

                <div>
                    <label for="create_user_email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Adresse e-mail <span class="text-brand-red">*</span>
                    </label>
                    <input type="email" 
                           id="create_user_email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required 
                           placeholder="nom@4kbrothers.ma"
                           class="w-full text-sm rounded-xl border-slate-300 px-4 py-2.5 bg-slate-50 focus:bg-white focus:border-brand-red focus:ring-brand-red/30 transition-all font-medium text-slate-900">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="create_user_password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Mot de passe <span class="text-brand-red">*</span>
                        </label>
                        <input type="password" 
                               id="create_user_password" 
                               name="password" 
                               required 
                               placeholder="••••••••"
                               class="w-full text-sm rounded-xl border-slate-300 px-4 py-2.5 bg-slate-50 focus:bg-white focus:border-brand-red focus:ring-brand-red/30 transition-all font-medium text-slate-900">
                    </div>

                    <div>
                        <label for="create_user_password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Confirmation <span class="text-brand-red">*</span>
                        </label>
                        <input type="password" 
                               id="create_user_password_confirmation" 
                               name="password_confirmation" 
                               required 
                               placeholder="••••••••"
                               class="w-full text-sm rounded-xl border-slate-300 px-4 py-2.5 bg-slate-50 focus:bg-white focus:border-brand-red focus:ring-brand-red/30 transition-all font-medium text-slate-900">
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                    <button type="button" 
                            @click="showCreateModal = false"
                            class="px-4 py-2.5 rounded-xl border border-slate-300 text-xs font-bold text-slate-700 hover:bg-slate-100 transition-colors">
                        Annuler
                    </button>

                    <button type="submit" 
                            class="px-5 py-2.5 rounded-xl bg-brand-red hover:bg-brand-redHover text-white text-xs font-bold uppercase tracking-wider shadow-md shadow-brand-red/25 transition-all">
                        Créer le compte
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 2: Modifier les coordonnées d'un profil -->
    <div x-show="showEditModal" 
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @keydown.escape.window="showEditModal = false"
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4"
         style="display: none;">
        
        <div class="relative w-full max-w-lg bg-white rounded-2xl border border-slate-200 shadow-2xl p-6 sm:p-8 transform transition-all"
             @click.away="showEditModal = false"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

            <div class="flex items-center justify-between mb-5 pb-3 border-b border-slate-100">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-800 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-slate-900">Modifier l'Administrateur</h3>
                        <p class="text-xs text-slate-500" x-text="'Mise à jour du profil : ' + selectedUser.name"></p>
                    </div>
                </div>
                <button type="button" @click="showEditModal = false" class="text-slate-400 hover:text-slate-600 text-lg font-bold">&times;</button>
            </div>

            <form :action="'{{ url('admin/users') }}/' + selectedUser.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Nom complet <span class="text-brand-red">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           x-model="selectedUser.name" 
                           required 
                           class="w-full text-sm rounded-xl border-slate-300 px-4 py-2.5 bg-slate-50 focus:bg-white focus:border-brand-red focus:ring-brand-red/30 transition-all font-medium text-slate-900">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Adresse e-mail <span class="text-brand-red">*</span>
                    </label>
                    <input type="email" 
                           name="email" 
                           x-model="selectedUser.email" 
                           required 
                           class="w-full text-sm rounded-xl border-slate-300 px-4 py-2.5 bg-slate-50 focus:bg-white focus:border-brand-red focus:ring-brand-red/30 transition-all font-medium text-slate-900">
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                    <button type="button" 
                            @click="showEditModal = false"
                            class="px-4 py-2.5 rounded-xl border border-slate-300 text-xs font-bold text-slate-700 hover:bg-slate-100 transition-colors">
                        Annuler
                    </button>

                    <button type="submit" 
                            class="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold uppercase tracking-wider shadow-md transition-all">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 3: Changer le mot de passe d'un profil -->
    <div x-show="showPasswordModal" 
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @keydown.escape.window="showPasswordModal = false"
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4"
         style="display: none;">
        
        <div class="relative w-full max-w-lg bg-white rounded-2xl border border-slate-200 shadow-2xl p-6 sm:p-8 transform transition-all"
             @click.away="showPasswordModal = false"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

            <div class="flex items-center justify-between mb-5 pb-3 border-b border-slate-100">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-slate-900">Réinitialiser le mot de passe</h3>
                        <p class="text-xs text-slate-500" x-text="'Pour l\'administrateur : ' + selectedUser.name"></p>
                    </div>
                </div>
                <button type="button" @click="showPasswordModal = false" class="text-slate-400 hover:text-slate-600 text-lg font-bold">&times;</button>
            </div>

            <form :action="'{{ url('admin/users') }}/' + selectedUser.id + '/password'" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Nouveau mot de passe <span class="text-brand-red">*</span>
                    </label>
                    <input type="password" 
                           name="password" 
                           required 
                           placeholder="••••••••"
                           class="w-full text-sm rounded-xl border-slate-300 px-4 py-2.5 bg-slate-50 focus:bg-white focus:border-brand-red focus:ring-brand-red/30 transition-all font-medium text-slate-900">
                    <p class="mt-1 text-[11px] text-slate-400">Minimum 8 caractères recommandés.</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Confirmer le nouveau mot de passe <span class="text-brand-red">*</span>
                    </label>
                    <input type="password" 
                           name="password_confirmation" 
                           required 
                           placeholder="••••••••"
                           class="w-full text-sm rounded-xl border-slate-300 px-4 py-2.5 bg-slate-50 focus:bg-white focus:border-brand-red focus:ring-brand-red/30 transition-all font-medium text-slate-900">
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                    <button type="button" 
                            @click="showPasswordModal = false"
                            class="px-4 py-2.5 rounded-xl border border-slate-300 text-xs font-bold text-slate-700 hover:bg-slate-100 transition-colors">
                        Annuler
                    </button>

                    <button type="submit" 
                            class="px-5 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold uppercase tracking-wider shadow-md shadow-amber-600/30 transition-all">
                        Changer le mot de passe
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 4: Confirmer la suppression d'un administrateur -->
    <div x-show="showDeleteModal" 
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @keydown.escape.window="showDeleteModal = false"
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4"
         style="display: none;">
        
        <div class="relative w-full max-w-lg bg-white rounded-2xl border border-slate-200 shadow-2xl p-6 sm:p-8 transform transition-all"
             @click.away="showDeleteModal = false"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

            <div class="flex items-center space-x-3 mb-4">
                <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-black text-slate-900">Supprimer cet administrateur ?</h3>
                    <p class="text-xs text-rose-600 font-semibold" x-text="selectedUser.name"></p>
                </div>
            </div>

            <p class="text-xs text-slate-600 mb-6 leading-relaxed">
                Êtes-vous certain de vouloir supprimer le profil de cet administrateur ? Cette personne ne pourra plus se connecter au tableau de bord 4K Brothers.
            </p>

            <form :action="'{{ url('admin/users') }}/' + selectedUser.id" method="POST" class="flex items-center justify-end space-x-3">
                @csrf
                @method('DELETE')

                <button type="button" 
                        @click="showDeleteModal = false"
                        class="px-4 py-2.5 rounded-xl border border-slate-300 text-xs font-bold text-slate-700 hover:bg-slate-100 transition-colors">
                    Annuler
                </button>

                <button type="submit" 
                        class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold uppercase tracking-wider shadow-md shadow-rose-600/30 transition-all">
                    Confirmer la suppression
                </button>
            </form>
        </div>
    </div>

</section>
