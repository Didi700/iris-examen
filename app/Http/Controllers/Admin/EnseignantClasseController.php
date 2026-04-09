<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use App\Models\LogActivite;
use App\Models\Matiere;
use App\Models\Role;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnseignantClasseController extends Controller
{
    /**
     * Afficher le formulaire d'affectation d'un enseignant
     */
    public function create(Utilisateur $enseignant)
    {
        // Vérifier que c'est bien un enseignant
        if (!$enseignant->estEnseignant()) {
            return redirect()
                ->route('admin.utilisateurs.index')
                ->with('error', 'Cet utilisateur n\'est pas un enseignant.');
        }

        $classes = Classe::where('statut', 'active')->orderBy('nom')->get();
        $matieres = Matiere::orderBy('nom')->get();

        // Récupérer les affectations existantes
        $affectations = DB::table('enseignant_classe')
            ->where('enseignant_id', $enseignant->id)
            ->get();

        return view('admin.enseignants.affecter', compact('enseignant', 'classes', 'matieres', 'affectations'));
    }

    /**
     * Enregistrer une affectation
     */
    public function store(Request $request, Utilisateur $enseignant)
    {
        $request->validate([
            'classe_id' => 'required|exists:classes,id',
            'matiere_id' => 'required|exists:matieres,id',
        ], [
            'classe_id.required' => 'Veuillez sélectionner une classe.',
            'matiere_id.required' => 'Veuillez sélectionner une matière.',
        ]);

        // Vérifier que c'est bien un enseignant
        if (!$enseignant->estEnseignant()) {
            return redirect()
                ->back()
                ->with('error', 'Cet utilisateur n\'est pas un enseignant.');
        }

        // Vérifier si l'affectation existe déjà
        $existe = DB::table('enseignant_classe')
            ->where('enseignant_id', $enseignant->id)
            ->where('classe_id', $request->classe_id)
            ->where('matiere_id', $request->matiere_id)
            ->exists();

        if ($existe) {
            return redirect()
                ->back()
                ->with('error', 'Cet enseignant est déjà affecté à cette classe pour cette matière.');
        }

        try {
            // Créer l'affectation
            DB::table('enseignant_classe')->insert([
                'enseignant_id' => $enseignant->id,
                'classe_id' => $request->classe_id,
                'matiere_id' => $request->matiere_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $classe = Classe::find($request->classe_id);
            $matiere = Matiere::find($request->matiere_id);

            // Logger l'action
            LogActivite::log(
                'affectation_enseignant_classe',
                'enseignant_classe',
                "Affectation de {$enseignant->nomComplet()} à la classe {$classe->nom} pour la matière {$matiere->nom}",
                [
                    'enseignant_id' => $enseignant->id,
                    'classe_id' => $classe->id,
                    'matiere_id' => $matiere->id,
                ]
            );

            return redirect()
                ->route('admin.enseignants.affecter', $enseignant->id)
                ->with('success', 'Enseignant affecté avec succès !');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }

    /**
     * Supprimer une affectation
     */
    public function destroy(Utilisateur $enseignant, $affectationId)
    {
        try {
            $affectation = DB::table('enseignant_classe')
                ->where('id', $affectationId)
                ->where('enseignant_id', $enseignant->id)
                ->first();

            if (!$affectation) {
                return redirect()
                    ->back()
                    ->with('error', 'Affectation introuvable.');
            }

            DB::table('enseignant_classe')
                ->where('id', $affectationId)
                ->delete();

            // Logger l'action
            LogActivite::log(
                'retrait_enseignant_classe',
                'enseignant_classe',
                "Retrait de l'affectation de {$enseignant->nomComplet()}",
                ['affectation_id' => $affectationId]
            );

            return redirect()
                ->back()
                ->with('success', 'Affectation supprimée avec succès !');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }
}