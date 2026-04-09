<?php

namespace App\Http\Controllers\Enseignant;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Reponse;
use App\Models\Matiere;
use App\Models\LogActivite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QuestionController extends Controller
{
    /**
     * Afficher la liste des questions
     */
    public function index(Request $request)
    {
        $query = Question::where('enseignant_id', auth()->id())
            ->with(['matiere', 'reponses']);

        // Filtre par recherche
        if ($request->filled('search')) {
            $query->where('enonce', 'LIKE', '%' . $request->search . '%');
        }

        // Filtre par type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filtre par difficulté
        if ($request->filled('difficulte')) {
            $query->where('difficulte', $request->difficulte);
        }

        // Filtre par matière
        if ($request->filled('matiere_id')) {
            $query->where('matiere_id', $request->matiere_id);
        }

        $questions = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('enseignant.questions.index', compact('questions'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        return view('enseignant.questions.create');
    }

    /**
     * Enregistrer une nouvelle question
     */
    public function store(Request $request)
    {
        // Log des données reçues pour debug
        Log::info('Données de création de question:', $request->all());

        // Validation de base
        $validated = $request->validate([
            'enonce' => 'required|string|max:1000',
            'type' => 'required|in:choix_unique,choix_multiple,vrai_faux,reponse_courte',
            'matiere_id' => 'required|exists:matieres,id',
            'difficulte' => 'required|in:facile,moyen,difficile',
            'explication' => 'nullable|string|max:2000',
        ], [
            'enonce.required' => 'L\'énoncé de la question est obligatoire.',
            'type.required' => 'Le type de question est obligatoire.',
            'matiere_id.required' => 'La matière est obligatoire.',
            'difficulte.required' => 'La difficulté est obligatoire.',
        ]);

        // Validation spécifique pour les réponses (sauf réponse courte)
        if ($request->type !== 'reponse_courte') {
            $request->validate([
                'reponses' => 'required|array|min:2',
                'reponses.*.texte' => 'required|string|max:500',
            ], [
                'reponses.required' => 'Vous devez fournir au moins 2 réponses.',
                'reponses.min' => 'Vous devez fournir au moins 2 réponses.',
                'reponses.*.texte.required' => 'Le texte de chaque réponse est obligatoire.',
            ]);
        }

        try {
            DB::beginTransaction();

            // Créer la question
            $question = Question::create([
                'enonce' => $validated['enonce'],
                'type' => $validated['type'],
                'enseignant_id' => auth()->id(),
                'matiere_id' => $validated['matiere_id'],
                'difficulte' => $validated['difficulte'],
                'explication' => $validated['explication'] ?? null,
                'est_active' => true,
            ]);

            Log::info('Question créée avec ID:', ['id' => $question->id]);

            // Créer les réponses si nécessaire
            if ($request->type !== 'reponse_courte' && $request->has('reponses')) {
                $hasCorrectAnswer = false;

                foreach ($request->reponses as $index => $reponseData) {
                    if (!empty($reponseData['texte'])) {
                        $estCorrecte = isset($reponseData['est_correcte']) ? true : false;
                        
                        if ($estCorrecte) {
                            $hasCorrectAnswer = true;
                        }

                        Reponse::create([
                            'question_id' => $question->id,
                            'texte' => $reponseData['texte'],
                            'est_correcte' => $estCorrecte,
                            'ordre' => $index + 1,
                        ]);

                        Log::info('Réponse créée:', [
                            'texte' => $reponseData['texte'],
                            'est_correcte' => $estCorrecte
                        ]);
                    }
                }

                // Vérifier qu'il y a au moins une réponse correcte
                if (!$hasCorrectAnswer) {
                    DB::rollBack();
                    return redirect()
                        ->back()
                        ->withInput()
                        ->withErrors(['reponses' => 'Vous devez marquer au moins une réponse comme correcte.']);
                }
            }

            // Logger l'action
            LogActivite::create([
                'utilisateur_id' => auth()->id(),
                'action' => 'creation_question',
                'module' => 'questions',
                'modele' => 'Question',
                'modele_id' => $question->id,
                'description' => "Création de la question '{$question->enonce}'",
            ]);

            DB::commit();

            return redirect()
                ->route('enseignant.questions.show', $question->id)
                ->with('success', 'Question créée avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur création question:', ['error' => $e->getMessage()]);
            
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Une erreur est survenue : ' . $e->getMessage()]);
        }
    }

    /**
     * Afficher une question
     */
    public function show(Question $question)
    {
        // Vérifier que l'enseignant est propriétaire
        if ($question->enseignant_id !== auth()->id()) {
            abort(403, 'Vous n\'avez pas accès à cette question.');
        }

        $question->load(['matiere', 'reponses']);

        // Compter le nombre d'examens utilisant cette question
        $nbExamens = $question->examens()->count();

        return view('enseignant.questions.show', compact('question', 'nbExamens'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Question $question)
    {
        // Vérifier que l'enseignant est propriétaire
        if ($question->enseignant_id !== auth()->id()) {
            abort(403, 'Vous n\'avez pas accès à cette question.');
        }

        $question->load('reponses');

        return view('enseignant.questions.edit', compact('question'));
    }

    /**
     * Mettre à jour une question
     */
    public function update(Request $request, Question $question)
    {
        // Vérifier que l'enseignant est propriétaire
        if ($question->enseignant_id !== auth()->id()) {
            abort(403, 'Vous n\'avez pas accès à cette question.');
        }

        // Validation de base
        $validated = $request->validate([
            'enonce' => 'required|string|max:1000',
            'matiere_id' => 'required|exists:matieres,id',
            'difficulte' => 'required|in:facile,moyen,difficile',
            'explication' => 'nullable|string|max:2000',
        ], [
            'enonce.required' => 'L\'énoncé de la question est obligatoire.',
            'matiere_id.required' => 'La matière est obligatoire.',
            'difficulte.required' => 'La difficulté est obligatoire.',
        ]);

        // Validation spécifique pour les réponses (sauf réponse courte)
        if ($question->type !== 'reponse_courte') {
            $request->validate([
                'reponses' => 'required|array|min:2',
                'reponses.*.texte' => 'required|string|max:500',
            ], [
                'reponses.required' => 'Vous devez fournir au moins 2 réponses.',
                'reponses.min' => 'Vous devez fournir au moins 2 réponses.',
            ]);
        }

        try {
            DB::beginTransaction();

            // Mettre à jour la question
            $question->update([
                'enonce' => $validated['enonce'],
                'matiere_id' => $validated['matiere_id'],
                'difficulte' => $validated['difficulte'],
                'explication' => $validated['explication'] ?? null,
            ]);

            // Gérer les réponses
            if ($question->type !== 'reponse_courte' && $request->has('reponses')) {
                $reponseIds = [];
                $hasCorrectAnswer = false;

                foreach ($request->reponses as $index => $reponseData) {
                    if (!empty($reponseData['texte'])) {
                        $estCorrecte = isset($reponseData['est_correcte']) ? true : false;
                        
                        if ($estCorrecte) {
                            $hasCorrectAnswer = true;
                        }

                        if (isset($reponseData['id']) && !empty($reponseData['id'])) {
                            // Mise à jour d'une réponse existante
                            $reponse = Reponse::find($reponseData['id']);
                            if ($reponse && $reponse->question_id === $question->id) {
                                $reponse->update([
                                    'texte' => $reponseData['texte'],
                                    'est_correcte' => $estCorrecte,
                                    'ordre' => $index + 1,
                                ]);
                                $reponseIds[] = $reponse->id;
                            }
                        } else {
                            // Création d'une nouvelle réponse
                            $reponse = Reponse::create([
                                'question_id' => $question->id,
                                'texte' => $reponseData['texte'],
                                'est_correcte' => $estCorrecte,
                                'ordre' => $index + 1,
                            ]);
                            $reponseIds[] = $reponse->id;
                        }
                    }
                }

                // Supprimer les réponses non présentes
                Reponse::where('question_id', $question->id)
                    ->whereNotIn('id', $reponseIds)
                    ->delete();

                // Vérifier qu'il y a au moins une réponse correcte
                if (!$hasCorrectAnswer) {
                    DB::rollBack();
                    return redirect()
                        ->back()
                        ->withInput()
                        ->withErrors(['reponses' => 'Vous devez marquer au moins une réponse comme correcte.']);
                }
            }

            // Logger l'action
            LogActivite::create([
                'utilisateur_id' => auth()->id(),
                'action' => 'modification_question',
                'module' => 'questions',
                'modele' => 'Question',
                'modele_id' => $question->id,
                'description' => "Modification de la question '{$question->enonce}'",
            ]);

            DB::commit();

            return redirect()
                ->route('enseignant.questions.show', $question->id)
                ->with('success', 'Question modifiée avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur modification question:', ['error' => $e->getMessage()]);
            
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Une erreur est survenue : ' . $e->getMessage()]);
        }
    }

    /**
     * Supprimer une question
     */
    public function destroy(Question $question)
    {
        // Vérifier que l'enseignant est propriétaire
        if ($question->enseignant_id !== auth()->id()) {
            abort(403, 'Vous n\'avez pas accès à cette question.');
        }

        // Vérifier que la question n'est pas utilisée dans des examens
        if ($question->examens()->count() > 0) {
            return redirect()
                ->back()
                ->withErrors(['error' => 'Impossible de supprimer une question utilisée dans des examens.']);
        }

        try {
            $enonce = $question->enonce;
            $id = $question->id;

            // Supprimer les réponses
            $question->reponses()->delete();

            // Supprimer la question
            $question->delete();

            // Logger l'action
            LogActivite::create([
                'utilisateur_id' => auth()->id(),
                'action' => 'suppression_question',
                'module' => 'questions',
                'modele' => 'Question',
                'modele_id' => $id,
                'description' => "Suppression de la question '{$enonce}'",
            ]);

            return redirect()
                ->route('enseignant.questions.index')
                ->with('success', 'Question supprimée avec succès !');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['error' => 'Une erreur est survenue : ' . $e->getMessage()]);
        }
    }

    /**
     * Dupliquer une question
     */
    public function duplicate(Question $question)
    {
        // Vérifier que l'enseignant est propriétaire
        if ($question->enseignant_id !== auth()->id()) {
            abort(403, 'Vous n\'avez pas accès à cette question.');
        }

        try {
            DB::beginTransaction();

            // Créer une copie de la question
            $nouvelleQuestion = $question->replicate();
            $nouvelleQuestion->enonce = $question->enonce . ' (Copie)';
            $nouvelleQuestion->save();

            // Copier les réponses
            foreach ($question->reponses as $reponse) {
                $nouvelleReponse = $reponse->replicate();
                $nouvelleReponse->question_id = $nouvelleQuestion->id;
                $nouvelleReponse->save();
            }

            // Logger l'action
            LogActivite::create([
                'utilisateur_id' => auth()->id(),
                'action' => 'duplication_question',
                'module' => 'questions',
                'modele' => 'Question',
                'modele_id' => $nouvelleQuestion->id,
                'description' => "Duplication de la question '{$question->enonce}'",
            ]);

            DB::commit();

            return redirect()
                ->route('enseignant.questions.edit', $nouvelleQuestion->id)
                ->with('success', 'Question dupliquée avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withErrors(['error' => 'Une erreur est survenue : ' . $e->getMessage()]);
        }
    }

    /**
     * Activer/Désactiver une question
     */
    public function toggleActive(Question $question)
    {
        // Vérifier que l'enseignant est propriétaire
        if ($question->enseignant_id !== auth()->id()) {
            abort(403, 'Vous n\'avez pas accès à cette question.');
        }

        try {
            $question->est_active = !$question->est_active;
            $question->save();

            $statut = $question->est_active ? 'activée' : 'désactivée';

            // Logger l'action
            LogActivite::create([
                'utilisateur_id' => auth()->id(),
                'action' => 'toggle_question',
                'module' => 'questions',
                'modele' => 'Question',
                'modele_id' => $question->id,
                'description' => "Question '{$question->enonce}' {$statut}",
            ]);

            return redirect()
                ->back()
                ->with('success', "Question {$statut} avec succès !");

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['error' => 'Une erreur est survenue : ' . $e->getMessage()]);
        }
    }
}