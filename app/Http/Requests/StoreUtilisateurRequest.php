<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUtilisateurRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->estAdmin();
    }

    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:255', 'regex:/^[a-zA-ZÀ-ÿ\s\-]+$/'],
            'prenom' => ['required', 'string', 'max:255', 'regex:/^[a-zA-ZÀ-ÿ\s\-]+$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:utilisateurs,email'],
            'matricule' => ['nullable', 'string', 'max:50', 'unique:utilisateurs,matricule'],
            'telephone' => ['nullable', 'string', 'regex:/^[0-9+\-\s()]+$/', 'max:20'],
            'date_naissance' => ['nullable', 'date', 'before:today'],
            'role_id' => ['required', 'exists:roles,id'],
            'password' => ['required', 'string', Password::min(8)->mixedCase()->numbers()],
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom est obligatoire.',
            'nom.regex' => 'Le nom ne peut contenir que des lettres, espaces et tirets.',
            'prenom.required' => 'Le prénom est obligatoire.',
            'email.required' => 'L\'email est obligatoire.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'matricule.unique' => 'Ce matricule est déjà utilisé.',
            'role_id.required' => 'Le rôle est obligatoire.',
            'password.required' => 'Le mot de passe est obligatoire.',
        ];
    }
}