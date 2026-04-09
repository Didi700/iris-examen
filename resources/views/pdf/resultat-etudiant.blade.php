<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Résultat - {{ $session->etudiant->prenom }} {{ $session->etudiant->nom }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            line-height: 1.6;
            color: #333;
        }
        
        .header {
            background: linear-gradient(135deg, #0066CC 0%, #7C3AED 100%);
            color: white;
            padding: 40px;
            text-align: center;
            margin-bottom: 30px;
        }
        
        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .header p {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .info-box {
            background: #f8f9fa;
            border-left: 4px solid #FDB913;
            padding: 20px;
            margin-bottom: 20px;
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
            padding: 8px 10px 8px 0;
            width: 40%;
        }
        
        .info-value {
            display: table-cell;
            padding: 8px 0;
        }
        
        .note-box {
            background: #e0f2fe;
            border: 3px solid #0066CC;
            padding: 30px;
            text-align: center;
            margin: 30px 0;
        }
        
        .note-principale {
            font-size: 48px;
            font-weight: bold;
            color: #0066CC;
            margin: 10px 0;
        }
        
        .mention {
            font-size: 16px;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 5px;
            display: inline-block;
            margin-top: 10px;
        }
        
        .mention-excellente {
            background: #10B981;
            color: white;
        }
        
        .mention-bonne {
            background: #0066CC;
            color: white;
        }
        
        .mention-moyenne {
            background: #F59E0B;
            color: white;
        }
        
        .mention-insuffisante {
            background: #EF4444;
            color: white;
        }
        
        .commentaire-box {
            background: white;
            border: 2px solid #e5e7eb;
            padding: 20px;
            margin: 20px 0;
        }
        
        .commentaire-box h3 {
            color: #0066CC;
            margin-bottom: 10px;
        }
        
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        
        .signature {
            margin-top: 40px;
            text-align: right;
        }
        
        .signature p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <!-- En-tête -->
    <div class="header">
        <h1>🎓 RELEVÉ DE NOTES</h1>
        <p>{{ $session->examen->titre }}</p>
    </div>

    <!-- Informations étudiant -->
    <div class="info-box">
        <h3 style="color: #0066CC; margin-bottom: 15px;">👤 Informations de l'étudiant</h3>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Nom complet :</div>
                <div class="info-value">{{ $session->etudiant->prenom }} {{ $session->etudiant->nom }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Matricule :</div>
                <div class="info-value">{{ $session->etudiant->matricule }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Classe :</div>
                <div class="info-value">{{ $session->examen->classe->nom }}</div>
            </div>
        </div>
    </div>

    <!-- Informations examen -->
    <div class="info-box">
        <h3 style="color: #0066CC; margin-bottom: 15px;">📝 Informations de l'examen</h3>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Matière :</div>
                <div class="info-value">{{ $session->examen->matiere->nom }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Enseignant :</div>
                <div class="info-value">{{ $session->examen->enseignant->prenom }} {{ $session->examen->enseignant->nom }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Date de passage :</div>
                <div class="info-value">{{ $session->created_at->format('d/m/Y à H:i') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Durée :</div>
                <div class="info-value">{{ $session->examen->duree_minutes }} minutes</div>
            </div>
        </div>
    </div>

    <!-- Note obtenue -->
    @php
        $noteMax = $session->note_maximale ?? $session->examen->note_totale;
        $noteSur20 = ($session->note_obtenue / $noteMax) * 20;
        $reussi = $noteSur20 >= 10;
        
        // Déterminer la mention
        if ($noteSur20 >= 16) {
            $mention = 'Très bien';
            $mentionClass = 'mention-excellente';
        } elseif ($noteSur20 >= 14) {
            $mention = 'Bien';
            $mentionClass = 'mention-bonne';
        } elseif ($noteSur20 >= 12) {
            $mention = 'Assez bien';
            $mentionClass = 'mention-moyenne';
        } elseif ($noteSur20 >= 10) {
            $mention = 'Passable';
            $mentionClass = 'mention-moyenne';
        } else {
            $mention = 'Insuffisant';
            $mentionClass = 'mention-insuffisante';
        }
    @endphp

    <div class="note-box">
        <p style="font-size: 14px; color: #666;">Note obtenue</p>
        <div class="note-principale">{{ number_format($noteSur20, 2) }}/20</div>
        <p style="font-size: 12px; color: #666; margin-top: 5px;">
            ({{ number_format($session->note_obtenue, 2) }}/{{ $noteMax }} points)
        </p>
        <div class="mention {{ $mentionClass }}">
            @if($reussi) ✓ @else ✗ @endif {{ $mention }}
        </div>
    </div>

    <!-- Commentaire enseignant -->
    @if($session->commentaire_enseignant)
        <div class="commentaire-box">
            <h3>💬 Commentaire de l'enseignant</h3>
            <p>{{ $session->commentaire_enseignant }}</p>
        </div>
    @endif

    <!-- Signature -->
    <div class="signature">
        <p><strong>L'enseignant</strong></p>
        <p style="margin-top: 40px;">_________________________</p>
        <p>{{ $session->examen->enseignant->prenom }} {{ $session->examen->enseignant->nom }}</p>
    </div>

    <!-- Pied de page -->
    <div class="footer">
        <p>Document généré le {{ now()->format('d/m/Y à H:i') }}</p>
        <p>© {{ now()->year }} IRIS EXAM - Plateforme de gestion d'examens en ligne</p>
    </div>
</body>
</html>