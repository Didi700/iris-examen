<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background: #f3f4f6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #0066CC 0%, #7C3AED 100%); color: white; padding: 40px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: white; padding: 40px; border-radius: 0 0 10px 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .button { display: inline-block; padding: 15px 35px; background: #FDB913; color: #1A1A1A; text-decoration: none; border-radius: 8px; font-weight: bold; margin-top: 20px; }
        .credentials-box { background: #f9fafb; border: 2px solid #e5e7eb; padding: 20px; border-radius: 8px; margin: 25px 0; }
        .warning-box { background: #fef3c7; border-left: 4px solid #f59e0b; padding: 15px; margin: 25px 0; border-radius: 4px; }
        .footer { text-align: center; margin-top: 30px; color: #6b7280; font-size: 12px; }
        .highlight { background: #fdb913; padding: 2px 6px; border-radius: 3px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎓 Bienvenue sur IRIS EXAM</h1>
        </div>
        
        <div class="content">
            <h2>Bonjour {{ $utilisateur->prenom }} {{ $utilisateur->nom }},</h2>
            
            <p>Votre compte a été créé avec succès sur la plateforme IRIS EXAM !</p>
            
            <p>Vous pouvez maintenant vous connecter à votre espace personnel avec les identifiants ci-dessous :</p>
            
            <div class="credentials-box">
                <p><strong>📧 Email :</strong> {{ $utilisateur->email }}</p>
                <p><strong>🎫 Matricule :</strong> {{ $utilisateur->matricule }}</p>
                <p><strong>🔑 Mot de passe temporaire :</strong> <span class="highlight">{{ $motDePasseTemporaire }}</span></p>
                <p><strong>👤 Rôle :</strong> {{ ucfirst($utilisateur->role->nom) }}</p>
            </div>
            
            <div class="warning-box">
                <p><strong>⚠️ IMPORTANT :</strong></p>
                <p>Pour des raisons de sécurité, vous <strong>DEVEZ</strong> changer votre mot de passe lors de votre première connexion.</p>
            </div>
            
            <div style="text-align: center;">
                <a href="{{ url('/login') }}" class="button">🚀 Se connecter maintenant</a>
            </div>
            
            <p style="margin-top: 40px; font-size: 14px; color: #6b7280;">
                <strong>Besoin d'aide ?</strong><br>
                Contactez l'administration si vous rencontrez des difficultés pour vous connecter.
            </p>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} IRIS EXAM - Plateforme de gestion d'examens en ligne</p>
            <p style="margin-top: 10px;">Ce message a été envoyé automatiquement, merci de ne pas y répondre.</p>
        </div>
    </div>
</body>
</html>