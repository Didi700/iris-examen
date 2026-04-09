<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Role;
use App\Models\Utilisateur;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Dashboard général (redirige selon le rôle)
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->estSuperAdmin() || $user->estAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->estEnseignant()) {
            return redirect()->route('enseignant.dashboard');
        } elseif ($user->estEtudiant()) {
            return redirect()->route('etudiant.dashboard');
        }

        return redirect()->route('login');
    }

    /**
     * Dashboard Admin
     */
    public function adminDashboard()
    {
        // Récupérer les IDs des rôles
        $roleEtudiant = Role::where('nom', 'etudiant')->first();
        $roleEnseignant = Role::where('nom', 'enseignant')->first();
        $roleAdmin = Role::where('nom', 'admin')->first();
        $roleSuperAdmin = Role::where('nom', 'super_admin')->first();

        // Compter les utilisateurs par rôle
        $stats = [
            'etudiants' => Utilisateur::where('role_id', $roleEtudiant->id ?? null)->count(),
            'enseignants' => Utilisateur::where('role_id', $roleEnseignant->id ?? null)->count(),
            'admins' => Utilisateur::whereIn('role_id', [
                $roleAdmin->id ?? null,
                $roleSuperAdmin->id ?? null
            ])->count(),
            'classes' => Classe::count(),
            'classes_actives' => Classe::where('statut', 'active')->count(),
            'examens' => 0, // TODO: À implémenter quand on aura les examens
            'total_utilisateurs' => Utilisateur::count(),
        ];

        // Statistiques supplémentaires
        $stats['etudiants_actifs'] = Utilisateur::where('role_id', $roleEtudiant->id ?? null)
            ->where('statut', 'actif')
            ->count();

        $stats['enseignants_actifs'] = Utilisateur::where('role_id', $roleEnseignant->id ?? null)
            ->where('statut', 'actif')
            ->count();

        // Classes avec le plus d'étudiants
        $classesPopulaires = Classe::withCount('etudiants')
            ->orderBy('etudiants_count', 'desc')
            ->take(5)
            ->get();

        // Derniers utilisateurs créés
        $derniersUtilisateurs = Utilisateur::with('role')
            ->latest()
            ->take(5)
            ->get();

        // Dernières classes créées
        $dernieresClasses = Classe::latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'classesPopulaires',
            'derniersUtilisateurs',
            'dernieresClasses'
        ));
    }

    /**
     * Dashboard Enseignant
     */
    public function enseignantDashboard()
    {
        $enseignant = auth()->user();

        $stats = [
            'classes' => $enseignant->classesEnseignees()->count(),
            'etudiants' => $enseignant->classesEnseignees()->withCount('etudiants')->get()->sum('etudiants_count'),
            'examens' => 0, // TODO: À implémenter
            'corrections' => 0, // TODO: À implémenter
        ];

        return view('enseignant.dashboard', compact('stats'));
    }

    /**
     * Dashboard Étudiant
     */
    public function etudiantDashboard()
    {
        $etudiant = auth()->user();

        $stats = [
            'classes' => $etudiant->classes()->count(),
            'examens_passes' => 0, // TODO: À implémenter
            'examens_a_venir' => 0, // TODO: À implémenter
            'moyenne_generale' => 0, // TODO: À implémenter
        ];

        return view('etudiant.dashboard', compact('stats'));
    }
}