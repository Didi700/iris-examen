<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LogActivite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'L\'email doit être valide.',
            'password.required' => 'Le mot de passe est obligatoire.',
        ]);

        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Vérifier si le compte est actif
            if ($user->statut !== 'actif') {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => 'Votre compte est inactif. Contactez l\'administrateur.',
                ]);
            }

            // Logger la connexion
            LogActivite::log('connexion', 'authentification', 'Connexion réussie');

            // Rediriger selon le rôle
            return $this->redirectToDashboard($user);
        }

        throw ValidationException::withMessages([
            'email' => 'Ces identifiants ne correspondent pas à nos enregistrements.',
        ]);
    }

    public function logout(Request $request)
    {
        // Logger la déconnexion avant de se déconnecter
        LogActivite::log('deconnexion', 'authentification', 'Déconnexion');

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    private function redirectToDashboard($user)
    {
        if ($user->estSuperAdmin() || $user->estAdmin()) {
            return redirect()->intended('/admin/dashboard');
        }

        if ($user->estEnseignant()) {
            return redirect()->intended('/enseignant/dashboard');
        }

        if ($user->estEtudiant()) {
            return redirect()->intended('/etudiant/dashboard');
        }

        return redirect('/');
    }
}