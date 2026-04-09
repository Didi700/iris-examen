<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulletin - {{ $etudiant->matricule }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.5;
            color: #333;
        }
        
        .container {
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }
        
        /* En-tête */
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            margin: -20px -20px 30px -20px;
        }
        
        .header h1 {
            font-size: 28pt;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        .header .subtitle {
            font-size: 14pt;
            opacity: 0.9;
        }
        
        /* Informations étudiant */
        .student-card {
            background: #f8fafc;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            border-left: 5px solid #667eea;
        }
        
        .student-card h2 {
            color: #667eea;
            font-size: 18pt;
            margin-bottom: 15px;
        }
        
        .student-card table {
            width: 100%;
        }
        
        .student-card td {
            padding: 8px 5px;
        }
        
        .student-card .label {
            font-weight: bold;
            color: #475569;
            width: 40%;
        }
        
        /* Statistiques globales */
        .stats-section {
            margin-bottom: 30px;
        }
        
        .stats-section h3 {
            color: #1e40af;
            font-size: 16pt;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 3px solid #667eea;
        }
        
        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            border-spacing: 10px;
        }
        
        .stat-box {
            display: table-cell;
            width: 33.33%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px;
        }
        
        .stat-box .value {
            font-size: 32pt;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .stat-box .label {
            font-size: 10pt;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        /* Performance par matière */
        .matiere-section {
            margin-bottom: 30px;
        }
        
        .matiere-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        
        .matiere-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f1f5f9;
        }
        
        .matiere-name {
            font-size: 14pt;
            font-weight: bold;
            color: #1e40af;
        }
        
        .matiere-average {
            font-size: 20pt;
            font-weight: bold;
            color: #16a34a;
        }
        
        .matiere-details {
            display: table;
            width: 100%;
        }
        
        .matiere-detail-item {
            display: table-cell;
            width: 33.33%;
            padding: 10px;
            text-align: center;
            background: #f8fafc;
            margin: 2px;
        }
        
        .matiere-detail-value {
            font-size: 16pt;
            font-weight: bold;
            color: #475569;
        }
        
        .matiere-detail-label {
            font-size: 9pt;
            color: #64748b;
            margin-top: 3px;
        }
        
        /* Barre de progression */
        .progress-bar {
            width: 100%;
            height: 25px;
            background: #e2e8f0;
            border-radius: 15px;
            overflow: hidden;
            margin-top: 10px;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #16a34a, #22c55e);
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding-right: 10px;
            color: white;
            font-weight: bold;
            font-size: 9pt;
        }
        
        /* Évolution */
        .evolution-section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        
        .evolution-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }
        
        .evolution-table thead {
            background: #1e40af;
            color: white;
        }
        
        .evolution-table th {
            padding: 12px 8px;
            text-align: left;
            font-size: 10pt;
        }
        
        .evolution-table td {
            padding: 10px 8px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .evolution-table tbody tr:nth-child(even) {
            background: #f8fafc;
        }
        
        /* Légende */
        .legend {
            margin-top: 20px;
            padding: 15px;
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            border-radius: 5px;
        }
        
        .legend h4 {
            color: #92400e;
            margin-bottom: 10px;
            font-size: 11pt;
        }
        
        .legend ul {
            margin-left: 20px;
            color: #78350f;
        }
        
        .legend li {
            margin: 5px 0;
            font-size: 9pt;
        }
        
        /* Commentaires */
        .comments-section {
            margin-top: 30px;
            padding: 20px;
            background: #f0fdf4;
            border-left: 4px solid #16a34a;
            border-radius: 5px;
            page-break-inside: avoid;
        }
        
        .comments-section h4 {
            color: #15803d;
            margin-bottom: 10px;
            font-size: 12pt;
        }
        
        .comment {
            margin: 10px 0;
            font-size: 10pt;
            color: #166534;
        }
        
        /* Footer */
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 3px solid #e2e8f0;
            text-align: center;
            page-break-inside: avoid;
        }
        
        .signatures {
            display: table;
            width: 100%;
            margin-top: 40px;
        }
        
        .signature-block {
            display: table-cell;
            width: 50%;
            text-align: center;
        }
        
        .signature-line {
            width: 150px;
            border-top: 1px solid #333;
            margin: 60px auto 10px;
            padding-top: 5px;
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
            <h1>📊 BULLETIN SCOLAIRE</h1>
            <p class="subtitle">Année Académique {{ now()->year }}-{{ now()->year + 1 }}</p>
        </div>
        
        <!-- Informations Étudiant -->
        <div class="student-card">
            <h2>👤 Informations de l'étudiant</h2>
            <table>
                <tr>
                    <td class="label">Nom complet :</td>
                    <td><strong>{{ $etudiant->nom_complet }}</strong></td>
                </tr>
                <tr>
                    <td class="label">Matricule :</td>
                    <td><strong>{{ $etudiant->matricule }}</strong></td>
                </tr>
                <tr>
                    <td class="label">Classe :</td>
                    <td>{{ $etudiant->classe->nom ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Niveau :</td>
                    <td>{{ $etudiant->classe->niveau ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Email :</td>
                    <td>{{ $etudiant->utilisateur->email ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Date d'édition :</td>
                    <td>{{ $date_generation->format('d/m/Y à H:i') }}</td>
                </tr>
            </table>
        </div>
        
        <!-- Statistiques Globales -->
        <div class="stats-section">
            <h3>📈 Statistiques Globales</h3>
            
            <div class="stats-grid">
                <div class="stat-box">
                    <div class="value">{{ number_format($stats['moyenne_generale'], 1) }}%</div>
                    <div class="label">Moyenne Générale</div>
                </div>
                <div class="stat-box">
                    <div class="value">{{ $stats['nb_examens'] }}</div>
                    <div class="label">Examens Passés</div>
                </div>
                <div class="stat-box">
                    <div class="value">{{ number_format($stats['taux_reussite'], 1) }}%</div>
                    <div class="label">Taux de Réussite</div>
                </div>
            </div>
            
            <div class="progress-bar">
                <div class="progress-fill" style="width: {{ $stats['moyenne_generale'] }}%;">
                    {{ number_format($stats['moyenne_generale'], 1) }}%
                </div>
            </div>
        </div>
        
        <!-- Performance par Matière -->
        <div class="matiere-section">
            <h3>📚 Performance par Matière</h3>
            
            @if($progressionMatieres->isEmpty())
                <p style="text-align: center; padding: 30px; color: #64748b;">
                    Aucune donnée disponible
                </p>
            @else
                @foreach($progressionMatieres as $data)
                    <div class="matiere-card">
                        <div class="matiere-header">
                            <div class="matiere-name">{{ $data['matiere'] }}</div>
                            <div class="matiere-average">{{ number_format($data['moyenne'], 1) }}%</div>
                        </div>
                        
                        <div class="matiere-details">
                            <div class="matiere-detail-item">
                                <div class="matiere-detail-value">{{ $data['nb_examens'] }}</div>
                                <div class="matiere-detail-label">Examens</div>
                            </div>
                            <div class="matiere-detail-item">
                                <div class="matiere-detail-value">{{ $data['nb_reussis'] }}</div>
                                <div class="matiere-detail-label">Réussis</div>
                            </div>
                            <div class="matiere-detail-item">
                                <div class="matiere-detail-value">{{ number_format($data['taux_reussite'], 1) }}%</div>
                                <div class="matiere-detail-label">Taux</div>
                            </div>
                        </div>
                        
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ $data['moyenne'] }}%;">
                                {{ number_format($data['moyenne'], 1) }}%
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
        
        <div class="page-break"></div>
        
        <!-- Évolution des Notes -->
        <div class="evolution-section">
            <h3>📉 Évolution des 10 Derniers Examens</h3>
            
            @if($evolutionNotes->isEmpty())
                <p style="text-align: center; padding: 30px; color: #64748b;">
                    Aucune donnée disponible
                </p>
            @else
                <table class="evolution-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Examen</th>
                            <th style="text-align: center;">Note (%)</th>
                            <th style="text-align: center;">Tendance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($evolutionNotes as $index => $evolution)
                            @php
                                $tendance = '';
                                if ($index > 0) {
                                    $previous = $evolutionNotes[$index - 1]['note'];
                                    if ($evolution['note'] > $previous) {
                                        $tendance = '📈 +' . number_format($evolution['note'] - $previous, 1);
                                    } elseif ($evolution['note'] < $previous) {
                                        $tendance = '📉 ' . number_format($evolution['note'] - $previous, 1);
                                    } else {
                                        $tendance = '➡️ =';
                                    }
                                }
                            @endphp
                            <tr>
                                <td>{{ $evolution['date'] }}</td>
                                <td>{{ $evolution['examen'] }}</td>
                                <td style="text-align: center; font-weight: bold; color: {{ $evolution['note'] >= 50 ? '#16a34a' : '#dc2626' }};">
                                    {{ number_format($evolution['note'], 1) }}%
                                </td>
                                <td style="text-align: center;">{{ $tendance }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
        
        <!-- Légende -->
        <div class="legend">
            <h4>📌 Légende et Remarques</h4>
            <ul>
                <li><strong>Moyenne Générale :</strong> Moyenne de tous les examens passés</li>
                <li><strong>Taux de Réussite :</strong> Pourcentage d'examens réussis (≥ seuil)</li>
                <li><strong>Tendance :</strong> 📈 Progression | 📉 Régression | ➡️ Stable</li>
            </ul>
        </div>
        
        <!-- Commentaires -->
        <div class="comments-section">
            <h4>💬 Appréciation Générale</h4>
            
            @if($stats['moyenne_generale'] >= 80)
                <p class="comment">
                    <strong>Excellent travail !</strong> L'étudiant démontre une excellente maîtrise des matières 
                    avec une moyenne générale de {{ number_format($stats['moyenne_generale'], 1) }}%. 
                    Continuez sur cette voie !
                </p>
            @elseif($stats['moyenne_generale'] >= 60)
                <p class="comment">
                    <strong>Bon niveau.</strong> L'étudiant montre une bonne compréhension des matières. 
                    Quelques efforts supplémentaires permettraient d'atteindre l'excellence.
                </p>
            @elseif($stats['moyenne_generale'] >= 50)
                <p class="comment">
                    <strong>Résultats satisfaisants.</strong> L'étudiant obtient des résultats corrects. 
                    Un travail plus régulier est conseillé pour progresser davantage.
                </p>
            @else
                <p class="comment">
                    <strong>Des efforts sont nécessaires.</strong> L'étudiant doit redoubler d'efforts 
                    et solliciter de l'aide pour améliorer ses résultats.
                </p>
            @endif
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="signatures">
                <div class="signature-block">
                    <p style="font-weight: bold;">L'étudiant</p>
                    <div class="signature-line">Signature</div>
                </div>
                <div class="signature-block">
                    <p style="font-weight: bold;">Le Directeur des Études</p>
                    <div class="signature-line">Signature et Cachet</div>
                </div>
            </div>
            
            <p style="margin-top: 30px; font-size: 9pt; color: #64748b;">
                <strong>IRIS - Institut de Recherche et d'Innovation en Systèmes</strong><br>
                Paris, France | www.iris-paris.fr<br>
                Document généré le {{ $date_generation->format('d/m/Y à H:i:s') }}
            </p>
        </div>
    </div>
</body>
</html>