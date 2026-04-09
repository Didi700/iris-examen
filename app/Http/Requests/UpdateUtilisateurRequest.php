<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUtilisateurRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->estAdmin();
    }

    public function rules(): array
    {
        $utilisateur = $this->route('utilisateur');

        return [
            'nom' => ['required', 'string', 'max:255', 'regex:/^[a-zA-ZÀ-ÿ\s\-]+$/'],
            'prenom' => ['required', 'string', 'max:255', 'regex:/^[a-zA-ZÀ-ÿ\s\-]+$/'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('utilisateurs')->ignore($utilisateur->id)],
            'matricule' => ['nullable', 'string', 'max:50', Rule::unique('utilisateurs')->ignore($utilisateur->id)],
            'telephone' => ['nullable', 'string', 'regex:/^[0-9+\-\s()]+$/', 'max:20'],
            'date_naissance' => ['nullable', 'date', 'before:today'],
            'role_id' => ['required', 'exists:roles,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom est obligatoire.',
            'prenom.required' => 'Le prénom est obligatoire.',
            'email.unique' => 'Cet email est déjà utilisé.',
        ];
    }
}