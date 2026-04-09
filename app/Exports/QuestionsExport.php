<?php

namespace App\Exports;

use App\Models\BanqueQuestion;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Barryvdh\DomPDF\Facade\Pdf; // ✅ Ajoutez cette ligne

class QuestionsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $enseignantId;
    protected $matiereId;

    public function __construct($enseignantId, $matiereId = null)
    {
        $this->enseignantId = $enseignantId;
        $this->matiereId = $matiereId;
    }

    /**
     * Collection de questions à exporter
     */
    public function collection()
    {
        $query = BanqueQuestion::where('enseignant_id', $this->enseignantId)
            ->with(['matiere', 'propositions']);

        if ($this->matiereId) {
            $query->where('matiere_id', $this->matiereId);
        }

        return $query->orderBy('matiere_id')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * En-têtes du fichier Excel
     */
    public function headings(): array
    {
        return [
            'ID',
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
            'Date de création',
        ];
    }

    /**
     * Mapping des données
     */
    public function map($question): array
    {
        $propositions = $question->propositions->sortBy('ordre')->values();
        
        return [
            $question->id,
            $this->getTypeLabel($question->type),
            $question->matiere->nom,
            $question->enonce,
            $question->points_par_defaut,
            $this->getDifficulteLabel($question->niveau_difficulte),
            $propositions->get(0)?->texte ?? '',
            $propositions->get(0)?->est_correcte ? 'OUI' : 'NON',
            $propositions->get(1)?->texte ?? '',
            $propositions->get(1)?->est_correcte ? 'OUI' : 'NON',
            $propositions->get(2)?->texte ?? '',
            $propositions->get(2)?->est_correcte ? 'OUI' : 'NON',
            $propositions->get(3)?->texte ?? '',
            $propositions->get(3)?->est_correcte ? 'OUI' : 'NON',
            $question->reponse_correcte ?? '',
            $question->explication ?? '',
            $question->tags ?? '',
            $question->est_active ? 'OUI' : 'NON',
            $question->created_at->format('d/m/Y H:i'),
        ];
    }

    /**
     * Styles du fichier Excel
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '0066CC']
                ],
            ],
        ];
    }

    /**
     * Largeur des colonnes
     */
    public function columnWidths(): array
    {
        return [
            'A' => 8,
            'B' => 15,
            'C' => 20,
            'D' => 50,
            'E' => 15,
            'F' => 20,
            'G' => 30,
            'H' => 12,
            'I' => 30,
            'J' => 12,
            'K' => 30,
            'L' => 12,
            'M' => 30,
            'N' => 12,
            'O' => 40,
            'P' => 40,
            'Q' => 20,
            'R' => 10,
            'S' => 20,
        ];
    }

    /**
     * Convertir le type en label
     */
    private function getTypeLabel($type)
    {
        $types = [
            'qcm_unique' => 'QCM Unique',
            'qcm_multiple' => 'QCM Multiple',
            'vrai_faux' => 'Vrai/Faux',
            'reponse_courte' => 'Réponse Courte',
            'texte_libre' => 'Texte Libre',
        ];

        return $types[$type] ?? $type;
    }

    /**
     * Convertir le niveau de difficulté en label
     */
    private function getDifficulteLabel($niveau)
    {
        $niveaux = [
            'facile' => 'Facile',
            'moyen' => 'Moyen',
            'difficile' => 'Difficile',
        ];

        return $niveaux[$niveau] ?? $niveau;
    }
}