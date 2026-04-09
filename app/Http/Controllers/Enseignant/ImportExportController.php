<?php

namespace App\Http\Controllers\Enseignant;

use App\Http\Controllers\Controller;
use App\Exports\QuestionsExport;
use App\Exports\TemplateExport;
use App\Imports\QuestionsImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ImportExportController extends Controller
{
    /**
     * Afficher la page d'import/export
     */
    public function index()
    {
        $matieres = \App\Models\Matiere::orderBy('nom')->get();
        
        return view('enseignant.import-export.index', compact('matieres'));
    }

    /**
     * Exporter les questions en Excel
     */
    public function export(Request $request)
    {
        $matiereId = $request->input('matiere_id');
        $enseignantId = auth()->id();

        $filename = 'Questions_' . ($matiereId ? 'Matiere_' . $matiereId : 'Toutes') . '_' . now()->format('Y-m-d') . '.xlsx';

        return Excel::download(new QuestionsExport($enseignantId, $matiereId), $filename);
    }

    /**
     * Exporter les questions en PDF
     */
    public function exportPdf(Request $request)
    {
        $matiereId = $request->input('matiere_id');
        $enseignantId = auth()->id();

        // Récupérer les questions
        $query = \App\Models\BanqueQuestion::where('enseignant_id', $enseignantId)
            ->with(['matiere', 'propositions']);

        if ($matiereId) {
            $query->where('matiere_id', $matiereId);
            $matiere = \App\Models\Matiere::find($matiereId);
        } else {
            $matiere = null;
        }

        $questions = $query->orderBy('matiere_id')
            ->orderBy('created_at', 'desc')
            ->get();

        $enseignant = auth()->user();

        // Générer le PDF
        $pdf = Pdf::loadView('enseignant.import-export.questions-pdf', compact(
            'questions',
            'enseignant',
            'matiere'
        ));

        $pdf->setPaper('a4', 'portrait');

        $filename = 'Questions_' . ($matiereId ? 'Matiere_' . $matiereId : 'Toutes') . '_' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Télécharger le template
     */
    public function downloadTemplate()
    {
        $headers = [
            'Type',
            'Matière',
            'Énoncé',
            'Points par défaut',
            'Niveau de difficulté',
            'Proposition 1',
            'Correcte 1',
            'Proposition 2',
            'Correcte 2',
            'Proposition 3',
            'Correcte 3',
            'Proposition 4',
            'Correcte 4',
            'Réponse correcte (texte)',
            'Explication',
            'Tags',
            'Active',
        ];

        $exemples = [
            [
                'QCM Unique',
                'Mathématiques',
                'Quelle est la valeur de π ?',
                '1',
                'Facile',
                '3.14',
                'OUI',
                '2.71',
                'NON',
                '1.41',
                'NON',
                '1.73',
                'NON',
                '',
                'Pi est une constante mathématique',
                'geometrie,constante',
                'OUI',
            ],
            [
                'QCM Multiple',
                'Informatique',
                'Quels sont des langages de programmation ?',
                '2',
                'Moyen',
                'Python',
                'OUI',
                'HTML',
                'NON',
                'Java',
                'OUI',
                'CSS',
                'NON',
                '',
                'HTML et CSS sont des langages de balisage',
                'programmation',
                'OUI',
            ],
            [
                'Vrai/Faux',
                'Histoire',
                'La Révolution française a eu lieu en 1789.',
                '1',
                'Facile',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                'vrai',
                'La Révolution française a commencé en 1789',
                'histoire,revolution',
                'OUI',
            ],
        ];

        $data = array_merge([$headers], $exemples);

        return Excel::download(new TemplateExport($data), 'Template_Questions.xlsx');
    }

    /**
     * Importer les questions depuis Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            $import = new QuestionsImport(auth()->id());
            Excel::import($import, $request->file('file'));

            $message = "✅ Import terminé ! {$import->getImported()} question(s) importée(s)";
            
            if ($import->getSkipped() > 0) {
                $message .= ", {$import->getSkipped()} ignorée(s)";
            }

            if (!empty($import->getErrors())) {
                $errors = implode('<br>', $import->getErrors());
                return redirect()->back()
                    ->with('warning', $message)
                    ->with('errors_detail', $errors);
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'import : ' . $e->getMessage());
        }
    }
}