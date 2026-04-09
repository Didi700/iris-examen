<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use App\Models\Classe;
use App\Models\Matiere;
use App\Models\Examen;
use App\Models\SessionExamen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistiques générales
        $stats = [
            'total_utilisateurs' => Utilisateur::count(),
            'total_etudiants' => Utilisateur::where('role', 'etudiant')->count(),
            'total_enseignants' => Utilisateur::where('role', 'enseignant')->count(),
            'total_admins' => Utilisateur::where('role', 'admin')->count(),
            'total_classes' => Classe::count(),
            'total_matieres' => Matiere::count(),
            'total_examens' => Examen::count(),
            'examens_publies' => Examen::where('statut', 'publie')->count(),
            'total_sessions' => SessionExamen::count(),
            'sessions_terminees' => SessionExamen::terminees()->count(),
        ];

        // Derniers utilisateurs
        $derniersUtilisateurs = Utilisateur::orderBy('created_at', 'desc')->limit(5)->get();

        // Derniers examens
        $derniersExamens = Examen::with(['enseignant', 'matiere', 'classe'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Activités récentes
        $activitesRecentes = DB::table('logs_activite')
            ->join('utilisateurs', 'logs_activite.utilisateur_id', '=', 'utilisateurs.id')
            ->select('logs_activite.*', 'utilisateurs.nom', 'utilisateurs.prenom')
            ->orderBy('logs_activite.created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'derniersUtilisateurs', 'derniersExamens', 'activitesRecentes'));
    }
}