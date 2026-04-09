<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use App\Models\LogActivite;
use Illuminate\Http\Request;

class ClasseController extends Controller
{
    public function index(Request $request)
    {
        $query = Classe::with('createur');

        // Filtrer par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('niveau', 'like', "%{$search}%");
            });
        }

        $classes = $query->latest()->paginate(15);

        return view('admin.classes.index', compact('classes'));
    }

    public function create()
    {
        return view('admin.classes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'unique:classes'],
            'niveau' => ['required', 'string', 'max:50'],
            'annee_scolaire' => ['required', 'string', 'max:20'],
            'description' => ['nullable', 'string'],
            'effectif_max' => ['required', 'integer', 'min:1'],
            'date_debut' => ['nullable', 'date'],
            'date_fin' => ['nullable', 'date', 'after:date_debut'],
            'accepte_alternance' => ['boolean'],
            'accepte_initial' => ['boolean'],
            'accepte_formation_continue' => ['boolean'],
        ], [
            'nom.required' => 'Le nom de la classe est obligatoire.',
            'code.required' => 'Le code de la classe est obligatoire.',
            'code.unique' => 'Ce code est déjà utilisé.',
            'niveau.required' => 'Le niveau est obligatoire.',
            'annee_scolaire.required' => 'L\'année scolaire est obligatoire.',
            'effectif_max.required' => 'L\'effectif maximum est obligatoire.',
            'effectif_max.min' => 'L\'effectif maximum doit être au moins 1.',
        ]);

        $classe = Classe::create([
            'nom' => $request->nom,
            'code' => $request->code,
            'niveau' => $request->niveau,
            'annee_scolaire' => $request->annee_scolaire,
            'description' => $request->description,
            'effectif_max' => $request->effectif_max,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'accepte_alternance' => $request->boolean('accepte_alternance'),
            'accepte_initial' => $request->boolean('accepte_initial'),
            'accepte_formation_continue' => $request->boolean('accepte_formation_continue'),
            'statut' => 'active',
            'cree_par' => auth()->id(),
        ]);

        LogActivite::log(
            'creation_classe',
            'classes',
            "Création de la classe {$classe->nom} (ID: {$classe->id})",
            ['classe_id' => $classe->id]
        );

        return redirect()
            ->route('admin.classes.index')
            ->with('success', 'Classe créée avec succès !');
    }

    public function show(Classe $classe)
    {
         $classe->load([
            'createur',
            'etudiants' => function($query) {      
                $query->orderBy('matricule');       
            },       
            'enseignants'
        ]);   
        return view('admin.classes.show', compact('classe'));
    }

    public function edit(Classe $classe)
    {
        return view('admin.classes.edit', compact('classe'));
    }

    public function update(Request $request, Classe $classe)
    {
        $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'unique:classes,code,' . $classe->id],
            'niveau' => ['required', 'string', 'max:50'],
            'annee_scolaire' => ['required', 'string', 'max:20'],
            'description' => ['nullable', 'string'],
            'effectif_max' => ['required', 'integer', 'min:1'],
            'date_debut' => ['nullable', 'date'],
            'date_fin' => ['nullable', 'date', 'after:date_debut'],
            'accepte_alternance' => ['boolean'],
            'accepte_initial' => ['boolean'],
            'accepte_formation_continue' => ['boolean'],
            'statut' => ['required', 'in:active,inactive,archivee'],
        ]);

        $classe->update([
            'nom' => $request->nom,
            'code' => $request->code,
            'niveau' => $request->niveau,
            'annee_scolaire' => $request->annee_scolaire,
            'description' => $request->description,
            'effectif_max' => $request->effectif_max,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'accepte_alternance' => $request->boolean('accepte_alternance'),
            'accepte_initial' => $request->boolean('accepte_initial'),
            'accepte_formation_continue' => $request->boolean('accepte_formation_continue'),
            'statut' => $request->statut,
        ]);

        LogActivite::log(
            'modification_classe',
            'classes',
            "Modification de la classe {$classe->nom} (ID: {$classe->id})",
            ['classe_id' => $classe->id]
        );

        return redirect()
            ->route('admin.classes.index')
            ->with('success', 'Classe modifiée avec succès !');
    }

    public function destroy(Classe $classe)
    {
        $nom = $classe->nom;
        $id = $classe->id;

        $classe->delete();

        LogActivite::log(
            'suppression_classe',
            'classes',
            "Suppression de la classe {$nom} (ID: {$id})",
            ['classe_id' => $id]
        );

        return redirect()
            ->route('admin.classes.index')
            ->with('success', 'Classe supprimée avec succès !');
    }
}