<?php

namespace App\Http\Controllers\Enseignant;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use App\Models\Matiere;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClasseController extends Controller
{
    /**
     * Liste des classes de l'enseignant avec ses matières
     */
    public function mesClasses()
    {
        $enseignant = auth()->user();

        // Récupérer les classes avec les matières enseignées
        $classes = Classe::whereHas('enseignants', function($query) use ($enseignant) {
            $query->where('enseignant_id', $enseignant->id);
        })
        ->with(['enseignants' => function($query) use ($enseignant) {
            $query->where('enseignant_id', $enseignant->id);
        }])
        ->withCount('etudiants')
        ->get();

        // Grouper par classe avec leurs matières
        $classesAvecMatieres = $classes->map(function($classe) use ($enseignant) {
            // Récupérer les matières que l'enseignant enseigne dans cette classe
            $matieres = DB::table('enseignant_classe')
                ->where('enseignant_id', $enseignant->id)
                ->where('classe_id', $classe->id)
                ->join('matieres', 'enseignant_classe.matiere_id', '=', 'matieres.id')
                ->select('matieres.*')
                ->get();

            $classe->mes_matieres = $matieres;
            
            // Compter les examens pour cette classe
            $classe->nb_examens = \App\Models\Examen::where('classe_id', $classe->id)
                ->where('enseignant_id', $enseignant->id)
                ->count();

            return $classe;
        });

        return view('enseignant.classes.index', compact('classesAvecMatieres'));
    }

    /**
     * Détails d'une classe
     */
    public function show(Classe $classe)
    {
        $enseignant = auth()->user();

        // Vérifier que l'enseignant enseigne dans cette classe
        $enseigneDansClasse = DB::table('enseignant_classe')
            ->where('enseignant_id', $enseignant->id)
            ->where('classe_id', $classe->id)
            ->exists();

        if (!$enseigneDansClasse) {
            abort(403, 'Vous n\'enseignez pas dans cette classe.');
        }

        // Récupérer les matières enseignées dans cette classe
        $matieres = DB::table('enseignant_classe')
            ->where('enseignant_id', $enseignant->id)
            ->where('classe_id', $classe->id)
            ->join('matieres', 'enseignant_classe.matiere_id', '=', 'matieres.id')
            ->select('matieres.*')
            ->get();

        // ✅ CORRECTION : Charger les étudiants avec leur utilisateur ET filtrer ceux qui n'ont pas d'utilisateur
        $etudiants = \App\Models\Etudiant::where('classe_id', $classe->id)   
            ->with('utilisateur')
            ->whereHas('utilisateur') // ✅ Filtrer uniquement ceux qui ont un utilisateur
            ->get();


        // Statistiques
        $stats = [
            'nb_etudiants' => $etudiants->count(),
            'nb_matieres' => $matieres->count(),
            'nb_examens' => \App\Models\Examen::where('classe_id', $classe->id)
                ->where('enseignant_id', $enseignant->id)
                ->count(),
        ];

        return view('enseignant.classes.show', compact('classe', 'matieres', 'etudiants', 'stats'));
    }

    /**
     * Vue d'une classe pour une matière spécifique
     */
    public function showMatiere(Classe $classe, Matiere $matiere)
    {
        $enseignant = auth()->user();

        // Vérifier que l'enseignant enseigne cette matière dans cette classe
        $enseigneMatiere = DB::table('enseignant_classe')
            ->where('enseignant_id', $enseignant->id)
            ->where('classe_id', $classe->id)
            ->where('matiere_id', $matiere->id)
            ->exists();

        if (!$enseigneMatiere) {
            abort(403, 'Vous n\'enseignez pas cette matière dans cette classe.');
        }

        // Récupérer les étudiants (uniquement ceux qui ont un utilisateur lié)
        $etudiants = \App\Models\Etudiant::where('classe_id', $classe->id)
            ->with(['utilisateur', 'sessionsExamen' => function($query) use ($matiere) {
                $query->whereHas('examen', function($q) use ($matiere) {
                    $q->where('matiere_id', $matiere->id);
                });
            }])
            ->whereHas('utilisateur')
            ->get();

        // Récupérer les examens
        $examens = \App\Models\Examen::where('classe_id', $classe->id)
            ->where('matiere_id', $matiere->id)
            ->where('enseignant_id', $enseignant->id)
            ->withCount('sessions')
            ->orderBy('date_debut', 'desc')
            ->get();

        // Calculer la moyenne de la classe pour cette matière
        $moyenneClasse = \App\Models\SessionExamen::whereHas('examen', function($query) use ($classe, $matiere, $enseignant) {
            $query->where('classe_id', $classe->id)
                ->where('matiere_id', $matiere->id)
                ->where('enseignant_id', $enseignant->id);
        })
        ->whereNotNull('note_obtenue')
        ->avg('note_obtenue');

        $stats = [
            'nb_etudiants' => $etudiants->count(),
            'nb_examens' => $examens->count(),
            'moyenne_classe' => $moyenneClasse,
            'nb_sessions' => $examens->sum('sessions_count'),
        ];

        return view('enseignant.classes.matiere', compact('classe', 'matiere', 'etudiants', 'examens', 'stats'));
    }
}