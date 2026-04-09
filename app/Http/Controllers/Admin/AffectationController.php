<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use App\Models\LogActivite;
use App\Models\Role;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AffectationController extends Controller
{
    /**
     * Afficher le formulaire d'affectation pour une classe
     */
    public function showAffectationForm(Classe $classe)
    {
        $classe->load('etudiants');
        
        // Récupérer les étudiants non affectés à cette classe
        $roleEtudiant = Role::where('nom', 'etudiant')->first();
        $etudiantsDisponibles = Utilisateur::where('role_id', $roleEtudiant->id)
            ->whereDoesntHave('classes', function($query) use ($classe) {
                $query->where('classe_id', $classe->id);
            })
            ->orderBy('nom')
            ->get();

        return view('admin.affectations.create', compact('classe', 'etudiantsDisponibles'));
    }

    /**
     * Affecter un étudiant à une classe
     */
    public function affecterEtudiant(Request $request, Classe $classe)
    {
        $request->validate([
            'etudiant_id' => ['required', 'exists:utilisateurs,id'],
            'regime' => ['required', 'in:initial,alternance,formation_continue'],
            'date_inscription' => ['required', 'date'],
            'entreprise' => ['required_if:regime,alternance', 'nullable', 'string', 'max:255'],
            'adresse_entreprise' => ['nullable', 'string'],
            'ville_entreprise' => ['nullable', 'string', 'max:255'],
            'code_postal_entreprise' => ['nullable', 'string', 'max:10'],
            'tuteur_entreprise' => ['nullable', 'string', 'max:255'],
            'poste_tuteur' => ['nullable', 'string', 'max:255'],
            'email_tuteur' => ['nullable', 'email', 'max:255'],
            'telephone_tuteur' => ['nullable', 'string', 'max:20'],
            'rythme_alternance' => ['nullable', 'string', 'max:255'],
            'commentaire' => ['nullable', 'string'],
        ], [
            'etudiant_id.required' => 'Veuillez sélectionner un étudiant.',
            'etudiant_id.exists' => 'L\'étudiant sélectionné n\'existe pas.',
            'regime.required' => 'Le régime de formation est obligatoire.',
            'regime.in' => 'Le régime sélectionné est invalide.',
            'date_inscription.required' => 'La date d\'inscription est obligatoire.',
            'date_inscription.date' => 'La date d\'inscription doit être une date valide.',
            'entreprise.required_if' => 'Le nom de l\'entreprise est obligatoire pour l\'alternance.',
            'email_tuteur.email' => 'L\'email du tuteur doit être valide.',
        ]);

        // Vérifier si la classe n'est pas complète
        if ($classe->estComplete()) {
            return redirect()
                ->back()
                ->with('error', 'La classe a atteint son effectif maximum.');
        }

        // Vérifier que le régime est accepté par la classe
        if ($request->regime === 'initial' && !$classe->accepte_initial) {
            return redirect()
                ->back()
                ->with('error', 'Cette classe n\'accepte pas la formation initiale.');
        }

        if ($request->regime === 'alternance' && !$classe->accepte_alternance) {
            return redirect()
                ->back()
                ->with('error', 'Cette classe n\'accepte pas l\'alternance.');
        }

        if ($request->regime === 'formation_continue' && !$classe->accepte_formation_continue) {
            return redirect()
                ->back()
                ->with('error', 'Cette classe n\'accepte pas la formation continue.');
        }

        $etudiant = Utilisateur::findOrFail($request->etudiant_id);

        // Vérifier que l'étudiant n'est pas déjà inscrit
        if ($classe->etudiants()->wherePivot('etudiant_id', $request->etudiant_id)->exists()) {
            return redirect()
                ->back()
                ->with('error', 'Cet étudiant est déjà inscrit dans cette classe.');
        }

        DB::beginTransaction();
        try {
            // Affecter l'étudiant
            $classe->etudiants()->attach($request->etudiant_id, [
                'regime' => $request->regime,
                'date_inscription' => $request->date_inscription,
                'entreprise' => $request->entreprise,
                'adresse_entreprise' => $request->adresse_entreprise,
                'ville_entreprise' => $request->ville_entreprise,
                'code_postal_entreprise' => $request->code_postal_entreprise,
                'tuteur_entreprise' => $request->tuteur_entreprise,
                'poste_tuteur' => $request->poste_tuteur,
                'email_tuteur' => $request->email_tuteur,
                'telephone_tuteur' => $request->telephone_tuteur,
                'rythme_alternance' => $request->rythme_alternance,
                'statut' => 'inscrit',
                'inscrit_par' => auth()->id(),
                'commentaire' => $request->commentaire,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Mettre à jour les statistiques de la classe
            $classe->increment('effectif_actuel');
            
            if ($request->regime === 'initial') {
                $classe->increment('nb_etudiants_initial');
            } elseif ($request->regime === 'alternance') {
                $classe->increment('nb_etudiants_alternance');
            } else {
                $classe->increment('nb_etudiants_formation_continue');
            }

            // IMPORTANT : Rafraîchir le modèle pour mettre à jour les attributs calculés
            $classe->refresh();

            // Logger l'action
            LogActivite::log(
                'affectation_etudiant',
                'classes',
                "Affectation de {$etudiant->nomComplet()} à la classe {$classe->nom} ({$request->regime})",
                [
                    'classe_id' => $classe->id,
                    'etudiant_id' => $etudiant->id,
                    'regime' => $request->regime,
                ]
            );

            DB::commit();

            return redirect()
                ->route('admin.classes.show', $classe->id)
                ->with('success', "L'étudiant {$etudiant->nomComplet()} a été affecté avec succès !");

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->with('error', 'Une erreur est survenue lors de l\'affectation: ' . $e->getMessage());
        }
    }

    /**
     * Afficher le formulaire de modification d'affectation
     */
    public function editAffectation(Classe $classe, Utilisateur $etudiant)
    {
        // Récupérer les informations de l'affectation
        $affectation = DB::table('classe_etudiant')
            ->where('classe_id', $classe->id)
            ->where('etudiant_id', $etudiant->id)
            ->first();

        if (!$affectation) {
            return redirect()
                ->route('admin.classes.show', $classe->id)
                ->with('error', 'Affectation introuvable.');
        }

        return view('admin.affectations.edit', compact('classe', 'etudiant', 'affectation'));
    }

    /**
     * Mettre à jour une affectation
     */
    public function updateAffectation(Request $request, Classe $classe, Utilisateur $etudiant)
    {
        $request->validate([
            'regime' => ['required', 'in:initial,alternance,formation_continue'],
            'date_inscription' => ['required', 'date'],
            'entreprise' => ['required_if:regime,alternance', 'nullable', 'string', 'max:255'],
            'adresse_entreprise' => ['nullable', 'string'],
            'ville_entreprise' => ['nullable', 'string', 'max:255'],
            'code_postal_entreprise' => ['nullable', 'string', 'max:10'],
            'tuteur_entreprise' => ['nullable', 'string', 'max:255'],
            'poste_tuteur' => ['nullable', 'string', 'max:255'],
            'email_tuteur' => ['nullable', 'email', 'max:255'],
            'telephone_tuteur' => ['nullable', 'string', 'max:20'],
            'rythme_alternance' => ['nullable', 'string', 'max:255'],
            'statut' => ['required', 'in:inscrit,desinscrit,diplome,abandonne,en_attente'],
            'commentaire' => ['nullable', 'string'],
        ]);

        DB::beginTransaction();
        try {
            // Récupérer l'ancien régime
            $ancienneAffectation = DB::table('classe_etudiant')
                ->where('classe_id', $classe->id)
                ->where('etudiant_id', $etudiant->id)
                ->first();

            if (!$ancienneAffectation) {
                return redirect()
                    ->back()
                    ->with('error', 'Affectation introuvable.');
            }

            $ancienRegime = $ancienneAffectation->regime;
            $nouveauRegime = $request->regime;

            // Mettre à jour l'affectation
            DB::table('classe_etudiant')
                ->where('classe_id', $classe->id)
                ->where('etudiant_id', $etudiant->id)
                ->update([
                    'regime' => $request->regime,
                    'date_inscription' => $request->date_inscription,
                    'entreprise' => $request->entreprise,
                    'adresse_entreprise' => $request->adresse_entreprise,
                    'ville_entreprise' => $request->ville_entreprise,
                    'code_postal_entreprise' => $request->code_postal_entreprise,
                    'tuteur_entreprise' => $request->tuteur_entreprise,
                    'poste_tuteur' => $request->poste_tuteur,
                    'email_tuteur' => $request->email_tuteur,
                    'telephone_tuteur' => $request->telephone_tuteur,
                    'rythme_alternance' => $request->rythme_alternance,
                    'statut' => $request->statut,
                    'commentaire' => $request->commentaire,
                    'updated_at' => now(),
                ]);

            // Mettre à jour les statistiques si le régime a changé
            if ($ancienRegime !== $nouveauRegime) {
                // Décrémenter l'ancien régime
                if ($ancienRegime === 'initial') {
                    $classe->decrement('nb_etudiants_initial');
                } elseif ($ancienRegime === 'alternance') {
                    $classe->decrement('nb_etudiants_alternance');
                } else {
                    $classe->decrement('nb_etudiants_formation_continue');
                }

                // Incrémenter le nouveau régime
                if ($nouveauRegime === 'initial') {
                    $classe->increment('nb_etudiants_initial');
                } elseif ($nouveauRegime === 'alternance') {
                    $classe->increment('nb_etudiants_alternance');
                } else {
                    $classe->increment('nb_etudiants_formation_continue');
                }
            }

            // IMPORTANT : Rafraîchir le modèle
            $classe->refresh();

            // Logger l'action
            LogActivite::log(
                'modification_affectation',
                'classes',
                "Modification de l'affectation de {$etudiant->nomComplet()} dans la classe {$classe->nom}",
                [
                    'classe_id' => $classe->id,
                    'etudiant_id' => $etudiant->id,
                    'ancien_regime' => $ancienRegime,
                    'nouveau_regime' => $nouveauRegime,
                ]
            );

            DB::commit();

            return redirect()
                ->route('admin.classes.show', $classe->id)
                ->with('success', 'Affectation modifiée avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->with('error', 'Une erreur est survenue lors de la modification: ' . $e->getMessage());
        }
    }

    /**
     * Retirer un étudiant d'une classe
     */
    public function retirerEtudiant(Classe $classe, Utilisateur $etudiant)
    {
        DB::beginTransaction();
        try {
            // Récupérer le régime avant suppression
            $affectation = DB::table('classe_etudiant')
                ->where('classe_id', $classe->id)
                ->where('etudiant_id', $etudiant->id)
                ->first();

            if (!$affectation) {
                return redirect()
                    ->back()
                    ->with('error', 'Affectation introuvable.');
            }

            $regime = $affectation->regime;

            // Retirer l'étudiant
            $classe->etudiants()->detach($etudiant->id);

            // Mettre à jour les statistiques
            $classe->decrement('effectif_actuel');
            
            if ($regime === 'initial') {
                $classe->decrement('nb_etudiants_initial');
            } elseif ($regime === 'alternance') {
                $classe->decrement('nb_etudiants_alternance');
            } else {
                $classe->decrement('nb_etudiants_formation_continue');
            }

            // IMPORTANT : Rafraîchir le modèle
            $classe->refresh();

            // Logger l'action
            LogActivite::log(
                'retrait_etudiant',
                'classes',
                "Retrait de {$etudiant->nomComplet()} de la classe {$classe->nom}",
                [
                    'classe_id' => $classe->id,
                    'etudiant_id' => $etudiant->id,
                    'regime' => $regime,
                ]
            );

            DB::commit();

            return redirect()
                ->route('admin.classes.show', $classe->id)
                ->with('success', "L'étudiant {$etudiant->nomComplet()} a été retiré de la classe.");

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->with('error', 'Une erreur est survenue lors du retrait: ' . $e->getMessage());
        }
    }

    /**
     * Affectation en masse
     */
    public function affectationMasse(Classe $classe)
    {
        $roleEtudiant = Role::where('nom', 'etudiant')->first();
        $etudiantsDisponibles = Utilisateur::where('role_id', $roleEtudiant->id)
            ->whereDoesntHave('classes', function($query) use ($classe) {
                $query->where('classe_id', $classe->id);
            })
            ->orderBy('nom')
            ->get();

        return view('admin.affectations.masse', compact('classe', 'etudiantsDisponibles'));
    }

    /**
     * Traiter l'affectation en masse
     */
    public function storeAffectationMasse(Request $request, Classe $classe)
    {
        $request->validate([
            'etudiants' => ['required', 'array', 'min:1'],
            'etudiants.*' => ['exists:utilisateurs,id'],
            'regime' => ['required', 'in:initial,alternance,formation_continue'],
            'date_inscription' => ['required', 'date'],
        ], [
            'etudiants.required' => 'Veuillez sélectionner au moins un étudiant.',
            'etudiants.min' => 'Veuillez sélectionner au moins un étudiant.',
        ]);

        // Vérifier que le régime est accepté par la classe
        if ($request->regime === 'initial' && !$classe->accepte_initial) {
            return redirect()
                ->back()
                ->with('error', 'Cette classe n\'accepte pas la formation initiale.');
        }

        if ($request->regime === 'alternance' && !$classe->accepte_alternance) {
            return redirect()
                ->back()
                ->with('error', 'Cette classe n\'accepte pas l\'alternance.');
        }

        if ($request->regime === 'formation_continue' && !$classe->accepte_formation_continue) {
            return redirect()
                ->back()
                ->with('error', 'Cette classe n\'accepte pas la formation continue.');
        }

        $nbPlacesRestantes = $classe->placesRestantes();
        $nbEtudiants = count($request->etudiants);

        if ($nbEtudiants > $nbPlacesRestantes) {
            return redirect()
                ->back()
                ->with('error', "Impossible d'affecter {$nbEtudiants} étudiants. Il ne reste que {$nbPlacesRestantes} place(s) disponible(s).");
        }

        DB::beginTransaction();
        try {
            $nbAffectes = 0;

            foreach ($request->etudiants as $etudiantId) {
                // Vérifier si pas déjà inscrit
                if ($classe->etudiants()->where('etudiant_id', $etudiantId)->exists()) {
                    continue;
                }

                $classe->etudiants()->attach($etudiantId, [
                    'regime' => $request->regime,
                    'date_inscription' => $request->date_inscription,
                    'statut' => 'inscrit',
                    'inscrit_par' => auth()->id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $nbAffectes++;
            }

            // Mettre à jour les statistiques
            $classe->increment('effectif_actuel', $nbAffectes);
            
            if ($request->regime === 'initial') {
                $classe->increment('nb_etudiants_initial', $nbAffectes);
            } elseif ($request->regime === 'alternance') {
                $classe->increment('nb_etudiants_alternance', $nbAffectes);
            } else {
                $classe->increment('nb_etudiants_formation_continue', $nbAffectes);
            }

            // IMPORTANT : Rafraîchir le modèle
            $classe->refresh();

            // Logger l'action
            LogActivite::log(
                'affectation_masse',
                'classes',
                "Affectation de {$nbAffectes} étudiants à la classe {$classe->nom}",
                [
                    'classe_id' => $classe->id,
                    'nb_etudiants' => $nbAffectes,
                    'regime' => $request->regime,
                ]
            );

            DB::commit();

            return redirect()
                ->route('admin.classes.show', $classe->id)
                ->with('success', "{$nbAffectes} étudiant(s) ont été affectés avec succès !");

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->with('error', 'Une erreur est survenue lors de l\'affectation en masse: ' . $e->getMessage());
        }
    }
}