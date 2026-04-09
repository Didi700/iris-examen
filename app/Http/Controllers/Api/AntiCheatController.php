<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\SessionExamen;

class AntiCheatController extends Controller
{
    public function report(Request $request)
    {
        $validated = $request->validate([
            'session_id' => 'required|exists:sessions_examen,id',
            'event_type' => 'required|string',
            'data' => 'required|array',
        ]);

        // Vérifier que la session appartient à l'utilisateur
        $session = SessionExamen::where('id', $validated['session_id'])
            ->where('etudiant_id', auth()->id())
            ->firstOrFail();

        // Logger l'événement
        Log::channel('anti-cheat')->warning('Événement de triche détecté', [
            'user_id' => auth()->id(),
            'session_id' => $validated['session_id'],
            'examen_id' => $session->examen_id,
            'event_type' => $validated['event_type'],
            'data' => $validated['data'],
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Incrémenter un compteur de tentatives (optionnel)
        $session->increment('tentatives_triche');

        return response()->json([
            'success' => true,
            'message' => 'Événement enregistré'
        ]);
    }
}