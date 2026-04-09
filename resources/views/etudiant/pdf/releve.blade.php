<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relevé de Notes - {{ $etudiant->matricule }}</title>
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
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #2563eb;
        }
        
        .header h1 {
            color: #1e40af;
            font-size: 24pt;
            margin-bottom: 10px;
        }
        
        .header .subtitle {
            color: #64748b;
            font-size: 12pt;
        }
        
        /* Informations étudiant */
        .student-info {
            background: #f8fafc;
            border-left: 4px solid #2563eb;
            padding: 15px;
            margin-bottom: 30px;
        }
        
        .student-info table {
            width: 100%;
        }
        
        .student-info td {
            padding: 5px;
        }
        
        .student-info .label {
            font-weight: bold;
            color: #475569;
            width: 150px;
        }
        
        /* Statistiques globales */
        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        
        .stat-card {
            display: table-cell;
            width: 25%;
            padding: 15px;
            text-align: center;
            background: #f1f5f9;
            border-radius: 8px;
            margin: 5px;
        }
        
        .stat-value {
            font-size: 24pt;
            font-weight: bold;
            color: #1e40af;
        }
        
        .stat-label {
            font-size: 9pt;
            color: #64748b;
            margin-top: 5px;
        }
        
        /* Tableau des résultats */
        .results-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        .results-table thead {
            background: #1e40af;
            color: white;
        }
        
        .results-table th {
            padding: 12px 8px;
            text-align: left;
            font-size: 10pt;
            font-weight: 600;
        }
        
        .results-table td {
            padding: 10px 8px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 10pt;
        }
        
        .results-table tbody tr:nth-child(even) {
            background: #f8fafc;
        }
        
        .results-table tbody tr:hover {
            background: #f1f5f9;
        }
        
        /* Badge de réussite */
        .badge {
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 9pt;
            font-weight: 600;
            display: inline-block;
        }
        
        .badge-success {
            background: #dcfce7;
            color: #166534;
        }
        
        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }
        
        /* Note */
        .note-value {
            font-weight: bold;
            font-size: 11pt;
        }
        
        .note-success {
            color: #16a34a;
        }
        
        .note-danger {
            color: #dc2626;
        }
        
        /* Pied de page */
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
            text-align: center;
            font-size: 9pt;
            color: #64748b;
        }
        
        .signature {
            margin-top: 40px;
            text-align: right;
        }
        
        .signature-line {
            border-top: 1px solid #333;
            width: 200px;
            margin-left: auto;
            margin-top: 50px;
            padding-top: 5px;
            text-align: center;
            font-size: 10pt;
        }
        
        /* Watermark */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80pt;
            color: rgba(0, 0, 0, 0.05);
            z-index: -1;
        }
    </style>
</head>
<body>
    <div class="watermark">IRIS PARIS</div>
    
    <div class="container">
        <!-- En-tête -->
        <div class="header">
            <h1>📋 RELEVÉ DE NOTES</h1>
            <p class="subtitle">Année Académique {{ now()->year }}-{{ now()->year + 1 }}</p>
        </div>
        
        <!-- Informations Étudiant -->
        <div class="student-info">
            <table>
                <tr>
                    <td class="label">Nom Complet :</td>
                    <td>{{ $etudiant->nom_complet }}</td>
                    <td class="label">Matricule :</td>
                    <td>{{ $etudiant->matricule }}</td>
                </tr>
                <tr>
                    <td class="label">Classe :</td>
                    <td>{{ $etudiant->classe->nom ?? 'N/A' }}</td>
                    <td class="label">Email :</td>
                    <td>{{ $etudiant->utilisateur->email ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Date d'édition :</td>
                    <td>{{ $date_generation->format('d/m/Y à H:i') }}</td>
                    <td class="label">Statut :</td>
                    <td>{{ $etudiant->statut_libelle }}</td>
                </tr>
            </table>
        </div>
        
        <!-- Statistiques Globales -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value">{{ number_format($stats['moyenne_generale'], 2) }}%</div>
                <div class="stat-label">Moyenne Générale</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $stats['nb_examens'] }}</div>
                <div class="stat-label">Examens Passés</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $stats['nb_reussis'] }}</div>
                <div class="stat-label">Examens Réussis</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ number_format($stats['taux_reussite'], 1) }}%</div>
                <div class="stat-label">Taux de Réussite</div>
            </div>
        </div>
        
        <!-- Tableau des Résultats -->
        @if($sessions->isEmpty())
            <p style="text-align: center; padding: 40px; color: #64748b;">
                Aucun résultat disponible pour le moment.
            </p>
        @else
            <table class="results-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Examen</th>
                        <th>Matière</th>
                        <th style="text-align: center;">Note</th>
                        <th style="text-align: center;">Pourcentage</th>
                        <th style="text-align: center;">Résultat</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sessions as $session)
                        @php
                            $seuil = $session->examen->seuil_reussite ?? 50;
                            $reussi = $session->pourcentage >= $seuil;
                        @endphp
                        <tr>
                            <td>{{ $session->date_soumission->format('d/m/Y') }}</td>
                            <td>{{ $session->examen->titre }}</td>
                            <td>{{ $session->examen->matiere->nom ?? 'N/A' }}</td>
                            <td style="text-align: center;">
                                <span class="note-value {{ $reussi ? 'note-success' : 'note-danger' }}">
                                    {{ number_format($session->note_obtenue, 2) }}/{{ $session->note_maximale ?? $session->examen->note_totale }}
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <span class="note-value {{ $reussi ? 'note-success' : 'note-danger' }}">
                                    {{ number_format($session->pourcentage, 2) }}%
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <span class="badge {{ $reussi ? 'badge-success' : 'badge-danger' }}">
                                    {{ $reussi ? '✓ RÉUSSI' : '✗ ÉCHOUÉ' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
        
        <!-- Signature -->
        <div class="signature">
            <p style="margin-bottom: 60px;">Le Directeur des Études</p>
            <div class="signature-line">
                Signature et Cachet
            </div>
        </div>
        
        <!-- Pied de page -->
        <div class="footer">
            <p><strong>IRIS - Institut de Recherche et d'Innovation en Systèmes</strong></p>
            <p>Paris, France | www.iris-paris.fr | contact@iris-paris.fr</p>
            <p style="margin-top: 10px; font-size: 8pt;">
                Document généré automatiquement le {{ $date_generation->format('d/m/Y à H:i:s') }}
            </p>
        </div>
    </div>
</body>
</html>