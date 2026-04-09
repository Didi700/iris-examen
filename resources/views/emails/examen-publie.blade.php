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
            background: linear-gradient(135deg, #0066CC 0%, #7C3AED 100%);
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
        .info-box {
            background: white;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            border-left: 4px solid #FDB913;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📚 IRIS EXAM</h1>
            <h2>Nouvel examen disponible !</h2>
        </div>
        
        <div class="content">
            <p>Bonjour,</p>
            
            <p>Un nouvel examen vient d'être publié et vous devez le passer.</p>
            
            <div class="info-box">
                <h3 style="margin-top: 0;">📝 {{ $examen->titre }}</h3>
                <p><strong>Matière :</strong> {{ $examen->matiere->nom }}</p>
                <p><strong>Classe :</strong> {{ $examen->classe->nom }}</p>
                <p><strong>Durée :</strong> {{ $examen->duree_minutes }} minutes</p>
                <p><strong>Questions :</strong> {{ $examen->questions->count() }}</p>
                <p><strong>📅 Date de début :</strong> {{ $examen->date_debut->format('d/m/Y à H:i') }}</p>
                <p><strong>⏰ Date limite :</strong> {{ $examen->date_fin->format('d/m/Y à H:i') }}</p>
            </div>
            
            <p><strong>⚠️ Attention :</strong> N'oubliez pas de passer cet examen avant la date limite !</p>
            
            <center>
                <a href="{{ url('/etudiant/examens/' . $examen->id) }}" class="button">
                    Voir l'examen →
                </a>
            </center>
            
            <p style="margin-top: 30px; font-size: 12px; color: #666;">
                Cet email a été envoyé automatiquement par IRIS EXAM.<br>
                Pour toute question, contactez votre enseignant.
            </p>
        </div>
    </div>
</body>
</html>