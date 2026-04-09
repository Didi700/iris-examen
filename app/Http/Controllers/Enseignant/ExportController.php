<?php

namespace App\Http\Controllers\Enseignant;

use App\Http\Controllers\Controller;
use App\Models\Examen;
use App\Models\SessionExamen;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;  // ⬅️ IMPORTANT

class ExportController extends Controller
{
    public function exportResultatsExamen(Examen $examen)
    {
        if ($examen->enseignant_id !== auth()->id()) {
            abort(403, 'Vous n\'avez pas accès à cet examen.');
        }

        $sessions = SessionExamen::where('examen_id', $examen->id)
            ->where('statut', 'corrige')
            ->whereNotNull('note_obtenue')
            ->with(['etudiant'])
            ->orderBy('note_obtenue', 'desc')
            ->get();

        if ($sessions->count() === 0) {
            return redirect()
                ->back()
                ->with('error', 'Aucun résultat disponible pour cet examen.');
        }

        $notes = $sessions->map(function($session) use ($examen) {
            $noteMax = $session->note_maximale ?? $examen->note_totale;
            return ($session->note_obtenue / $noteMax) * 20;
        });

        $moyenne = $notes->avg();
        $noteMin = $notes->min();
        $noteMax = $notes->max();
        
        $nbReussis = $notes->filter(function($note) {
            return $note >= 10;
        })->count();
        
        $tauxReussite = $sessions->count() > 0 
            ? round(($nbReussis / $sessions->count()) * 100) 
            : 0;

        $pdf = Pdf::loadView('pdf.resultats-examen', compact(
            'examen',
            'sessions',
            'moyenne',
            'noteMin',
            'noteMax',
            'tauxReussite'
        ));

        // ✅ CORRECTION ICI
        $filename = 'resultats_' . Str::slug($examen->titre) . '_' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    public function exportResultatEtudiant(SessionExamen $session)
    {
        if ($session->examen->enseignant_id !== auth()->id()) {
            abort(403, 'Vous n\'avez pas accès à cette session.');
        }

        if ($session->statut !== 'corrige' || !$session->note_obtenue) {
            return redirect()
                ->back()
                ->with('error', 'Cette session n\'a pas encore été corrigée.');
        }

        $pdf = Pdf::loadView('pdf.resultat-etudiant', compact('session'));

        // ✅ CORRECTION ICI
        $filename = 'resultat_' . 
                    Str::slug($session->etudiant->nom) . '_' . 
                    Str::slug($session->examen->titre) . '_' . 
                    now()->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }
}