<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Store a newly created administrator in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed', Password::defaults()],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'email_verified_at' => now(),
        ]);

        return redirect()->route('profile.edit')->with('success', 'Nouveau profil administrateur ajouté avec succès !');
    }

    /**
     * Update the specified administrator.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        return redirect()->route('profile.edit')->with('success', 'Le profil de '.$user->name.' a été mis à jour.');
    }

    /**
     * Update the password of the specified administrator.
     */
    public function updatePassword(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validateWithBag('changeUserPassword_'.$user->id, [
            'password' => ['required', 'string', 'confirmed', Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('profile.edit')->with('success', 'Le mot de passe de '.$user->name.' a été modifié avec succès !');
    }

    /**
     * Remove the specified administrator from storage.
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('profile.edit')->with('error', 'Vous ne pouvez pas supprimer votre propre compte depuis la liste. Utilisez la zone sensible ci-dessous.');
        }

        if (User::count() <= 1) {
            return redirect()->route('profile.edit')->with('error', 'Action refusée : le système doit conserver au moins un compte administrateur.');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('profile.edit')->with('success', 'Le profil administrateur '.$userName.' a été supprimé avec succès.');
    }
}
