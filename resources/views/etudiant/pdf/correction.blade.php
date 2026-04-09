<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Correction - {{ $session->examen->titre }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.6;
            color: #333;
        }
        
        .container {
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }
        
        /* En-tête */
        .header {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: white;
            padding: 25px;
            margin: -20px -20px 30px -20px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 24pt;
            margin-bottom: 10px;
        }
        
        .header .subtitle {
            font-size: 13pt;
            opacity: 0.9;
        }
        
        /* Informations examen */
        .exam-info {
            background: #f8fafc;
            border-left: 5px solid #3b82f6;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 5px;
        }
        
        .exam-info table {
            width: 100%;
        }
        
        .exam-info td {
            padding: 8px 5px;
        }
        
        .exam-info .label {
            font-weight: bold;
            color: #475569;
            width: 35%;
        }
        
        /* Résultat global */
        .result-summary {
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .result-score {
            font-size: 48pt;
            font-weight: bold;
            color: #16a34a;
            margin-bottom: 10px;
        }
        
        .result-score.failed {
            color: #dc2626;
        }
        
        .result-label {
            font-size: 14pt;
            color: #64748b;
            margin-bottom: 20px;
        }
        
        .result-badge {
            display: inline-block;
            padding: 10px 30px;
            border-radius: 25px;
            font-size: 14pt;
            font-weight: bold;
        }
        
        .result-badge.success {
            background: #dcfce7;
            color: #166534;
        }
        
        .result-badge.danger {
            background: #fee2e2;
            color: #991b1b;
        }
        
        /* Questions */
        .questions-section {
            margin-bottom: 30px;
        }
        
        .question-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        
        .question-header {
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f1f5f9;
        }
        
        .question-number {
            font-size: 14pt;
            font-weight: bold;
            color: #1e40af;
            display: inline-block;
            margin-right: 10px;
        }
        
        .question-points {
            background: #eff6ff;
            color: #1e40af;
            padding: 5px 15px;
            border-radius: 15px;
            font-weight: bold;
            font-size: 10pt;
            float: right;
        }
        
        .question-text {
            font-size: 11pt;
            margin: 15px 0;
            line-height: 1.6;
            clear: both;
        }
        
        .answer-section {
            margin: 15px 0;
            padding: 15px;
            border-radius: 5px;
        }
        
        .answer-label {
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 10pt;
        }
        
        .answer-content {
            padding: 10px;
            border-radius: 5px;
            background: #f8fafc;
        }
        
        .student-answer {
            border-left: 4px solid #3b82f6;
            background: #dbeafe;
        }
        
        .correct-answer {
            border-left: 4px solid #16a34a;
            background: #dcfce7;
        }
        
        .student-answer.wrong {
            border-left-color: #dc2626;
            background: #fee2e2;
        }
        
        /* Statut */
        .status-icon {
            float: right;
            font-size: 24pt;
            margin-left: 10px;
        }
        
        .correct-icon { color: #16a34a; }
        .wrong-icon { color: #dc2626; }
        
        /* Explication */
        .explanation {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin-top: 15px;
            border-radius: 5px;
        }
        
        .explanation-title {
            font-weight: bold;
            color: #92400e;
            margin-bottom: 8px;
            font-size: 10pt;
        }
        
        .explanation-text {
            color: #78350f;
            font-size: 10pt;
            line-height: 1.5;
        }
        
        /* Footer */
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
            text-align: center;
            font-size: 9pt;
            color: #64748b;
        }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- En-tête -->
        <div class="header">
            <h1>✏️ CORRECTION DÉTAILLÉE</h1>
            <p class="subtitle">{{ $session->examen->titre }}</p>
        </div>
        
        <!-- Informations Examen -->
        <div class="exam-info">
            <table>
                <tr>
                    <td class="label">Étudiant :</td>
                    <td><strong>{{ $etudiant->nom_complet }}</strong> ({{ $etudiant->matricule }})</td>
                </tr>
                <tr>
                    <td class="label">Matière :</td>
                    <td>{{ $session->examen->matiere->nom ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Classe :</td>
                    <td>{{ $session->examen->classe->nom ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Date de passage :</td>
                    <td>{{ $session->date_soumission ? $session->date_soumission->format('d/m/Y à H:i') : 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Durée :</td>
                    <td>{{ $session->examen->duree ?? 'N/A' }} minutes</td>
                </tr>
            </table>
        </div>
        
        <!-- Résultat Global -->
        <div class="result-summary">
            @php
                $seuil = $session->examen->seuil_reussite ?? 50;
                $reussi = $session->pourcentage >= $seuil;
            @endphp
            
            <div class="result-score {{ $reussi ? '' : 'failed' }}">
                {{ number_format($session->note_obtenue, 2) }}/{{ $session->note_maximale ?? $session->examen->note_totale }}
            </div>
            
            <div class="result-label">
                {{ number_format($session->pourcentage, 2) }}%
            </div>
            
            <div class="result-badge {{ $reussi ? 'success' : 'danger' }}">
                {{ $reussi ? '✓ EXAMEN RÉUSSI' : '✗ EXAMEN ÉCHOUÉ' }}
            </div>
        </div>
        
        <!-- Questions et Réponses -->
        <div class="questions-section">
            <h2 style="color: #1e40af; margin-bottom: 20px; font-size: 18pt;">📝 Détail des Réponses</h2>
            
            @if($session->reponses->isEmpty())
                <p style="text-align: center; padding: 40px; color: #64748b;">
                    Aucune réponse disponible pour cet examen.
                </p>
            @else
                @foreach($session->reponses as $index => $reponse)
                    @php
                        $question = $reponse->question;
                        if (!$question) continue;
                        
                        $pointsObtenus = $reponse->points_obtenus ?? 0;
                        $pointsMax = $reponse->points_attribues ?? $question->points ?? 1;
                        $estCorrecte = $pointsObtenus >= $pointsMax;
                    @endphp
                    
                    <div class="question-card">
                        <!-- En-tête Question -->
                        <div class="question-header">
                            <span class="question-number">Question {{ $index + 1 }}</span>
                            <span class="question-points">
                                {{ number_format($pointsObtenus, 1) }}/{{ $pointsMax }} pts
                            </span>
                            
                            <!-- Statut -->
                            <div class="status-icon {{ $estCorrecte ? 'correct-icon' : 'wrong-icon' }}">
                                {{ $estCorrecte ? '✓' : '✗' }}
                            </div>
                        </div>
                        
                        <!-- Énoncé -->
                        <div class="question-text">
                            {{ $question->enonce }}
                        </div>
                        
                        <!-- Votre réponse -->
                        <div class="answer-section">
                            <div class="answer-label">📝 Votre réponse :</div>
                            <div class="answer-content student-answer {{ $estCorrecte ? '' : 'wrong' }}">
                                @if($reponse->reponse_donnee)
                                    @php
                                        $reponseDonnee = is_string($reponse->reponse_donnee) 
                                            ? $reponse->reponse_donnee 
                                            : json_encode($reponse->reponse_donnee);
                                    @endphp
                                    {{ $reponseDonnee }}
                                @else
                                    <em>Aucune réponse</em>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Bonne réponse (si différente) -->
                        @if(!$estCorrecte && $question->reponse_correcte)
                            <div class="answer-section">
                                <div class="answer-label">✓ Bonne réponse :</div>
                                <div class="answer-content correct-answer">
                                    @php
                                        $bonneReponse = is_string($question->reponse_correcte)
                                            ? $question->reponse_correcte
                                            : json_encode($question->reponse_correcte);
                                    @endphp
                                    {{ $bonneReponse }}
                                </div>
                            </div>
                        @endif
                        
                        <!-- Explication -->
                        @if($question->explication)
                            <div class="explanation">
                                <div class="explanation-title">💡 Explication :</div>
                                <div class="explanation-text">
                                    {{ $question->explication }}
                                </div>
                            </div>
                        @endif
                        
                        <!-- Commentaire Enseignant -->
                        @if($reponse->commentaire)
                            <div class="explanation" style="background: #e0f2fe; border-left-color: #0284c7;">
                                <div class="explanation-title" style="color: #075985;">✍️ Commentaire de l'enseignant :</div>
                                <div class="explanation-text" style="color: #0c4a6e;">
                                    {{ $reponse->commentaire }}
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    @if(($index + 1) % 3 === 0 && $index + 1 < $session->reponses->count())
                        <div class="page-break"></div>
                    @endif
                @endforeach
            @endif
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p><strong>IRIS - Institut de Recherche et d'Innovation en Systèmes</strong></p>
            <p>Paris, France | www.iris-paris.fr</p>
            <p style="margin-top: 10px;">
                Document généré le {{ $date_generation->format('d/m/Y à H:i:s') }}
            </p>
        </div>
    </div>
</body>
</html>