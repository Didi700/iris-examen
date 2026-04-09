<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    public function changerMotDePasseForm()
    {
        return view('profil.changer-mot-de-passe');
    }

    public function changerMotDePasse(Request $request)
    {
        $request->validate([
            'ancien_mot_de_passe' => ['required'],
            'nouveau_mot_de_passe' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'ancien_mot_de_passe.required' => 'L\'ancien mot de passe est obligatoire.',
            'nouveau_mot_de_passe.required' => 'Le nouveau mot de passe est obligatoire.',
            'nouveau_mot_de_passe.min' => 'Le nouveau mot de passe doit contenir au moins 8 caractères.',
            'nouveau_mot_de_passe.confirmed' => 'Les mots de passe ne correspondent pas.',
        ]);

        $user = auth()->user();

        // Vérifier l'ancien mot de passe
        if (!Hash::check($request->ancien_mot_de_passe, $user->password)) {
            return back()->withErrors(['ancien_mot_de_passe' => 'L\'ancien mot de passe est incorrect.']);
        }

        // Mettre à jour le mot de passe
        $user->update([
            'password' => Hash::make($request->nouveau_mot_de_passe),
            'doit_changer_mot_de_passe' => false,
        ]);

        return redirect()->route($user->role->nom . '.dashboard')
            ->with('success', 'Votre mot de passe a été changé avec succès !');
    }
}