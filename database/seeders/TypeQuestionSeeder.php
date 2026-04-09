<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeQuestionSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'nom' => 'QCM (Choix Multiple)',
                'code' => 'qcm',
                'description' => 'Question à choix multiples avec plusieurs réponses possibles',
                'correction_automatique' => true,
            ],
            [
                'nom' => 'QCU (Choix Unique)',
                'code' => 'qcu',
                'description' => 'Question à choix unique avec une seule bonne réponse',
                'correction_automatique' => true,
            ],
            [
                'nom' => 'Vrai/Faux',
                'code' => 'vrai_faux',
                'description' => 'Question nécessitant une réponse vrai ou faux',
                'correction_automatique' => true,
            ],
            [
                'nom' => 'Texte Libre',
                'code' => 'texte_libre',
                'description' => 'Réponse rédigée nécessitant une correction manuelle',
                'correction_automatique' => false,
            ],
            [
                'nom' => 'Code',
                'code' => 'code',
                'description' => 'Question nécessitant d\'écrire du code',
                'correction_automatique' => false,
            ],
        ];

        foreach ($types as $type) {
            DB::table('types_question')->insert([
                'nom' => $type['nom'],
                'code' => $type['code'],
                'description' => $type['description'],
                'correction_automatique' => $type['correction_automatique'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}