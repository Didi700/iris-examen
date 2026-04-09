<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Relevé de notes</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #FDB913;
        }

        .header h1 {
            color: #0066CC;
            font-size: 24px;
            margin-bottom: 5px;
        }

        .header p {
            color: #666;
            font-size: 14px;
        }

        .info-section {
            margin-bottom: 25px;
            padding: 15px;
            background: #f8f9fa;
            border-left: 4px solid #0066CC;
        }

        .info-section h2 {
            color: #0066CC;
            font-size: 16px;
            margin-bottom: 10px;
        }

        .info-grid {
            display: table;
            width: 100%;
        }

        .info-row {
            display: table-row;
        }

        .info-label {
            display: table-cell;
            font-weight: bold;
            padding: 5px 10px 5px 0;
            width: 150px;
        }

        .info-value {
            display: table-cell;
            padding: 5px 0;
        }

        .resultat-box {
            text-align: center;
            padding: 20px;
            margin: 20px 0;
            background: {{ $details['est_reussi'] ? '#d4edda' : '#f8d7da' }};
            border: 2px solid {{ $details['est_reussi'] ? '#28a745' : '#dc3545' }};
            border-radius: 8px;
        }

        .resultat-box .note {
            font-size: 36px;
            font-weight: bold;
            color: {{ $details['est_reussi'] ? '#28a745' : '#dc3545' }};
            margin: 10px 0;
        }

        .resultat-box .statut {
            font-size: 18px;
            font-weight: bold;
            color: {{ $details['est_reussi'] ? '#28a745' : '#dc3545' }};
        }

        .questions-section {
            margin-top: 30px;
        }

        .question {
            margin-bottom: 25px;
            padding: 15px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            page-break-inside: avoid;
        }

        .question-header {
            background: #0066CC;
            color: white;
            padding: 10px;
            margin: -15px -15px 15px -15px;
            border-radius: 5px 5px 0 0;
            font-weight: bold;
        }

        .question-enonce {
            margin-bottom: 10px;
            font-weight: bold;
        }

        .reponse {
            padding: 10px;
            margin: 10px 0;
            background: #f8f9fa;
            border-left: 4px solid #6c757d;
        }

        .reponse.correcte {
            background: #d4edda;
            border-left-color: #28a745;
        }

        .reponse.incorrecte {
            background: #f8d7da;
            border-left-color: #dc3545;
        }

        .commentaire {
            margin-top: 10px;
            padding: 10px;
            background: #e7f3ff;
            border-left: 4px solid #0066CC;
            font-style: italic;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #666;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- En-tête -->
    <div class="header">
        <h1>RELEVE DE NOTES</h1>
        <p>IRIS EXAM - Plateforme d'Examens en Ligne</p>
    </div>

    <!-- Informations étudiant -->
    <div class="info-section">
        <h2>Informations de l'etudiant</h2>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Nom complet :</div>
                <div class="info-value">{{ $user->prenom }} {{ $user->nom }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Matricule :</div>
                <div class="info-value">{{ $user->matricule }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Classe :</div>
                <div class="info-value">{{ $session->examen->classe->nom }}</div>
            </div>
        </div>
    </div>

    <!-- Informations examen -->
    <div class="info-section">
        <h2>Informations de l'examen</h2>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Examen :</div>
                <div class="info-value">{{ $session->examen->titre }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Matiere :</div>
                <div class="info-value">{{ $session->examen->matiere->nom }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Enseignant :</div>
                <div class="info-value">{{ $session->examen->enseignant->prenom }} {{ $session->examen->enseignant->nom }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Date de passage :</div>
                <div class="info-value">{{ $session->created_at->format('d/m/Y a H:i') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Temps passe :</div>
                <div class="info-value">{{ $details['temps_passe'] }}</div>
            </div>
        </div>
    </div>

    <!-- Résultat -->
    <div class="resultat-box">
        <div class="statut">
            {{ $details['est_reussi'] ? 'EXAMEN REUSSI' : 'EXAMEN NON REUSSI' }}
        </div>
        <div class="note">{{ number_format($details['note_sur_20'], 2) }} / 20</div>
        <p>{{ $details['note_obtenue'] }} / {{ $details['note_maximale'] }} points ({{ round($details['pourcentage']) }}%)</p>
        <p>Questions correctes : {{ $details['questions_correctes'] }} / {{ $details['questions_totales'] }}</p>
    </div>

    <!-- Détail des questions -->
    <div class="questions-section">
        <h2 style="color: #0066CC; margin-bottom: 20px;">Detail des questions</h2>

        @foreach($session->examen->questions as $index => $question)
            @php
                $reponse = $reponses->get($question->id);
                $estCorrecte = $reponse && $reponse->est_correcte;
                $pointsObtenus = $reponse ? $reponse->points_obtenus : 0;
                $pointsMax = $question->pivot->points;
            @endphp

            <div class="question">
                <div class="question-header">
                    Question {{ $index + 1 }} - {{ $estCorrecte ? 'Correcte' : 'Incorrecte' }} - {{ number_format($pointsObtenus, 1) }}/{{ $pointsMax }} pts
                </div>

                <div class="question-enonce">
                    {{ $question->enonce }}
                </div>

                <div class="reponse {{ $estCorrecte ? 'correcte' : 'incorrecte' }}">
                    <strong>Votre reponse :</strong><br>
                    @if($reponse)
                        @if($question->type === 'qcm_unique' || $question->type === 'qcm_multiple')
                            @php
                                $reponsesDonnees = json_decode($reponse->reponse_donnee, true);
                                if (!is_array($reponsesDonnees)) {
                                    $reponsesDonnees = [$reponse->reponse_donnee];
                                }
                                $propositionsSelectionnees = $question->propositions->whereIn('id', $reponsesDonnees);
                            @endphp
                            @foreach($propositionsSelectionnees as $prop)
                                - {{ $prop->texte }}<br>
                            @endforeach
                        @else
                            {{ $reponse->reponse_donnee }}
                        @endif
                    @else
                        Aucune reponse
                    @endif
                </div>

                @if($reponse && $reponse->commentaire_correcteur)
                    <div class="commentaire">
                        <strong>Commentaire de l'enseignant :</strong><br>
                        {{ $reponse->commentaire_correcteur }}
                    </div>
                @endif

                @if($question->explication)
                    <div style="margin-top: 10px; padding: 10px; background: #e7f3ff; border-left: 4px solid #0066CC;">
                        <strong>Explication :</strong><br>
                        {{ $question->explication }}
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Document genere le {{ now()->format('d/m/Y a H:i') }}</p>
        <p>IRIS EXAM - {{ date('Y') }} - Tous droits reserves</p>
    </div>
</body>
</html>