<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogActivite;
use App\Models\Matiere;
use App\Models\Question;
use App\Models\Reponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    /**
     * Afficher la liste des questions
     */
    public function index(Request $request)
    {
        $query = Question::with(['matiere', 'createur', 'reponses']);

        // Filtres
        if ($request->filled('matiere_id')) {
            $query->where('matiere_id', $request->matiere_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('difficulte')) {
            $query->where('difficulte', $request->difficulte);
        }

        if ($request->filled('search')) {
            $query->where('enonce', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('est_active')) {
            $query->where('est_active', $request->est_active === '1');
        }

        // Tri
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $questions = $query->paginate(15);
        $matieres = Matiere::orderBy('nom')->get();

        // Statistiques
        $stats = [
            'total' => Question::count(),
            'actives' => Question::where('est_active', true)->count(),
            'qcm' => Question::whereIn('type', ['qcm_simple', 'qcm_multiple'])->count(),
            'texte_libre' => Question::where('type', 'texte_libre')->count(),
        ];

        return view('admin.questions.index', compact('questions', 'matieres', 'stats'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $matieres = Matiere::orderBy('nom')->get();
        return view('admin.questions.create', compact('matieres'));
    }

    /**
     * Enregistrer une nouvelle question
     */
    public function store(Request $request)
    {
        $request->validate([
            'matiere_id' => 'required|exists:matieres,id',
            'type' => 'required|in:qcm_simple,qcm_multiple,vrai_faux,texte_libre',
            'enonce' => 'required|string|min:10',
            'explication' => 'nullable|string',
            'difficulte' => 'required|in:facile,moyen,difficile',
            'points' => 'required|numeric|min:0.25|max:100',
            'tags' => 'nullable|string',
            'reponses' => 'required_if:type,qcm_simple,qcm_multiple,vrai_faux|array|min:2',
            'reponses.*.texte' => 'required|string',
            'reponses.*.est_correcte' => 'nullable|boolean',
        ], [
            'matiere_id.required' => 'Veuillez sélectionner une matière.',
            'type.required' => 'Le type de question est obligatoire.',
            'enonce.required' => 'L\'énoncé de la question est obligatoire.',
            'enonce.min' => 'L\'énoncé doit contenir au moins 10 caractères.',
            'difficulte.required' => 'Le niveau de difficulté est obligatoire.',
            'points.required' => 'Le nombre de points est obligatoire.',
            'reponses.required_if' => 'Veuillez ajouter au moins 2 réponses.',
            'reponses.min' => 'Veuillez ajouter au moins 2 réponses.',
        ]);

        DB::beginTransaction();
        try {
            // Créer la question
            $question = Question::create([
                'matiere_id' => $request->matiere_id,
                'createur_id' => auth()->id(),
                'type' => $request->type,
                'enonce' => $request->enonce,
                'explication' => $request->explication,
                'difficulte' => $request->difficulte,
                'points' => $request->points,
                'tags' => $request->tags,
                'est_active' => $request->has('est_active'),
            ]);

            // Créer les réponses (sauf pour texte libre)
            if ($request->type !== 'texte_libre' && $request->has('reponses')) {
                foreach ($request->reponses as $ordre => $reponseData) {
                    if (!empty($reponseData['texte'])) {
                        Reponse::create([
                            'question_id' => $question->id,
                            'texte' => $reponseData['texte'],
                            'est_correcte' => isset($reponseData['est_correcte']) && $reponseData['est_correcte'],
                            'ordre' => $ordre,
                        ]);
                    }
                }
            }

            // Vérifier qu'il y a au moins une bonne réponse (sauf texte libre)
            if ($request->type !== 'texte_libre') {
                $nbBonnesReponses = $question->reponsesCorrectes()->count();
                if ($nbBonnesReponses === 0) {
                    DB::rollBack();
                    return redirect()
                        ->back()
                        ->withInput()
                        ->with('error', 'Vous devez sélectionner au moins une bonne réponse.');
                }

                // Pour QCM simple, vérifier qu'il n'y a qu'une seule bonne réponse
                if ($request->type === 'qcm_simple' && $nbBonnesReponses > 1) {
                    DB::rollBack();
                    return redirect()
                        ->back()
                        ->withInput()
                        ->with('error', 'Un QCM à réponse unique ne peut avoir qu\'une seule bonne réponse.');
                }
            }

            // Logger l'action
            LogActivite::log(
                'creation_question',
                'questions',
                "Création de la question : {$question->enonce}",
                ['question_id' => $question->id]
            );

            DB::commit();

            return redirect()
                ->route('admin.questions.show', $question->id)
                ->with('success', 'Question créée avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la création : ' . $e->getMessage());
        }
    }

    /**
     * Afficher une question
     */
    public function show(Question $question)
    {
        $question->load(['matiere', 'createur', 'reponses']);
        return view('admin.questions.show', compact('question'));
    }

    /**
     * Afficher le formulaire de modification
     */
    public function edit(Question $question)
    {
        $question->load('reponses');
        $matieres = Matiere::orderBy('nom')->get();
        return view('admin.questions.edit', compact('question', 'matieres'));
    }

    /**
     * Mettre à jour une question
     */
    public function update(Request $request, Question $question)
    {
        $request->validate([
            'matiere_id' => 'required|exists:matieres,id',
            'type' => 'required|in:qcm_simple,qcm_multiple,vrai_faux,texte_libre',
            'enonce' => 'required|string|min:10',
            'explication' => 'nullable|string',
            'difficulte' => 'required|in:facile,moyen,difficile',
            'points' => 'required|numeric|min:0.25|max:100',
            'tags' => 'nullable|string',
            'reponses' => 'required_if:type,qcm_simple,qcm_multiple,vrai_faux|array|min:2',
            'reponses.*.texte' => 'required|string',
            'reponses.*.est_correcte' => 'nullable|boolean',
        ]);

        DB::beginTransaction();
        try {
            // Mettre à jour la question
            $question->update([
                'matiere_id' => $request->matiere_id,
                'type' => $request->type,
                'enonce' => $request->enonce,
                'explication' => $request->explication,
                'difficulte' => $request->difficulte,
                'points' => $request->points,
                'tags' => $request->tags,
                'est_active' => $request->has('est_active'),
            ]);

            // Supprimer les anciennes réponses
            $question->reponses()->delete();

            // Créer les nouvelles réponses (sauf pour texte libre)
            if ($request->type !== 'texte_libre' && $request->has('reponses')) {
                foreach ($request->reponses as $ordre => $reponseData) {
                    if (!empty($reponseData['texte'])) {
                        Reponse::create([
                            'question_id' => $question->id,
                            'texte' => $reponseData['texte'],
                            'est_correcte' => isset($reponseData['est_correcte']) && $reponseData['est_correcte'],
                            'ordre' => $ordre,
                        ]);
                    }
                }
            }

            // Vérifier qu'il y a au moins une bonne réponse (sauf texte libre)
            if ($request->type !== 'texte_libre') {
                $nbBonnesReponses = $question->reponsesCorrectes()->count();
                if ($nbBonnesReponses === 0) {
                    DB::rollBack();
                    return redirect()
                        ->back()
                        ->withInput()
                        ->with('error', 'Vous devez sélectionner au moins une bonne réponse.');
                }

                // Pour QCM simple, vérifier qu'il n'y a qu'une seule bonne réponse
                if ($request->type === 'qcm_simple' && $nbBonnesReponses > 1) {
                    DB::rollBack();
                    return redirect()
                        ->back()
                        ->withInput()
                        ->with('error', 'Un QCM à réponse unique ne peut avoir qu\'une seule bonne réponse.');
                }
            }

            // Logger l'action
            LogActivite::log(
                'modification_question',
                'questions',
                "Modification de la question : {$question->enonce}",
                ['question_id' => $question->id]
            );

            DB::commit();

            return redirect()
                ->route('admin.questions.show', $question->id)
                ->with('success', 'Question modifiée avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la modification : ' . $e->getMessage());
        }
    }

    /**
     * Supprimer une question
     */
    public function destroy(Question $question)
    {
        try {
            $enonce = $question->enonce;
            $question->delete();

            // Logger l'action
            LogActivite::log(
                'suppression_question',
                'questions',
                "Suppression de la question : {$enonce}",
                ['question_id' => $question->id]
            );

            return redirect()
                ->route('admin.questions.index')
                ->with('success', 'Question supprimée avec succès !');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Une erreur est survenue lors de la suppression : ' . $e->getMessage());
        }
    }

    /**
     * Dupliquer une question
     */
    public function duplicate(Question $question)
    {
        DB::beginTransaction();
        try {
            // Créer une copie de la question
            $nouvelleQuestion = $question->replicate();
            $nouvelleQuestion->enonce = $question->enonce . ' (Copie)';
            $nouvelleQuestion->createur_id = auth()->id();
            $nouvelleQuestion->nb_utilisations = 0;
            $nouvelleQuestion->taux_reussite = null;
            $nouvelleQuestion->save();

            // Copier les réponses
            foreach ($question->reponses as $reponse) {
                $nouvelleReponse = $reponse->replicate();
                $nouvelleReponse->question_id = $nouvelleQuestion->id;
                $nouvelleReponse->save();
            }

            // Logger l'action
            LogActivite::log(
                'duplication_question',
                'questions',
                "Duplication de la question : {$question->enonce}",
                ['question_id' => $question->id, 'nouvelle_question_id' => $nouvelleQuestion->id]
            );

            DB::commit();

            return redirect()
                ->route('admin.questions.edit', $nouvelleQuestion->id)
                ->with('success', 'Question dupliquée avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Une erreur est survenue lors de la duplication : ' . $e->getMessage());
        }
    }

    /**
     * Activer/Désactiver une question
     */
    public function toggleActive(Question $question)
    {
        try {
            $question->est_active = !$question->est_active;
            $question->save();

            $statut = $question->est_active ? 'activée' : 'désactivée';

            // Logger l'action
            LogActivite::log(
                'toggle_question',
                'questions',
                "Question {$statut} : {$question->enonce}",
                ['question_id' => $question->id, 'est_active' => $question->est_active]
            );

            return redirect()
                ->back()
                ->with('success', "Question {$statut} avec succès !");

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }
}