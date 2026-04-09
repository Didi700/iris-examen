<?php

namespace App\Repositories;

use App\Models\Examen;
use Illuminate\Support\Facades\DB;

class ExamenRepository
{
    /**
     * Récupérer les examens avec toutes les relations nécessaires
     */
    public function getExamensWithRelations($query)
    {
        return $query->with([
            'matiere:id,nom,code',
            'classe:id,nom',
            'enseignant:id,nom,prenom,email',
            'questions' => function($q) {
                $q->select('id', 'examen_id', 'intitule', 'type', 'points')
                  ->orderBy('ordre');
            },
            'questions.reponses_possibles:id,question_id,texte,est_correcte'
        ]);
    }

    /**
     * Récupérer les examens à venir pour un étudiant (optimisé)
     */
    public function getExamensAVenir($etudiantId, $limit = 5)
    {
        return Examen::select([
                'examens.id',
                'examens.titre',
                'examens.description',
                'examens.date_debut',
                'examens.date_fin',
                'examens.duree',
                'examens.matiere_id',
                'examens.classe_id'
            ])
            ->where('examens.statut', 'publie')
            ->where('examens.date_debut', '>', now())
            ->whereHas('classe', function($query) use ($etudiantId) {
                $query->whereHas('etudiants', function($q) use ($etudiantId) {
                    $q->where('utilisateurs.id', $etudiantId);
                });
            })
            ->with(['matiere:id,nom', 'classe:id,nom'])
            ->orderBy('examens.date_debut', 'asc')
            ->limit($limit)
            ->get();
    }

    /**
     * Compter les examens à venir (optimisé)
     */
    public function countExamensAVenir($etudiantId)
    {
        return Examen::where('statut', 'publie')
            ->where('date_debut', '>', now())
            ->whereHas('classe.etudiants', function($q) use ($etudiantId) {
                $q->where('utilisateurs.id', $etudiantId);
            })
            ->count();
    }
}