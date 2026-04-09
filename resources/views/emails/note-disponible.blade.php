<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
            color: white;
            padding: 30px;
            border-radius: 10px 10px 0 0;
            text-align: center;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .note-box {
            background: white;
            padding: 30px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
            border: 3px solid #FDB913;
        }
        .note {
            font-size: 48px;
            font-weight: bold;
            color: #0066CC;
        }
        .button {
            display: inline-block;
            padding: 15px 30px;
            background: #FDB913;
            color: #1A1A1A;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📊 IRIS EXAM</h1>
            <h2>Votre note est disponible !</h2>
        </div>
        
        <div class="content">
            <p>Bonjour {{ $session->etudiant->prenom }},</p>
            
            <p>Votre copie pour l'examen <strong>{{ $session->examen->titre }}</strong> a été corrigée.</p>
            
            <div class="note-box">
                <p style="margin: 0; color: #666; font-size: 14px;">Votre note</p>
                <p class="note">
                    {{ number_format(($session->note_obtenue / $session->note_maximale) * 20, 2) }}/20
                </p>
                <p style="margin: 0; color: #666;">
                    ({{ $session->note_obtenue }}/{{ $session->note_maximale }} points)
                </p>
            </div>
            
            @if($session->commentaire_enseignant)
            <div style="background: white; padding: 15px; border-radius: 8px; margin: 15px 0; border-left: 4px solid #0066CC;">
                <p style="margin: 0;"><strong>💬 Commentaire de l'enseignant :</strong></p>
                <p style="margin-top: 10px;">{{ $session->commentaire_enseignant }}</p>
            </div>
            @endif
            
            <center>
                <a href="{{ url('/etudiant/examens/' . $session->examen_id . '/resultat') }}" class="button">
                    Voir les détails →
                </a>
            </center>
            
            <p style="margin-top: 30px; font-size: 12px; color: #666;">
                Cet email a été envoyé automatiquement par IRIS EXAM.
            </p>
        </div>
    </div>
</body>
</html>