<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExamenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->estEnseignant();
    }

    public function rules(): array
    {
        return [
            'titre' => ['required', 'string', 'max:255', 'min:3'],
            'description' => ['nullable', 'string', 'max:5000'],
            'classe_id' => ['required', 'exists:classes,id'],
            'matiere_id' => ['required', 'exists:matieres,id'],
            'type' => ['required', 'in:formatif,sommatif,diagnostic'],
            'duree' => ['required', 'integer', 'min:1', 'max:480'],
            'note_totale' => ['required', 'numeric', 'min:0', 'max:100'],
            'seuil_reussite' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'date_debut' => ['required', 'date', 'after:now'],
            'date_fin' => ['required', 'date', 'after:date_debut'],
            'instructions' => ['nullable', 'string', 'max:5000'],
            'melanger_questions' => ['boolean'],
            'melanger_reponses' => ['boolean'],
            'afficher_resultats' => ['boolean'],
            'autoriser_retour' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'titre.required' => 'Le titre de l\'examen est obligatoire.',
            'titre.min' => 'Le titre doit contenir au moins 3 caractères.',
            'titre.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            'classe_id.required' => 'Vous devez sélectionner une classe.',
            'classe_id.exists' => 'La classe sélectionnée n\'existe pas.',
            'matiere_id.required' => 'Vous devez sélectionner une matière.',
            'matiere_id.exists' => 'La matière sélectionnée n\'existe pas.',
            'duree.required' => 'La durée de l\'examen est obligatoire.',
            'duree.min' => 'La durée doit être d\'au moins 1 minute.',
            'duree.max' => 'La durée ne peut pas dépasser 480 minutes (8 heures).',
            'date_debut.required' => 'La date de début est obligatoire.',
            'date_debut.after' => 'La date de début doit être dans le futur.',
            'date_fin.required' => 'La date de fin est obligatoire.',
            'date_fin.after' => 'La date de fin doit être après la date de début.',
            'note_totale.required' => 'La note totale est obligatoire.',
            'note_totale.min' => 'La note totale doit être supérieure à 0.',
            'note_totale.max' => 'La note totale ne peut pas dépasser 100.',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'melanger_questions' => $this->has('melanger_questions'),
            'melanger_reponses' => $this->has('melanger_reponses'),
            'afficher_resultats' => $this->has('afficher_resultats'),
            'autoriser_retour' => $this->has('autoriser_retour'),
        ]);
    }
}