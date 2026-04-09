<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use App\Models\LogActivite;
use App\Models\Role;
use App\Models\Utilisateur;
use App\Models\Classe;
use App\Mail\BienvenueMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UtilisateurController extends Controller
{
    /**
     * Afficher la liste des utilisateurs
     */
    public function index(Request $request)
    {
        $query = Utilisateur::with('role', 'createur');

        // Filtrer par rôle
        if ($request->filled('role')) {
            $query->whereHas('role', fn($q) => $q->where('nom', $request->role));
        }

        // Filtrer par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('matricule', 'like', "%{$search}%");
            });
        }

        $utilisateurs = $query->latest()->paginate(15);
        $roles = Role::all();

        // Statistiques
        $stats = [
            'total' => Utilisateur::count(),
            'etudiants' => Utilisateur::whereHas('role', function ($q) {
                $q->where('nom', 'etudiant');
            })->count(),
            'enseignants' => Utilisateur::whereHas('role', function ($q) {
                $q->where('nom', 'enseignant');
            })->count(),
            'admins' => Utilisateur::whereHas('role', function ($q) {
                $q->whereIn('nom', ['admin', 'super_admin']);
            })->count(),
        ];

        return view('admin.utilisateurs.index', compact('utilisateurs', 'roles', 'stats'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $roles = Role::all();
        $classes = Classe::all();
        return view('admin.utilisateurs.create', compact('roles', 'classes'));
    }

    /**
     * Enregistrer un nouvel utilisateur
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('utilisateurs')->whereNull('deleted_at')],
            'role_id' => ['required', 'exists:roles,id'],
            // ✅ MATRICULE N'EST PLUS REQUIS - IL SERA GÉNÉRÉ AUTOMATIQUEMENT
            'telephone' => ['nullable', 'string', 'max:20'],
            'date_naissance' => ['nullable', 'date'],
            'genre' => ['nullable', 'in:homme,femme,autre'],
            'adresse' => ['nullable', 'string'],
            'ville' => ['nullable', 'string', 'max:255'],
            'code_postal' => ['nullable', 'string', 'max:10'],
            'classe_id' => ['nullable', 'exists:classes,id'],
            'specialite' => ['nullable', 'string', 'max:255'],
        ], [
            'nom.required' => 'Le nom est obligatoire.',
            'prenom.required' => 'Le prénom est obligatoire.',
            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'L\'email doit être valide.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'role_id.required' => 'Le rôle est obligatoire.',
        ]);

        // 🔑 GÉNÉRER UN MOT DE PASSE TEMPORAIRE
        $motDePasseTemporaire = Str::random(12);

        // ✅ GÉNÉRER LE MATRICULE AUTOMATIQUEMENT
        $matricule = $this->genererMatricule($request->role_id);

        // 🔄 Si l'email existe déjà en soft deleted, le restaurer  
        $utilisateur = Utilisateur::withTrashed()->firstWhere('email', $request->email);  
        if ($utilisateur) {
            $utilisateur->restore(); // on réactive l'utilisateur       
            $utilisateur->update([           
                'nom' => $request->nom,           
                'prenom' => $request->prenom,           
                'password' => Hash::make($motDePasseTemporaire),           
                'role_id' => $request->role_id,           
                'telephone' => $request->telephone,           
                'date_naissance' => $request->date_naissance,           
                'genre' => $request->genre,           
                'adresse' => $request->adresse,           
                'ville' => $request->ville,           
                'code_postal' => $request->code_postal,           
                'pays' => $request->pays ?? 'France',
                'statut' => 'actif',
        
            ]);
        } else {       
            // Création normale       
            $utilisateur = Utilisateur::create([
                'nom' => $request->nom,          
                'prenom' => $request->prenom,           
                'email' => $request->email,          
                'password' => Hash::make($motDePasseTemporaire),           
                'doit_changer_mot_de_passe' => true,           
                'role_id' => $request->role_id,           
                'matricule' => $this->genererMatricule($request->role_id), // matricule auto           
                'telephone' => $request->telephone,           
                'date_naissance' => $request->date_naissance,           
                'genre' => $request->genre,           
                'adresse' => $request->adresse,           
                'ville' => $request->ville,           
                'code_postal' => $request->code_postal,           
                'pays' => $request->pays ?? 'France',
                'statut' => 'actif',           
                'cree_par' => auth()->id(),       
            ]);
        }


        // 👤 Créer l’entité spécifique
        $role = Role::find($request->role_id);
        if ($role->nom === 'etudiant' && $request->filled('classe_id')) {
            $utilisateur->etudiant()->updateOrCreate(
                ['utilisateur_id' => $utilisateur->id],
                ['classe_id' => $request->classe_id]
            );
        } elseif ($role->nom === 'enseignant') {
            $utilisateur->enseignant()->updateOrCreate(
                ['utilisateur_id' => $utilisateur->id],      
                ['specialite' => $request->specialite]
        
            );
        }
        
        // 📝 LOGGER L'ACTION
        LogActivite::log(
            'creation_utilisateur',
            'utilisateurs',
            "Création de l'utilisateur {$utilisateur->nomComplet()} (ID: {$utilisateur->id}) - Matricule: {$matricule}",
            ['utilisateur_id' => $utilisateur->id, 'matricule' => $matricule]
        );

        // 🔔 CRÉER LA NOTIFICATION DANS L'APP
        if (class_exists('App\Services\NotificationService')) {
            try {
                NotificationService::notifierBienvenue($utilisateur);
            } catch (\Exception $e) {
                \Log::warning('Erreur notification bienvenue', ['error' => $e->getMessage()]);
            }
        }

        // 📧 ENVOYER L'EMAIL AVEC LE MOT DE PASSE TEMPORAIRE
        try {
            Mail::to($utilisateur->email)->send(new BienvenueMail($utilisateur, $motDePasseTemporaire));
            
            return redirect()
                ->route('admin.utilisateurs.index')
                ->with('success', "✅ Utilisateur créé avec succès ! Matricule généré : <strong>{$matricule}</strong>. Email envoyé à {$utilisateur->email}.");
        } catch (\Exception $e) {
            \Log::error("Erreur envoi email : " . $e->getMessage());
            
            return redirect()
                ->route('admin.utilisateurs.index')
                ->with('warning', "⚠️ Utilisateur créé (Matricule: <strong>{$matricule}</strong>) mais l'email n'a pas pu être envoyé. Mot de passe temporaire : <strong>{$motDePasseTemporaire}</strong>");
        }
    }

    /**
     * ✅ GÉNÈRE UN MATRICULE UNIQUE SELON LE RÔLE
     * Format : PRÉFIXE-ANNÉE-NUMÉRO
     * Exemples : ADM-2024-001, ENS-2024-001, ETU-2024-001
     */
    
    private function genererMatricule($roleId)
    {
        $role = Role::find($roleId);
        $annee = date('Y');
        
        // Définir le préfixe selon le rôle
        $prefixe = match($role->nom) {
            'super_admin', 'admin' => 'ADM',
            'enseignant' => 'ENS',
            'etudiant' => 'ETU',
            default => 'USR',
        };
    do {
        // Générer un numéro aléatoire entre 1 et 999
        $numero = rand(1, 999);
        $matricule = sprintf('%s-%s-%03d', $prefixe, $annee, $numero);
    } while (Utilisateur::where('matricule', $matricule)->exists());
        
    return $matricule;
  }
        

    /**
     * Afficher un utilisateur
     */
    public function show(Utilisateur $utilisateur)
    {
        $utilisateur->load('role', 'createur');
        
        // 👥 CHARGER LES RELATIONS SELON LE RÔLE
        if ($utilisateur->estEtudiant() && $utilisateur->etudiant) {
            $utilisateur->load('etudiant.classe');
        } elseif ($utilisateur->estEnseignant() && $utilisateur->enseignant) {
            $utilisateur->load('enseignant.classes', 'enseignant.matieres');
        }
        
        return view('admin.utilisateurs.show', compact('utilisateur'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Utilisateur $utilisateur)
    {
        $roles = Role::all();
        $classes = Classe::all();
        return view('admin.utilisateurs.edit', compact('utilisateur', 'roles', 'classes'));
    }

    /**
     * Mettre à jour un utilisateur
     */
    public function update(Request $request, Utilisateur $utilisateur)
    {
        $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('utilisateurs')->ignore($utilisateur->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role_id' => ['required', 'exists:roles,id'],
            // ✅ MATRICULE N'EST PLUS REQUIS EN ÉDITION (sera régénéré si le rôle change)
            'telephone' => ['nullable', 'string', 'max:20'],
            'date_naissance' => ['nullable', 'date'],
            'genre' => ['nullable', 'in:homme,femme,autre'],
            'adresse' => ['nullable', 'string'],
            'ville' => ['nullable', 'string', 'max:255'],
            'code_postal' => ['nullable', 'string', 'max:10'],
            'statut' => ['required', 'in:actif,inactif,suspendu'],
            'classe_id' => ['nullable', 'exists:classes,id'],
        ]);

        $data = [
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'telephone' => $request->telephone,
            'date_naissance' => $request->date_naissance,
            'genre' => $request->genre,
            'adresse' => $request->adresse,
            'ville' => $request->ville,
            'code_postal' => $request->code_postal,
            'pays' => $request->pays ?? 'France',
            'statut' => $request->statut,
        ];

        // ✅ SI LE RÔLE CHANGE, REGÉNÉRER LE MATRICULE
        if ($request->role_id != $utilisateur->role_id) {
            $nouveauMatricule = $this->genererMatricule($request->role_id);
            $data['matricule'] = $nouveauMatricule;
        }

        // Si un nouveau mot de passe est fourni
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $utilisateur->update($data);

        // Mettre à jour la classe si étudiant
        if ($utilisateur->estEtudiant() && $utilisateur->etudiant && $request->filled('classe_id')) {
            $utilisateur->etudiant->update(['classe_id' => $request->classe_id]);
        }

        // Logger l'action
        LogActivite::log(
            'modification_utilisateur',
            'utilisateurs',
            "Modification de l'utilisateur {$utilisateur->nomComplet()} (ID: {$utilisateur->id})",
            ['utilisateur_id' => $utilisateur->id]
        );

        return redirect()
            ->route('admin.utilisateurs.index')
            ->with('success', 'Utilisateur modifié avec succès !');
    }

    /**
     * Supprimer un utilisateur
     */
    public function destroy(Utilisateur $utilisateur)
    {
        // Empêcher la suppression de son propre compte
        if ($utilisateur->id === auth()->id()) {
            return redirect()
                ->route('admin.utilisateurs.index')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $nom = $utilisateur->nomComplet();
        $id = $utilisateur->id;

        $utilisateur->delete();

        // Logger l'action
        LogActivite::log(
            'suppression_utilisateur',
            'utilisateurs',
            "Suppression de l'utilisateur {$nom} (ID: {$id})",
            ['utilisateur_id' => $id]
        );

        return redirect()
            ->route('admin.utilisateurs.index')
            ->with('success', 'Utilisateur supprimé avec succès !');
    }

    /**
     * Changer le statut d'un utilisateur
     */
    public function toggleStatus(Utilisateur $utilisateur)
    {
        $nouveauStatut = $utilisateur->statut === 'actif' ? 'inactif' : 'actif';
        $utilisateur->update(['statut' => $nouveauStatut]);

        // Logger l'action
        LogActivite::log(
            'changement_statut_utilisateur',
            'utilisateurs',
            "Changement de statut de l'utilisateur {$utilisateur->nomComplet()} : {$nouveauStatut}",
            ['utilisateur_id' => $utilisateur->id, 'nouveau_statut' => $nouveauStatut]
        );

        return redirect()
            ->route('admin.utilisateurs.index')
            ->with('success', "Statut de l'utilisateur modifié avec succès !");
    }
}