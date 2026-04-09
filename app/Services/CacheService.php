<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\Examen;
use App\Models\SessionExamen;

class CacheService
{
    /**
     * Durée du cache en minutes
     */
    const CACHE_DURATION = 60; // 1 heure

    /**
     * Cache des examens à venir pour un étudiant
     */
    public function getExamensAVenir($etudiantId)
    {
        $cacheKey = "examens_a_venir_{$etudiantId}";
        
        return Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($etudiantId) {
            return Examen::where('statut', 'publie')
                ->where('date_debut', '>', now())
                ->whereHas('classe', function($query) use ($etudiantId) {
                    $query->whereHas('etudiants', function($q) use ($etudiantId) {
                        $q->where('utilisateurs.id', $etudiantId);
                    });
                })
                ->with(['matiere', 'classe'])
                ->orderBy('date_debut', 'asc')
                ->limit(5)
                ->get();
        });
    }

    /**
     * Cache des statistiques de l'étudiant
     */
    public function getStatistiquesEtudiant($etudiantId)
    {
        $cacheKey = "stats_etudiant_{$etudiantId}";
        
        return Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($etudiantId) {
            $sessions = SessionExamen::where('etudiant_id', $etudiantId)
                ->whereIn('statut', ['termine', 'corrige', 'soumis'])
                ->whereNotNull('note_obtenue')
                ->get();

            if ($sessions->isEmpty()) {
                return [
                    'total_examens' => 0,
                    'moyenne_generale' => 0,
                    'taux_reussite' => 0,
                ];
            }

            $total = 0;
            $reussis = 0;

            foreach ($sessions as $session) {
                $noteMax = $session->note_maximale ?? $session->examen->note_totale ?? 20;
                if ($noteMax > 0) {
                    $noteSur20 = ($session->note_obtenue / $noteMax) * 20;
                    $total += $noteSur20;
                    if ($noteSur20 >= 10) {
                        $reussis++;
                    }
                }
            }

            return [
                'total_examens' => $sessions->count(),
                'moyenne_generale' => round($total / $sessions->count(), 2),
                'taux_reussite' => round(($reussis / $sessions->count()) * 100),
            ];
        });
    }

    /**
     * Invalider le cache d'un étudiant
     */
    public function invalidateEtudiantCache($etudiantId)
    {
        Cache::forget("examens_a_venir_{$etudiantId}");
        Cache::forget("stats_etudiant_{$etudiantId}");
        Cache::forget("derniers_resultats_{$etudiantId}");
    }

    /**
     * Invalider tout le cache
     */
    public function invalidateAll()
    {
        Cache::flush();
    }
}