<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificat de Réussite</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        @page {
            margin: 0;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            width: 100%;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .certificate {
            width: 90%;
            max-width: 1000px;
            background: white;
            padding: 60px;
            border: 15px solid #f8b400;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            position: relative;
        }
        
        .certificate::before {
            content: '';
            position: absolute;
            top: 30px;
            left: 30px;
            right: 30px;
            bottom: 30px;
            border: 3px solid #f8b400;
            pointer-events: none;
        }
        
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .logo {
            font-size: 48pt;
            margin-bottom: 20px;
        }
        
        .title {
            font-size: 42pt;
            font-weight: bold;
            color: #667eea;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-bottom: 10px;
        }
        
        .subtitle {
            font-size: 18pt;
            color: #764ba2;
            font-style: italic;
        }
        
        .divider {
            width: 200px;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            margin: 30px auto;
        }
        
        .content {
            text-align: center;
            padding: 40px 0;
        }
        
        .presented-to {
            font-size: 16pt;
            color: #666;
            margin-bottom: 20px;
        }
        
        .student-name {
            font-size: 36pt;
            font-weight: bold;
            color: #333;
            margin: 20px 0;
            text-transform: uppercase;
        }
        
        .achievement {
            font-size: 14pt;
            color: #555;
            line-height: 1.8;
            margin: 30px 0;
            padding: 0 50px;
        }
        
        .details {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 30px auto;
            max-width: 600px;
            text-align: left;
        }
        
        .details table {
            width: 100%;
        }
        
        .details td {
            padding: 8px;
            font-size: 12pt;
        }
        
        .details .label {
            font-weight: bold;
            color: #667eea;
            width: 200px;
        }
        
        .grade {
            font-size: 48pt;
            font-weight: bold;
            color: #16a34a;
            margin: 30px 0;
        }
        
        .footer {
            margin-top: 50px;
            display: flex;
            justify-content: space-around;
        }
        
        .signature-block {
            text-align: center;
        }
        
        .signature-line {
            width: 200px;
            border-top: 2px solid #333;
            margin: 50px auto 10px;
            padding-top: 10px;
        }
        
        .signature-name {
            font-weight: bold;
            font-size: 12pt;
        }
        
        .signature-title {
            font-size: 10pt;
            color: #666;
            font-style: italic;
        }
        
        .date-issued {
            text-align: center;
            margin-top: 40px;
            font-size: 11pt;
            color: #666;
        }
        
        .seal {
            position: absolute;
            bottom: 80px;
            right: 80px;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: #f8b400;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14pt;
            font-weight: bold;
            color: white;
            text-align: center;
            line-height: 1.2;
            transform: rotate(-15deg);
            border: 5px solid #d4940a;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <!-- En-tête -->
        <div class="header">
            <div class="logo">🎓</div>
            <h1 class="title">Certificat</h1>
            <p class="subtitle">de Réussite</p>
        </div>
        
        <div class="divider"></div>
        
        <!-- Contenu -->
        <div class="content">
            <p class="presented-to">Ce certificat est décerné à</p>
            
            <h2 class="student-name">{{ $etudiant->nom_complet }}</h2>
            
            <p class="achievement">
                Pour avoir réussi avec succès l'examen<br>
                <strong style="font-size: 16pt; color: #667eea;">{{ $session->examen->titre }}</strong><br>
                dans le cadre de la matière <strong>{{ $session->examen->matiere->nom ?? 'N/A' }}</strong>
            </p>
            
            <!-- Détails -->
            <div class="details">
                <table>
                    <tr>
                        <td class="label">Matricule :</td>
                        <td>{{ $etudiant->matricule }}</td>
                    </tr>
                    <tr>
                        <td class="label">Classe :</td>
                        <td>{{ $session->examen->classe->nom ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Date de l'examen :</td>
                        <td>{{ $session->date_soumission->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td class="label">Durée :</td>
                        <td>{{ $session->examen->duree }} minutes</td>
                    </tr>
                    <tr>
                        <td class="label">Note obtenue :</td>
                        <td><strong style="color: #16a34a;">{{ number_format($session->note_obtenue, 2) }}/{{ $session->note_maximale ?? $session->examen->note_totale }}</strong></td>
                    </tr>
                </table>
            </div>
            
            <!-- Note -->
            <div class="grade">
                {{ number_format($session->pourcentage, 2) }}%
            </div>
            
            <p style="color: #16a34a; font-size: 14pt; font-weight: bold;">
                ✓ EXAMEN RÉUSSI
            </p>
        </div>
        
        <!-- Signatures -->
        <div class="footer">
            <div class="signature-block">
                <div class="signature-line">
                    <p class="signature-name">{{ $session->examen->enseignant->nom_complet ?? 'Enseignant' }}</p>
                    <p class="signature-title">Enseignant Responsable</p>
                </div>
            </div>
            
            <div class="signature-block">
                <div class="signature-line">
                    <p class="signature-name">Direction</p>
                    <p class="signature-title">Directeur des Études</p>
                </div>
            </div>
        </div>
        
        <!-- Date -->
        <div class="date-issued">
            Délivré le {{ $date_generation->format('d F Y') }} à Paris
        </div>
        
        <!-- Sceau -->
        <div class="seal">
            IRIS<br>PARIS
        </div>
    </div>
</body>
</html>