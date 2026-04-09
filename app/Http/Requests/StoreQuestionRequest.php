<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->estEnseignant();
    }

    public function rules(): array
    {
        $rules = [
            'texte' => ['required', 'string', 'min:10', 'max:5000'],
            'type' => ['required', 'in:choix_unique,choix_multiple,vrai_faux,ouverte'],
            'matiere_id' => ['required', 'exists:matieres,id'],
            'difficulte' => ['required', 'in:facile,moyen,difficile'],
            'points' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'explication' => ['nullable', 'string', 'max:2000'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ];

        // Validation des réponses selon le type
        if (in_array($this->type, ['choix_unique', 'choix_multiple', 'vrai_faux'])) {
            $rules['reponses'] = ['required', 'array', 'min:2'];
            $rules['reponses.*.texte'] = ['required', 'string', 'max:500'];
            $rules['reponses.*.est_correcte'] = ['required', 'boolean'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'texte.required' => 'Le texte de la question est obligatoire.',
            'texte.min' => 'La question doit contenir au moins 10 caractères.',
            'texte.max' => 'La question ne peut pas dépasser 5000 caractères.',
            'type.required' => 'Le type de question est obligatoire.',
            'type.in' => 'Le type de question n\'est pas valide.',
            'matiere_id.required' => 'Vous devez sélectionner une matière.',
            'matiere_id.exists' => 'La matière sélectionnée n\'existe pas.',
            'reponses.required' => 'Vous devez ajouter au moins 2 réponses.',
            'reponses.min' => 'Vous devez ajouter au moins 2 réponses.',
            'reponses.*.texte.required' => 'Le texte de chaque réponse est obligatoire.',
            'reponses.*.est_correcte.required' => 'Vous devez indiquer si la réponse est correcte.',
            'image.image' => 'Le fichier doit être une image.',
            'image.mimes' => 'L\'image doit être au format JPEG, PNG, JPG ou GIF.',
            'image.max' => 'L\'image ne peut pas dépasser 2 Mo.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Vérifier qu'il y a au moins une bonne réponse
            if ($this->has('reponses') && is_array($this->reponses)) {
                $hasCorrect = collect($this->reponses)
                    ->pluck('est_correcte')
                    ->contains(true);

                if (!$hasCorrect) {
                    $validator->errors()->add(
                        'reponses',
                        'Au moins une réponse doit être marquée comme correcte.'
                    );
                }
            }

            // Pour choix unique et vrai/faux, une seule réponse correcte
            if (in_array($this->type, ['choix_unique', 'vrai_faux']) && $this->has('reponses')) {
                $correctCount = collect($this->reponses)
                    ->where('est_correcte', true)
                    ->count();

                if ($correctCount > 1) {
                    $validator->errors()->add(
                        'reponses',
                        'Pour ce type de question, une seule réponse peut être correcte.'
                    );
                }
            }
        });
    }
}