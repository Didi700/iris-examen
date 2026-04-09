<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Résultats - {{ $examen->titre }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
        }
        
        .header {
            background: linear-gradient(135deg, #0066CC 0%, #7C3AED 100%);
            color: white;
            padding: 30px;
            text-align: center;
            margin-bottom: 30px;
        }
        
        .header h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        .header p {
            font-size: 12px;
            opacity: 0.9;
        }
        
        .info-box {
            background: #f8f9fa;
            border-left: 4px solid #FDB913;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .info-box h3 {
            font-size: 14px;
            margin-bottom: 10px;
            color: #0066CC;
        }
        
        .info-grid {
            display: table;
            width: 100%;
            margin-top: 10px;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-label {
            display: table-cell;
            font-weight: bold;
            padding: 5px 10px 5px 0;
            width: 30%;
        }
        
        .info-value {
            display: table-cell;
            padding: 5px 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        th {
            background: #0066CC;
            color: white;
            padding: 12px 8px;
            text-align: left;
            font-size: 11px;
            font-weight: bold;
        }
        
        td {
            padding: 10px 8px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        tr:nth-child(even) {
            background: #f9fafb;
        }
        
        .note-cell {
            font-weight: bold;
            font-size: 13px;
        }
        
        .note-excellente {
            color: #10B981;
        }
        
        .note-bonne {
            color: #0066CC;
        }
        
        .note-moyenne {
            color: #F59E0B;
        }
        
        .note-faible {
            color: #EF4444;
        }
        
        .stats-box {
            background: #e0f2fe;
            border: 2px solid #0066CC;
            padding: 15px;
            margin-top: 30px;
        }
        
        .stats-grid {
            display: table;
            width: 100%;
        }
        
        .stat-item {
            display: table-cell;
            text-align: center;
            padding: 10px;
        }
        
        .stat-value {
            font-size: 20px;
            font-weight: bold;
            color: #0066CC;
        }
        
        .stat-label {
            font-size: 10px;
            color: #666;
            margin-top: 5px;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
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
        <h1>📊 RÉSULTATS D'EXAMEN</h1>
        <p>{{ $examen->titre }}</p>
    </div>

    <!-- Informations de l'examen -->
    <div class="info-box">
        <h3>📝 Informations de l'examen</h3>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Matière :</div>
                <div class="info-value">{{ $examen->matiere->nom }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Classe :</div>
                <div class="info-value">{{ $examen->classe->nom }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Enseignant :</div>
                <div class="info-value">{{ $examen->enseignant->prenom }} {{ $examen->enseignant->nom }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Date de l'examen :</div>
                <div class="info-value">Du {{ $examen->date_debut->format('d/m/Y') }} au {{ $examen->date_fin->format('d/m/Y') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Durée :</div>
                <div class="info-value">{{ $examen->duree_minutes }} minutes</div>
            </div>
            <div class="info-row">
                <div class="info-label">Note totale :</div>
                <div class="info-value">{{ $examen->note_totale }} points</div>
            </div>
        </div>
    </div>

    <!-- Statistiques globales -->
    <div class="stats-box">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-value">{{ $sessions->count() }}</div>
                <div class="stat-label">Étudiants</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ number_format($moyenne, 2) }}/20</div>
                <div class="stat-label">Moyenne</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ number_format($noteMin, 2) }}/20</div>
                <div class="stat-label">Note min</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ number_format($noteMax, 2) }}/20</div>
                <div class="stat-label">Note max</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $tauxReussite }}%</div>
                <div class="stat-label">Taux de réussite</div>
            </div>
        </div>
    </div>

    <!-- Liste des résultats -->
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 35%;">Étudiant</th>
                <th style="width: 15%;">Matricule</th>
                <th style="width: 15%;">Note obtenue</th>
                <th style="width: 15%;">Note /20</th>
                <th style="width: 15%;">Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sessions as $index => $session)
                @php
                    $noteMax = $session->note_maximale ?? $examen->note_totale;
                    $noteSur20 = ($session->note_obtenue / $noteMax) * 20;
                    $reussi = $noteSur20 >= 10;
                    
                    // Déterminer la classe CSS
                    if ($noteSur20 >= 16) {
                        $noteClass = 'note-excellente';
                    } elseif ($noteSur20 >= 12) {
                        $noteClass = 'note-bonne';
                    } elseif ($noteSur20 >= 10) {
                        $noteClass = 'note-moyenne';
                    } else {
                        $noteClass = 'note-faible';
                    }
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $session->etudiant->prenom }} {{ $session->etudiant->nom }}</td>
                    <td>{{ $session->etudiant->matricule }}</td>
                    <td class="note-cell {{ $noteClass }}">
                        {{ number_format($session->note_obtenue, 2) }}/{{ $noteMax }}
                    </td>
                    <td class="note-cell {{ $noteClass }}">
                        {{ number_format($noteSur20, 2) }}/20
                    </td>
                    <td>
                        @if($reussi)
                            <span style="color: #10B981; font-weight: bold;">✓ Réussi</span>
                        @else
                            <span style="color: #EF4444; font-weight: bold;">✗ Échoué</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pied de page -->
    <div class="footer">
        <p>Document généré le {{ now()->format('d/m/Y à H:i') }} par IRIS EXAM</p>
        <p>© {{ now()->year }} IRIS EXAM - Tous droits réservés</p>
    </div>
</body>
</html>