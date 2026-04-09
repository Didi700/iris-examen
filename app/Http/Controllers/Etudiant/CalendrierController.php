<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\Examen;
use App\Models\SessionExamen;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendrierController extends Controller
{
    /**
     * Afficher le calendrier des examens
     */
    public function index(Request $request)
    {
        $etudiant = auth()->user()->etudiant;
        
        if (!$etudiant) {
            return redirect()->route('etudiant.dashboard')
                ->with('error', 'Profil étudiant non trouvé.');
        }

        // Récupérer le mois et l'année depuis la requête (par défaut = mois actuel)
        $mois = $request->get('mois', now()->month);
        $annee = $request->get('annee', now()->year);

        // Créer la date du premier jour du mois
        $dateDebut = Carbon::createFromDate($annee, $mois, 1)->startOfMonth();
        $dateFin = $dateDebut->copy()->endOfMonth();

        // Récupérer les examens de la classe de l'étudiant pour ce mois
        $examens = Examen::where('classe_id', $etudiant->classe_id)
            ->where('statut', 'publie')
            ->whereBetween('date_debut', [$dateDebut, $dateFin])
            ->with(['matiere', 'classe'])
            ->orderBy('date_debut')
            ->get();

        // Récupérer les sessions de l'étudiant
        $sessions = SessionExamen::where('etudiant_id', $etudiant->id)
            ->whereIn('examen_id', $examens->pluck('id'))
            ->get()
            ->keyBy('examen_id');

        // Créer un tableau d'événements pour le calendrier
        $evenements = [];
        foreach ($examens as $examen) {
            $session = $sessions->get($examen->id);
            
            // Déterminer le statut
            if ($session) {
                if ($session->statut === 'corrige') {
                    $statut = 'termine';
                    $couleur = 'gray';
                } elseif ($session->statut === 'soumis') {
                    $statut = 'soumis';
                    $couleur = 'blue';
                } elseif ($session->statut === 'en_cours') {
                    $statut = 'en_cours';
                    $couleur = 'orange';
                } else {
                    $statut = 'non_commence';
                    $couleur = 'green';
                }
            } else {
                if ($examen->date_debut->isPast() && $examen->date_fin->isPast()) {
                    $statut = 'manque';
                    $couleur = 'red';
                } elseif ($examen->date_debut->isPast() && $examen->date_fin->isFuture()) {
                    $statut = 'en_cours';
                    $couleur = 'orange';
                } else {
                    $statut = 'a_venir';
                    $couleur = 'green';
                }
            }

            $evenements[] = [
                'id' => $examen->id,
                'titre' => $examen->titre,
                'matiere' => $examen->matiere->nom,
                'date_debut' => $examen->date_debut->format('Y-m-d H:i'),
                'date_fin' => $examen->date_fin->format('Y-m-d H:i'),
                'duree' => $examen->duree_minutes,
                'statut' => $statut,
                'couleur' => $couleur,
                'url' => route('etudiant.examens.show', $examen->id),
            ];
        }

        // Navigation mois précédent/suivant
        $moisPrecedent = $dateDebut->copy()->subMonth();
        $moisSuivant = $dateDebut->copy()->addMonth();

        return view('etudiant.calendrier', compact(
            'evenements',
            'dateDebut',
            'dateFin',
            'moisPrecedent',
            'moisSuivant'
        ));
    }

    /**
     * API pour récupérer les examens au format JSON (pour AJAX)
     */
    public function getExamens(Request $request)
    {
        $etudiant = auth()->user()->etudiant;
        
        if (!$etudiant) {
            return response()->json(['error' => 'Étudiant non trouvé'], 404);
        }

        $debut = $request->get('start');
        $fin = $request->get('end');

        $examens = Examen::where('classe_id', $etudiant->classe_id)
            ->where('statut', 'publie')
            ->whereBetween('date_debut', [$debut, $fin])
            ->with(['matiere'])
            ->get();

        $sessions = SessionExamen::where('etudiant_id', $etudiant->id)
            ->whereIn('examen_id', $examens->pluck('id'))
            ->get()
            ->keyBy('examen_id');

        $events = [];
        foreach ($examens as $examen) {
            $session = $sessions->get($examen->id);
            
            if ($session) {
                if ($session->statut === 'corrige') {
                    $color = '#6B7280'; // gray
                } elseif ($session->statut === 'soumis') {
                    $color = '#3B82F6'; // blue
                } else {
                    $color = '#F59E0B'; // orange
                }
            } else {
                if ($examen->date_fin->isPast()) {
                    $color = '#EF4444'; // red
                } else {
                    $color = '#10B981'; // green
                }
            }

            $events[] = [
                'id' => $examen->id,
                'title' => $examen->titre . ' - ' . $examen->matiere->nom,
                'start' => $examen->date_debut->toIso8601String(),
                'end' => $examen->date_fin->toIso8601String(),
                'backgroundColor' => $color,
                'borderColor' => $color,
                'url' => route('etudiant.examens.show', $examen->id),
            ];
        }

        return response()->json($events);
    }
}