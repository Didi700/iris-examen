<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExamenRequest extends FormRequest
{
    public function authorize(): bool
    {
        $examen = $this->route('examen');
        return auth()->check() 
            && auth()->user()->estEnseignant() 
            && $examen->enseignant_id === auth()->id();
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
            'date_debut' => ['required', 'date'],
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
            'date_fin.after' => 'La date de fin doit être après la date de début.',
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