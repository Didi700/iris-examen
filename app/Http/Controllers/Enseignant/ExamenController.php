<?php

namespace App\Http\Controllers\Enseignant;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use App\Models\Examen;
use App\Models\LogActivite;
use App\Models\Matiere;
use App\Models\Question;
use App\Mail\ExamenPublieMail;
use App\Notifications\NouvelExamenPublie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ExamenController extends Controller
{
    /**
     * Afficher la liste des examens de l'enseignant
     */
    public function index(Request $request)
    {
        $query = Examen::with(['matiere', 'classe'])
            ->withCount(['questions', 'sessions'])
            ->where('enseignant_id', auth()->id());

        // Filtres
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('titre', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('matiere_id')) {
            $query->where('matiere_id', $request->matiere_id);
        }

        if ($request->filled('classe_id')) {
            $query->where('classe_id', $request->classe_id);
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Tri
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $examens = $query->paginate(15);

        // Récupérer les matières et classes de l'enseignant
        $enseignant = auth()->user();
        
        $classes = Classe::whereHas('enseignants', function($query) use ($enseignant) {
            $query->where('enseignant_id', $enseignant->id);
        })->orderBy('nom')->get();

        $matieres = DB::table('enseignant_classe')
            ->where('enseignant_id', $enseignant->id)
            ->join('matieres', 'enseignant_classe.matiere_id', '=', 'matieres.id')
            ->select('matieres.*')
            ->distinct()
            ->orderBy('matieres.nom')
            ->get();

        // Statistiques
        $stats = [
            'total' => Examen::where('enseignant_id', auth()->id())->count(),
            'brouillons' => Examen::where('enseignant_id', auth()->id())->where('statut', 'brouillon')->count(),
            'publies' => Examen::where('enseignant_id', auth()->id())->where('statut', 'publie')->count(),
            'termines' => Examen::where('enseignant_id', auth()->id())->where('statut', 'termine')->count(),
        ];

        return view('enseignant.examens.index', compact('examens', 'matieres', 'classes', 'stats'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create(Request $request)
    {
        $enseignant = auth()->user();
        
        $classes = Classe::whereHas('enseignants', function($query) use ($enseignant) {
            $query->where('enseignant_id', $enseignant->id);
        })->orderBy('nom')->get();

        $matieres = DB::table('enseignant_classe')
            ->where('enseignant_id', $enseignant->id)
            ->join('matieres', 'enseignant_classe.matiere_id', '=', 'matieres.id')
            ->select('matieres.*')
            ->distinct()
            ->orderBy('matieres.nom')
            ->get();

        $classeId = $request->get('classe_id');
        $matiereId = $request->get('matiere_id');

        return view('enseignant.examens.create', compact('classes', 'matieres', 'classeId', 'matiereId'));
    }

    /**
     * Enregistrer un nouvel examen
     */
    public function store(Request $request)
    {
        $rules = [
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'instructions' => 'nullable|string',
            'matiere_id' => 'required|exists:matieres,id',
            'classe_id' => 'required|exists:classes,id',
            'type_examen' => 'required|in:en_ligne,document',
            'duree_minutes' => 'required|integer|min:1|max:600',
            'date_debut' => 'required|date',

            'nombre_tentatives_max' => 'required|integer|min:1|max:10',
            'seuil_reussite' => 'required|integer|min:0|max:100',
            'questions' => 'required|array|min:1',
            'questions.*' => 'exists:banque_questions,id',
            'points' => 'required|array',
            'points.*' => 'required|numeric|min:0.5',
        ];

        if ($request->type_examen === 'document') {
            $rules['fichier_sujet'] = 'required|file|mimes:pdf|max:10240';
        }

        $validated = $request->validate($rules, [
            'questions.required' => 'Vous devez sélectionner au moins une question.',
            'questions.min' => 'Vous devez sélectionner au moins une question.',
            'titre.required' => 'Le titre est obligatoire.',
            'matiere_id.required' => 'La matière est obligatoire.',
            'classe_id.required' => 'La classe est obligatoire.',
            'fichier_sujet.required' => 'Le fichier PDF de l\'examen est obligatoire pour ce type d\'examen.',
            'fichier_sujet.mimes' => 'Le fichier doit être au format PDF.',
        ]);

        try {
            DB::beginTransaction();

            $noteTotale = 0;
            foreach ($request->questions as $questionId) {
                $noteTotale += floatval($request->points[$questionId] ?? 0);
            }

            $dateDebut = Carbon::parse($request->date_debut);
            $dateFin = $dateDebut->copy()->addMinutes((int) $request->duree_minutes);
            $data = [
                'titre' => $request->titre,
                'description' => $request->description,
                'instructions' => $request->instructions,
                'enseignant_id' => auth()->id(),
                'matiere_id' => $request->matiere_id,
                'classe_id' => $request->classe_id,
                'type_examen' => $request->type_examen,
                'duree_minutes' => $request->duree_minutes,
                'note_totale' => $noteTotale,
                'date_debut' => $dateDebut,
                'date_fin' => $dateFin,
                'nombre_tentatives_max' => $request->nombre_tentatives_max,
                'seuil_reussite' => $request->seuil_reussite,
                'statut' => $request->input('statut', 'brouillon'),
            ];

            if ($request->type_examen === 'en_ligne') {
                $data['melanger_questions'] = $request->has('melanger_questions');
                $data['melanger_reponses'] = $request->has('melanger_reponses');
                $data['afficher_resultats_immediatement'] = $request->has('afficher_resultats_immediatement');
                $data['autoriser_retour_arriere'] = $request->has('autoriser_retour_arriere');
            }

            if ($request->type_examen === 'document' && $request->hasFile('fichier_sujet')) {
                $file = $request->file('fichier_sujet');
                $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                $path = $file->storeAs('examens/sujets', $filename, 'public');
                $data['fichier_sujet_path'] = $path;
            }

            $examen = Examen::create($data);

            foreach ($request->questions as $ordre => $questionId) {
                $examen->questions()->attach($questionId, [
                    'ordre' => $ordre + 1,
                    'points' => floatval($request->points[$questionId] ?? 5),
                    'obligatoire' => true,
                ]);
            }

            LogActivite::create([
                'utilisateur_id' => auth()->id(),
                'action' => 'creation_examen',
                'module' => 'examens',
                'modele' => 'Examen',
                'modele_id' => $examen->id,
                'description' => "Création de l'examen '{$examen->titre}'",
            ]);

            DB::commit();

            return redirect()
                ->route('enseignant.examens.show', $examen->id)
                ->with('success', 'Examen créé avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Erreur création examen', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }

    /**
     * Afficher un examen
     */
    public function show(Examen $examen)
    {
        if ($examen->enseignant_id !== auth()->id()) {
            abort(403, 'Vous n\'avez pas accès à cet examen.');
        }

        $examen->load(['matiere', 'classe', 'questions.reponses']);

        $stats = [
            'nb_questions' => $examen->questions->count(),
            'note_totale_questions' => $examen->questions->sum('pivot.points'),
            'nb_sessions' => $examen->sessions()->count(),
            'nb_sessions_terminees' => $examen->sessions()->whereIn('statut', ['soumis', 'corrige'])->count(),
            'moyenne' => $examen->sessions()->whereNotNull('note_obtenue')->avg('note_obtenue'),
        ];

        return view('enseignant.examens.show', compact('examen', 'stats'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Examen $examen)
    {
        if ($examen->enseignant_id !== auth()->id()) {
            abort(403, 'Vous n\'avez pas accès à cet examen.');
        }

        if (in_array($examen->statut, ['publie', 'termine', 'archive'])) {
            return redirect()
                ->route('enseignant.examens.show', $examen->id)
                ->with('error', 'Impossible de modifier un examen publié, terminé ou archivé.');
        }

        $enseignant = auth()->user();
        
        $classes = Classe::whereHas('enseignants', function($query) use ($enseignant) {
            $query->where('enseignant_id', $enseignant->id);
        })->orderBy('nom')->get();

        $matieres = DB::table('enseignant_classe')
            ->where('enseignant_id', $enseignant->id)
            ->join('matieres', 'enseignant_classe.matiere_id', '=', 'matieres.id')
            ->select('matieres.*')
            ->distinct()
            ->orderBy('matieres.nom')
            ->get();

        return view('enseignant.examens.edit', compact('examen', 'classes', 'matieres'));
    }

    /**
     * Mettre à jour un examen
     */
    public function update(Request $request, Examen $examen)
    {
        if ($examen->enseignant_id !== auth()->id()) {
            abort(403, 'Vous n\'avez pas accès à cet examen.');
        }

        if (in_array($examen->statut, ['publie', 'termine', 'archive'])) {
            return redirect()
                ->route('enseignant.examens.show', $examen->id)
                ->with('error', 'Impossible de modifier un examen publié, terminé ou archivé.');
        }

        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'instructions' => 'nullable|string',
            'matiere_id' => 'required|exists:matieres,id',
            'classe_id' => 'required|exists:classes,id',
            'duree_minutes' => 'required|integer|min:1|max:600',
            'note_totale' => 'required|numeric|min:1|max:1000',
            'date_debut' => 'required|date',
            'nombre_tentatives_max' => 'required|integer|min:1|max:10',
            'seuil_reussite' => 'required|integer|min:0|max:100',
        ]);

        try {
            $dateDebut = Carbon::parse($request->date_debut);
            $dateFin = $dateDebut->copy()->addMinutes($request->duree_minutes);
            $updateData = [
                'titre' => $request->titre,
                'description' => $request->description,
                'instructions' => $request->instructions,
                'matiere_id' => $request->matiere_id,
                'classe_id' => $request->classe_id,
                'duree_minutes' => $request->duree_minutes,
                'note_totale' => $request->note_totale,
                'date_debut' => $dateDebut,
                'date_fin' => $dateFin,
                'nombre_tentatives_max' => $request->nombre_tentatives_max,
                'seuil_reussite' => $request->seuil_reussite,
            ];

            if ($examen->type_examen === 'en_ligne') {
                $updateData['melanger_questions'] = $request->has('melanger_questions');
                $updateData['melanger_reponses'] = $request->has('melanger_reponses');
                $updateData['afficher_resultats_immediatement'] = $request->has('afficher_resultats_immediatement');
                $updateData['autoriser_retour_arriere'] = $request->has('autoriser_retour_arriere');
            }

            $examen->update($updateData);

            LogActivite::create([
                'utilisateur_id' => auth()->id(),
                'action' => 'modification_examen',
                'module' => 'examens',
                'modele' => 'Examen',
                'modele_id' => $examen->id,
                'description' => "Modification de l'examen '{$examen->titre}'",
            ]);

            return redirect()
                ->route('enseignant.examens.show', $examen->id)
                ->with('success', 'Examen modifié avec succès !');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }

    /**
     * Supprimer un examen
     */
    public function destroy(Examen $examen)
    {
        if ($examen->enseignant_id !== auth()->id()) {
            abort(403, 'Vous n\'avez pas accès à cet examen.');
        }

        if ($examen->sessions()->count() > 0) {
            return redirect()
                ->back()
                ->with('error', 'Impossible de supprimer un examen qui a déjà été passé par des étudiants.');
        }

        try {
            $titre = $examen->titre;
            $id = $examen->id;

            if ($examen->type_examen === 'document' && $examen->fichier_sujet_path) {
                Storage::disk('public')->delete($examen->fichier_sujet_path);
            }

            $examen->delete();

            LogActivite::create([
                'utilisateur_id' => auth()->id(),
                'action' => 'suppression_examen',
                'module' => 'examens',
                'modele' => 'Examen',
                'modele_id' => $id,
                'description' => "Suppression de l'examen '{$titre}'",
            ]);

            return redirect()
                ->route('enseignant.examens.index')
                ->with('success', 'Examen supprimé avec succès !');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }

    /**
     * Dupliquer un examen
     */
    public function duplicate(Examen $examen)
    {
        if ($examen->enseignant_id !== auth()->id()) {
            abort(403, 'Vous n\'avez pas accès à cet examen.');
        }

        try {
            DB::beginTransaction();

            $nouvelExamen = $examen->replicate();
            $nouvelExamen->titre = $examen->titre . ' (Copie)';
            $nouvelExamen->statut = 'brouillon';
            
            if ($examen->type_examen === 'document' && $examen->fichier_sujet_path) {
                $oldPath = $examen->fichier_sujet_path;
                $newFilename = time() . '_copie_' . basename($oldPath);
                $newPath = 'examens/sujets/' . $newFilename;
                
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->copy($oldPath, $newPath);
                    $nouvelExamen->fichier_sujet_path = $newPath;
                }
            }
            
            $nouvelExamen->save();

            if ($examen->type_examen === 'en_ligne') {
                foreach ($examen->questions as $question) {
                    $nouvelExamen->questions()->attach($question->id, [
                        'ordre' => $question->pivot->ordre,
                        'points' => $question->pivot->points,
                        'obligatoire' => $question->pivot->obligatoire ?? true,
                    ]);
                }
            }

            DB::commit();

            LogActivite::create([
                'utilisateur_id' => auth()->id(),
                'action' => 'duplication_examen',
                'module' => 'examens',
                'modele' => 'Examen',
                'modele_id' => $nouvelExamen->id,
                'description' => "Duplication de l'examen '{$examen->titre}' vers '{$nouvelExamen->titre}'",
            ]);

            return redirect()
                ->route('enseignant.examens.show', $nouvelExamen->id)
                ->with('success', 'Examen dupliqué avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }

    /**
     * Publier un examen - AVEC NOTIFICATIONS
     */
    public function publier(Examen $examen)
    {
        if ($examen->enseignant_id !== auth()->id()) {
            abort(403, 'Vous n\'avez pas accès à cet examen.');
        }

        if ($examen->type_examen === 'en_ligne') {
            if ($examen->questions->count() === 0) {
                return redirect()
                    ->back()
                    ->with('error', 'Impossible de publier un examen sans questions.');
            }
        } elseif ($examen->type_examen === 'document') {
            if (!$examen->fichier_sujet_path) {
                return redirect()
                    ->back()
                    ->with('error', 'Impossible de publier un examen sans fichier PDF.');
            }
        }

        try {
            DB::beginTransaction();

            $examen->update(['statut' => 'publie']);

            // 🔔 ENVOYER LES NOTIFICATIONS AUX ÉTUDIANTS
            $classe = $examen->classe;
            $etudiants = $classe->etudiants;

            $notificationsEnvoyees = 0;

            foreach ($etudiants as $etudiant) {
                try {
                    $etudiant->notify(new NouvelExamenPublie($examen));
                    $notificationsEnvoyees++;
                } catch (\Exception $e) {
                    Log::error('Erreur envoi notification examen publié', [
                        'etudiant_id' => $etudiant->id,
                        'examen_id' => $examen->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            LogActivite::create([
                'utilisateur_id' => auth()->id(),
                'action' => 'publication_examen',
                'module' => 'examens',
                'modele' => 'Examen',
                'modele_id' => $examen->id,
                'description' => "Publication de l'examen '{$examen->titre}' - {$notificationsEnvoyees} notification(s) envoyée(s)",
            ]);

            DB::commit();

            return redirect()
                ->route('enseignant.examens.show', $examen->id)
                ->with('success', "Examen publié avec succès ! {$notificationsEnvoyees} étudiant(s) notifié(s).");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }

    /**
     * Archiver un examen
     */
    public function archiver(Examen $examen)
    {
        if ($examen->enseignant_id !== auth()->id()) {
            abort(403, 'Vous n\'avez pas accès à cet examen.');
        }

        try {
            $examen->update(['statut' => 'archive']);

            LogActivite::create([
                'utilisateur_id' => auth()->id(),
                'action' => 'archivage_examen',
                'module' => 'examens',
                'modele' => 'Examen',
                'modele_id' => $examen->id,
                'description' => "Archivage de l'examen '{$examen->titre}'",
            ]);

            return redirect()
                ->back()
                ->with('success', 'Examen archivé avec succès !');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }

    public function gererQuestions(Examen $examen)
    {
        if ($examen->enseignant_id !== auth()->id()) {
            abort(403, 'Vous n\'avez pas accès à cet examen.');
        }

        if ($examen->type_examen !== 'en_ligne') {
            return redirect()
                ->route('enseignant.examens.show', $examen->id)
                ->with('error', 'Cette fonctionnalité n\'est disponible que pour les examens en ligne.');
        }

        if (!in_array($examen->statut, ['brouillon'])) {
            return redirect()
                ->route('enseignant.examens.show', $examen->id)
                ->with('error', 'Impossible de modifier les questions d\'un examen publié.');
        }

        $questionsExamen = $examen->questions()
            ->with('reponses')
            ->orderBy('examen_question.ordre')
            ->get();

        $questionsDisponibles = Question::where('enseignant_id', auth()->id())
            ->where('matiere_id', $examen->matiere_id)
            ->where('est_active', true)
            ->whereNotIn('id', $questionsExamen->pluck('id'))
            ->with('reponses')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('enseignant.examens.questions', compact('examen', 'questionsExamen', 'questionsDisponibles'));
    }
}