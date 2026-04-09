<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Questions</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #333;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid #FDB913;
        }

        .header h1 {
            color: #0066CC;
            font-size: 22px;
            margin-bottom: 5px;
        }

        .header p {
            color: #666;
            font-size: 12px;
        }

        .info-section {
            margin-bottom: 20px;
            padding: 12px;
            background: #f8f9fa;
            border-left: 4px solid #0066CC;
        }

        .info-section strong {
            color: #0066CC;
        }

        .question {
            margin-bottom: 20px;
            padding: 12px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            page-break-inside: avoid;
        }

        .question-header {
            background: #0066CC;
            color: white;
            padding: 8px;
            margin: -12px -12px 12px -12px;
            border-radius: 5px 5px 0 0;
            font-weight: bold;
            font-size: 12px;
        }

        .question-enonce {
            font-weight: bold;
            margin-bottom: 10px;
            color: #1a1a1a;
        }

        .propositions {
            margin-left: 15px;
        }

        .proposition {
            padding: 6px;
            margin: 5px 0;
            background: #f8f9fa;
            border-left: 3px solid #6c757d;
        }

        .proposition.correcte {
            background: #d4edda;
            border-left-color: #28a745;
            font-weight: bold;
        }

        .details {
            margin-top: 10px;
            padding: 8px;
            background: #e7f3ff;
            border-left: 3px solid #0066CC;
            font-size: 10px;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            margin-right: 5px;
        }

        .badge-type {
            background: #0066CC;
            color: white;
        }

        .badge-difficulte-facile {
            background: #28a745;
            color: white;
        }

        .badge-difficulte-moyen {
            background: #FDB913;
            color: #1a1a1a;
        }

        .badge-difficulte-difficile {
            background: #dc3545;
            color: white;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #ddd;
            text-align: center;
            font-size: 9px;
            color: #666;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <!-- En-tête -->
    <div class="header">
        <h1>BANQUE DE QUESTIONS</h1>
        <p>IRIS EXAM - Plateforme d'Examens en Ligne</p>
    </div>

    <!-- Informations -->
    <div class="info-section">
        <strong>Enseignant :</strong> {{ $enseignant->prenom }} {{ $enseignant->nom }}<br>
        @if($matiere)
            <strong>Matiere :</strong> {{ $matiere->nom }}<br>
        @else
            <strong>Matiere :</strong> Toutes les matieres<br>
        @endif
        <strong>Nombre de questions :</strong> {{ $questions->count() }}<br>
        <strong>Date d'export :</strong> {{ now()->format('d/m/Y a H:i') }}
    </div>

    <!-- Questions -->
    @foreach($questions as $index => $question)
        @php
            $typeLabels = [
                'qcm_unique' => 'QCM Unique',
                'qcm_multiple' => 'QCM Multiple',
                'vrai_faux' => 'Vrai/Faux',
                'reponse_courte' => 'Reponse Courte',
                'texte_libre' => 'Texte Libre',
            ];
            $typeLabel = $typeLabels[$question->type] ?? $question->type;
        @endphp

        <div class="question">
            <div class="question-header">
                Question #{{ $index + 1 }}
                <span class="badge badge-type">{{ $typeLabel }}</span>
                <span class="badge badge-difficulte-{{ $question->niveau_difficulte }}">
                    {{ ucfirst($question->niveau_difficulte) }}
                </span>
                <span style="float: right;">{{ $question->points_par_defaut }} pts</span>
            </div>

            <div class="question-enonce">
                {{ $question->enonce }}
            </div>

            <div style="font-size: 10px; color: #666; margin-bottom: 8px;">
                <strong>Matiere :</strong> {{ $question->matiere->nom }}
                @if($question->tags)
                    | <strong>Tags :</strong> {{ $question->tags }}
                @endif
            </div>

            @if(in_array($question->type, ['qcm_unique', 'qcm_multiple']))
                <div class="propositions">
                    @foreach($question->propositions->sortBy('ordre') as $prop)
                        <div class="proposition {{ $prop->est_correcte ? 'correcte' : '' }}">
                            {{ chr(64 + $loop->iteration) }}. {{ $prop->texte }}
                            @if($prop->est_correcte)
                                (Correcte)
                            @endif
                        </div>
                    @endforeach
                </div>
            @elseif($question->type === 'vrai_faux')
                <div class="propositions">
                    <div class="proposition {{ $question->reponse_correcte === 'vrai' ? 'correcte' : '' }}">
                        Vrai @if($question->reponse_correcte === 'vrai') (Correct) @endif
                    </div>
                    <div class="proposition {{ $question->reponse_correcte === 'faux' ? 'correcte' : '' }}">
                        Faux @if($question->reponse_correcte === 'faux') (Correct) @endif
                    </div>
                </div>
            @elseif($question->reponse_correcte)
                <div class="details">
                    <strong>Reponse attendue :</strong> {{ $question->reponse_correcte }}
                </div>
            @endif

            @if($question->explication)
                <div class="details">
                    <strong>Explication :</strong> {{ $question->explication }}
                </div>
            @endif
        </div>

        @if(($index + 1) % 4 === 0 && ($index + 1) < $questions->count())
            <div class="page-break"></div>
        @endif
    @endforeach

    <!-- Footer -->
    <div class="footer">
        <p>Document genere le {{ now()->format('d/m/Y a H:i') }}</p>
        <p>IRIS EXAM - {{ date('Y') }} - Tous droits reserves</p>
    </div>
</body>
</html>