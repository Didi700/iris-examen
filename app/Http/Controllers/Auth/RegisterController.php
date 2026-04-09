<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LogActivite;
use App\Models\Role;
use App\Models\Utilisateur;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:utilisateurs'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'matricule' => ['required', 'string', 'unique:utilisateurs'],
            'telephone' => ['nullable', 'string', 'max:20'],
        ], [
            'nom.required' => 'Le nom est obligatoire.',
            'prenom.required' => 'Le prénom est obligatoire.',
            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'L\'email doit être valide.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            'matricule.required' => 'Le matricule est obligatoire.',
            'matricule.unique' => 'Ce matricule est déjà utilisé.',
        ]);

        // Par défaut, les inscriptions libres créent des étudiants
        $roleEtudiant = Role::where('nom', 'etudiant')->first();

        $user = Utilisateur::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $roleEtudiant->id,
            'matricule' => $request->matricule,
            'telephone' => $request->telephone,
            'statut' => 'actif',
        ]);

        // Déclencher l'événement d'inscription (pour l'email de vérification)
        event(new Registered($user));

        // Logger l'inscription
        LogActivite::create([
            'utilisateur_id' => $user->id,
            'action' => 'inscription',
            'module' => 'authentification',
            'description' => 'Nouvelle inscription utilisateur',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Connecter automatiquement l'utilisateur
        auth()->login($user);

        return redirect('/etudiant/dashboard')->with('success', 'Inscription réussie ! Veuillez vérifier votre email.');
    }
}