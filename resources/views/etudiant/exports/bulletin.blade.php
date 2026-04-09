<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bulletin de notes</title>
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
            border-bottom: 4px solid #FDB913;
        }

        .header h1 {
            color: #0066CC;
            font-size: 28px;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .header .subtitle {
            color: #666;
            font-size: 14px;
            margin-top: 5px;
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
            text-transform: uppercase;
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
            width: 200px;
            color: #666;
        }

        .info-value {
            display: table-cell;
            padding: 5px 0;
        }

        .statistiques-box {
            display: table;
            width: 100%;
            margin: 20px 0;
        }

        .stat-item {
            display: table-cell;
            width: 33.33%;
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            border-right: 2px solid white;
        }

        .stat-item:last-child {
            border-right: none;
        }

        .stat-item .value {
            font-size: 32px;
            font-weight: bold;
            color: #0066CC;
            display: block;
            margin-bottom: 5px;
        }

        .stat-item .label {
            font-size: 11px;
            color: #666;
            text-transform: uppercase;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        thead th {
            background: #0066CC;
            color: white;
            padding: 12px 8px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
        }

        tbody td {
            padding: 10px 8px;
            border-bottom: 1px solid #ddd;
            font-size: 11px;
        }

        tbody tr:nth-child(even) {
            background: #f8f9fa;
        }

        .text-center {
            text-align: center;
        }

        .note-cell {
            font-weight: bold;
            font-size: 13px;
        }

        .note-cell.reussi {
            color: #28a745;
        }

        .note-cell.echoue {
            color: #dc3545;
        }

        .appreciation {
            margin-top: 30px;
            padding: 20px;
            background: #e7f3ff;
            border-left: 4px solid #0066CC;
        }

        .appreciation h3 {
            color: #0066CC;
            margin-bottom: 10px;
        }

        .signature-section {
            margin-top: 40px;
            display: table;
            width: 100%;
        }

        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
            padding: 20px;
        }

        .signature-line {
            border-top: 2px solid #333;
            margin-top: 60px;
            padding-top: 5px;
            font-size: 10px;
        }

        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 2px solid #ddd;
            text-align: center;
            font-size: 10px;
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
        <h1>Bulletin de Notes</h1>
        <p class="subtitle">IRIS EXAM - Plateforme d'Examens en Ligne</p>
        <p class="subtitle">Annee Academique {{ now()->year }}/{{ now()->year + 1 }}</p>
    </div>

    <!-- Informations étudiant -->
    <div class="info-section">
        <h2>Informations de l'etudiant</h2>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Nom et Prenom :</div>
                <div class="info-value">{{ $user->prenom }} {{ $user->nom }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Matricule :</div>
                <div class="info-value">{{ $user->matricule }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Classe :</div>
                <div class="info-value">{{ $etudiant->classe->nom }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Date d'edition :</div>
                <div class="info-value">{{ now()->format('d/m/Y') }}</div>
            </div>
        </div>
    </div>

    <!-- Statistiques globales -->
    <div class="statistiques-box">
        <div class="stat-item">
            <span class="value">{{ $stats['moyenne_generale'] }}</span>
            <span class="label">Moyenne Generale / 20</span>
        </div>
        <div class="stat-item">
            <span class="value">{{ $stats['nb_examens'] }}</span>
            <span class="label">Examens Passes</span>
        </div>
        <div class="stat-item">
            <span class="value">{{ $stats['taux_reussite'] }}%</span>
            <span class="label">Taux de Reussite</span>
        </div>
    </div>

    <!-- Résultats par matière -->
    <div>
        <h2 style="color: #0066CC; margin-bottom: 20px; text-transform: uppercase;">Resultats par Matiere</h2>

        <table>
            <thead>
                <tr>
                    <th style="width: 40%;">Matiere</th>
                    <th class="text-center" style="width: 20%;">Nombre d'examens</th>
                    <th class="text-center" style="width: 20%;">Moyenne</th>
                    <th class="text-center" style="width: 20%;">Appreciation</th>
                </tr>
            </thead>
            <tbody>
                @foreach($moyennesParMatiere as $matiere => $data)
                    <tr>
                        <td><strong>{{ $matiere }}</strong></td>
                        <td class="text-center">{{ $data['nb_examens'] }}</td>
                        <td class="text-center note-cell {{ $data['moyenne'] >= 10 ? 'reussi' : 'echoue' }}">
                            {{ number_format($data['moyenne'], 2) }}/20
                        </td>
                        <td class="text-center">
                            @if($data['moyenne'] >= 16)
                                Tres bien
                            @elseif($data['moyenne'] >= 14)
                                Bien
                            @elseif($data['moyenne'] >= 12)
                                Assez bien
                            @elseif($data['moyenne'] >= 10)
                                Passable
                            @else
                                Insuffisant
                            @endif
                        </td>
                    </tr>
                @endforeach
                <tr style="background: #e7f3ff; font-weight: bold;">
                    <td>MOYENNE GENERALE</td>
                    <td class="text-center">{{ $stats['nb_examens'] }}</td>
                    <td class="text-center note-cell {{ $stats['moyenne_generale'] >= 10 ? 'reussi' : 'echoue' }}">
                        {{ number_format($stats['moyenne_generale'], 2) }}/20
                    </td>
                    <td class="text-center">
                        @if($stats['moyenne_generale'] >= 16)
                            Tres bien
                        @elseif($stats['moyenne_generale'] >= 14)
                            Bien
                        @elseif($stats['moyenne_generale'] >= 12)
                            Assez bien
                        @elseif($stats['moyenne_generale'] >= 10)
                            Passable
                        @else
                            Insuffisant
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Détail des examens -->
    <div class="page-break"></div>

    <h2 style="color: #0066CC; margin: 30px 0 20px 0; text-transform: uppercase;">Detail des Examens</h2>

    <table>
        <thead>
            <tr>
                <th style="width: 25%;">Examen</th>
                <th style="width: 20%;">Matiere</th>
                <th class="text-center" style="width: 15%;">Date</th>
                <th class="text-center" style="width: 15%;">Note</th>
                <th class="text-center" style="width: 10%;">%</th>
                <th class="text-center" style="width: 15%;">Resultat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sessions as $session)
                @php
                    $noteMax = $session->note_maximale ?? $session->examen->note_totale;
                    $noteSur20 = $noteMax > 0 ? ($session->note_obtenue / $noteMax) * 20 : 0;
                    $estReussi = $session->pourcentage >= $session->examen->seuil_reussite;
                @endphp
                <tr>
                    <td>{{ $session->examen->titre }}</td>
                    <td>{{ $session->examen->matiere->nom }}</td>
                    <td class="text-center">{{ $session->created_at->format('d/m/Y') }}</td>
                    <td class="text-center note-cell {{ $estReussi ? 'reussi' : 'echoue' }}">
                        {{ number_format($noteSur20, 2) }}/20
                    </td>
                    <td class="text-center">{{ round($session->pourcentage) }}%</td>
                    <td class="text-center">
                        <strong style="color: {{ $estReussi ? '#28a745' : '#dc3545' }}">
                            {{ $estReussi ? 'Reussi' : 'Echoue' }}
                        </strong>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Appréciation -->
    <div class="appreciation">
        <h3>Appreciation Generale</h3>
        <p>
            @if($stats['moyenne_generale'] >= 16)
                Excellent travail ! L'etudiant demontre une maitrise remarquable des matieres etudiees. Continuez dans cette voie exemplaire.
            @elseif($stats['moyenne_generale'] >= 14)
                Tres bon niveau. L'etudiant fait preuve de serieux et d'implication dans son travail. Resultats encourageants.
            @elseif($stats['moyenne_generale'] >= 12)
                Resultats satisfaisants. L'etudiant progresse correctement. Un effort supplementaire permettrait d'atteindre un niveau superieur.
            @elseif($stats['moyenne_generale'] >= 10)
                Resultats justes. L'etudiant doit fournir davantage d'efforts et de regularite dans son travail pour ameliorer ses performances.
            @else
                Resultats insuffisants. L'etudiant doit imperativement renforcer son travail personnel et solliciter de l'aide si necessaire.
            @endif
        </p>
        <p style="margin-top: 10px;">
            <strong>Examens reussis :</strong> {{ $stats['nb_reussis'] }} / {{ $stats['nb_examens'] }} ({{ $stats['taux_reussite'] }}%)
        </p>
    </div>

    <!-- Signatures -->
    <div class="signature-section">
        <div class="signature-box">
            <p><strong>Le Directeur Pedagogique</strong></p>
            <div class="signature-line">Signature et cachet</div>
        </div>
        <div class="signature-box">
            <p><strong>L'Etudiant</strong></p>
            <div class="signature-line">Signature</div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p><strong>IRIS EXAM</strong> - Plateforme d'Examens en Ligne</p>
        <p>Document genere le {{ now()->format('d/m/Y a H:i') }}</p>
        <p>{{ date('Y') }} - Tous droits reserves</p>
    </div>
</body>
</html>