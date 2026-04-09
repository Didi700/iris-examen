<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        $question = $this->route('question');
        return auth()->check() 
            && auth()->user()->estEnseignant() 
            && $question->enseignant_id === auth()->id();
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
            'reponses.required' => 'Vous devez ajouter au moins 2 réponses.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->has('reponses') && is_array($this->reponses)) {
                $hasCorrect = collect($this->reponses)
                    ->pluck('est_correcte')
                    ->contains(true);

                if (!$hasCorrect) {
                    $validator->errors()->add('reponses', 'Au moins une réponse doit être correcte.');
                }
            }

            if (in_array($this->type, ['choix_unique', 'vrai_faux']) && $this->has('reponses')) {
                $correctCount = collect($this->reponses)->where('est_correcte', true)->count();
                if ($correctCount > 1) {
                    $validator->errors()->add('reponses', 'Une seule réponse peut être correcte pour ce type.');
                }
            }
        });
    }
}