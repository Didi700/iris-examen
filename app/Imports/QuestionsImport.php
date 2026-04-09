<?php

namespace App\Imports;

use App\Models\BanqueQuestion;
use App\Models\PropositionReponse;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class QuestionsImport implements ToCollection, WithHeadingRow, WithValidation
{
    protected $enseignantId;
    protected $errors = [];
    protected $imported = 0;
    protected $skipped = 0;

    public function __construct($enseignantId)
    {
        $this->enseignantId = $enseignantId;
    }

    /**
     * Traiter la collection de lignes
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            try {
                $this->importQuestion($row, $index);
                $this->imported++;
            } catch (\Exception $e) {
                $this->errors[] = "Ligne " . ($index + 2) . ": " . $e->getMessage();
                $this->skipped++;
            }
        }
    }

    /**
     * Importer une question
     */
    private function importQuestion($row, $index)
    {
        // Validation de base
        if (empty($row['type']) || empty($row['enonce'])) {
            throw new \Exception("Type ou énoncé manquant");
        }

        // Convertir le type
        $type = $this->convertType($row['type']);
        
        // Trouver la matière
        $matiere = \App\Models\Matiere::where('nom', 'LIKE', '%' . $row['matiere'] . '%')->first();
        if (!$matiere) {
            throw new \Exception("Matière '{$row['matiere']}' introuvable");
        }

        // Créer la question
        $question = BanqueQuestion::create([
            'enseignant_id' => $this->enseignantId,
            'matiere_id' => $matiere->id,
            'type' => $type,
            'enonce' => $row['enonce'],
            'points_par_defaut' => $row['points_par_defaut'] ?? 1,
            'niveau_difficulte' => $this->convertDifficulte($row['niveau_de_difficulte'] ?? 'Moyen'),
            'reponse_correcte' => $row['reponse_correcte_texte'] ?? null,
            'explication' => $row['explication'] ?? null,
            'tags' => $row['tags'] ?? null,
            'est_active' => $this->convertBoolean($row['active'] ?? 'OUI'),
        ]);

        // Créer les propositions pour QCM
        if (in_array($type, ['qcm_unique', 'qcm_multiple'])) {
            $this->createPropositions($question, $row);
        }
    }

    /**
     * Créer les propositions
     */
    private function createPropositions($question, $row)
    {
        $propositions = [
            ['texte' => $row['proposition_1'] ?? null, 'correcte' => $row['correcte_1'] ?? 'NON'],
            ['texte' => $row['proposition_2'] ?? null, 'correcte' => $row['correcte_2'] ?? 'NON'],
            ['texte' => $row['proposition_3'] ?? null, 'correcte' => $row['correcte_3'] ?? 'NON'],
            ['texte' => $row['proposition_4'] ?? null, 'correcte' => $row['correcte_4'] ?? 'NON'],
        ];

        $ordre = 1;
        foreach ($propositions as $prop) {
            if (!empty($prop['texte'])) {
                PropositionReponse::create([
                    'question_id' => $question->id,
                    'texte' => $prop['texte'],
                    'est_correcte' => $this->convertBoolean($prop['correcte']),
                    'ordre' => $ordre++,
                ]);
            }
        }
    }

    /**
     * Convertir le type de question
     */
    private function convertType($type)
    {
        $types = [
            'QCM Unique' => 'qcm_unique',
            'QCM Multiple' => 'qcm_multiple',
            'Vrai/Faux' => 'vrai_faux',
            'Réponse Courte' => 'reponse_courte',
            'Texte Libre' => 'texte_libre',
        ];

        return $types[$type] ?? 'qcm_unique';
    }

    /**
     * Convertir le niveau de difficulté
     */
    private function convertDifficulte($niveau)
    {
        $niveaux = [
            'Facile' => 'facile',
            'Moyen' => 'moyen',
            'Difficile' => 'difficile',
        ];

        return $niveaux[$niveau] ?? 'moyen';
    }

    /**
     * Convertir booléen
     */
    private function convertBoolean($value)
    {
        return in_array(strtoupper($value), ['OUI', 'YES', '1', 'TRUE']);
    }

    /**
     * Règles de validation
     */
    public function rules(): array
    {
        return [
            'type' => 'required',
            'enonce' => 'required',
        ];
    }

    /**
     * Obtenir les erreurs
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Obtenir le nombre de questions importées
     */
    public function getImported()
    {
        return $this->imported;
    }

    /**
     * Obtenir le nombre de questions ignorées
     */
    public function getSkipped()
    {
        return $this->skipped;
    }
}