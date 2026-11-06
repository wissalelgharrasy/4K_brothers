<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetCodeMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the "forgot password" form (step 1: enter email).
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Send a 6-digit OTP code to the user's email (step 1 submit).
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        // Always return success to avoid email enumeration
        if ($user) {
            $code = (string) random_int(100000, 999999);

            // Store code in cache for 10 minutes keyed by email
            Cache::put('pwd_reset_code_'.md5($request->email), $code, now()->addMinutes(10));

            Mail::to($user->email)->send(new PasswordResetCodeMail($code));
        }

        return redirect()->route('password.code.form', ['email' => $request->email])
            ->with('status', 'Un code à 6 chiffres a été envoyé à votre adresse email.');
    }

    /**
     * Display the code verification form (step 2).
     */
    public function codeForm(Request $request): View
    {
        return view('auth.verify-code', [
            'email' => $request->query('email', ''),
        ]);
    }

    /**
     * Verify the OTP code and show the new password form (step 3).
     */
    public function verifyCode(Request $request): RedirectResponse|View
    {
        $request->validate([
            'email' => ['required', 'email'],
            'code' => ['required', 'digits:6'],
        ]);

        $cacheKey = 'pwd_reset_code_'.md5($request->email);
        $stored = Cache::get($cacheKey);

        if (! $stored || $stored !== $request->code) {
            return back()->withInput()->withErrors(['code' => 'Code incorrect ou expiré.']);
        }

        // Code valid — store a verified flag and redirect to new password form
        Cache::put('pwd_reset_verified_'.md5($request->email), true, now()->addMinutes(10));

        return redirect()->route('password.new.form', ['email' => $request->email]);
    }

    /**
     * Display the new password form (step 3).
     */
    public function newPasswordForm(Request $request): View
    {
        // Guard: must have verified the code first
        $verified = Cache::get('pwd_reset_verified_'.md5($request->query('email', '')));

        if (! $verified) {
            abort(403, 'Accès non autorisé.');
        }

        return view('auth.new-password-otp', [
            'email' => $request->query('email', ''),
        ]);
    }

    /**
     * Save the new password (step 3 submit).
     */
    public function saveNewPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $cacheKey = 'pwd_reset_verified_'.md5($request->email);

        if (! Cache::get($cacheKey)) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Session expirée. Recommencez.']);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $user->update(['password' => Hash::make($request->password)]);

        // Clean up cache
        Cache::forget($cacheKey);
        Cache::forget('pwd_reset_code_'.md5($request->email));

        return redirect()->route('login')
            ->with('status', 'Mot de passe modifié avec succès. Connectez-vous.');
    }
}
