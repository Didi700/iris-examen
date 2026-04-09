<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogActivite;
use App\Models\Matiere;
use Illuminate\Http\Request;

class MatiereController extends Controller
{
    public function index(Request $request)
    {
        $query = Matiere::with('createur');

        // Filtrer par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $matieres = $query->latest()->paginate(15);

        return view('admin.matieres.index', compact('matieres'));
    }

    public function create()
    {
        return view('admin.matieres.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'unique:matieres'],
            'description' => ['nullable', 'string'],
            'coefficient' => ['required', 'integer', 'min:1', 'max:10'],
        ], [
            'nom.required' => 'Le nom de la matière est obligatoire.',
            'code.required' => 'Le code de la matière est obligatoire.',
            'code.unique' => 'Ce code est déjà utilisé.',
            'coefficient.required' => 'Le coefficient est obligatoire.',
            'coefficient.min' => 'Le coefficient doit être au moins 1.',
            'coefficient.max' => 'Le coefficient ne peut pas dépasser 10.',
        ]);

        $matiere = Matiere::create([
            'nom' => $request->nom,
            'code' => $request->code,
            'description' => $request->description,
            'coefficient' => $request->coefficient,
            'statut' => 'active',
            'cree_par' => auth()->id(),
        ]);

        LogActivite::log(
            'creation_matiere',
            'matieres',
            "Création de la matière {$matiere->nom} (ID: {$matiere->id})",
            ['matiere_id' => $matiere->id]
        );

        return redirect()
            ->route('admin.matieres.index')
            ->with('success', 'Matière créée avec succès !');
    }

    public function show(Matiere $matiere)
    {
        $matiere->load('createur', 'questions', 'examens');
        return view('admin.matieres.show', compact('matiere'));
    }

    public function edit(Matiere $matiere)
    {
        return view('admin.matieres.edit', compact('matiere'));
    }

    public function update(Request $request, Matiere $matiere)
    {
        $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'unique:matieres,code,' . $matiere->id],
            'description' => ['nullable', 'string'],
            'coefficient' => ['required', 'integer', 'min:1', 'max:10'],
            'statut' => ['required', 'in:active,inactive'],
        ]);

        $matiere->update([
            'nom' => $request->nom,
            'code' => $request->code,
            'description' => $request->description,
            'coefficient' => $request->coefficient,
            'statut' => $request->statut,
        ]);

        LogActivite::log(
            'modification_matiere',
            'matieres',
            "Modification de la matière {$matiere->nom} (ID: {$matiere->id})",
            ['matiere_id' => $matiere->id]
        );

        return redirect()
            ->route('admin.matieres.index')
            ->with('success', 'Matière modifiée avec succès !');
    }

    public function destroy(Matiere $matiere)
    {
        $nom = $matiere->nom;
        $id = $matiere->id;

        $matiere->delete();

        LogActivite::log(
            'suppression_matiere',
            'matieres',
            "Suppression de la matière {$nom} (ID: {$id})",
            ['matiere_id' => $id]
        );

        return redirect()
            ->route('admin.matieres.index')
            ->with('success', 'Matière supprimée avec succès !');
    }
}