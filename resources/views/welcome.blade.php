<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IRIS EXAM - Plateforme d'évaluation nouvelle génération</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }

        /* Navigation */
        .navbar {
            background: white;
            border-bottom: 1px solid #e5e7eb;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .nav-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 70px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .logo-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #FDB913 0%, #E49D0A 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .logo-text {
            font-size: 24px;
            font-weight: 800;
            color: #1a1a1a;
        }

        .nav-links {
            display: flex;
            gap: 16px;
        }

        .btn {
            padding: 10px 24px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 15px;
            text-decoration: none;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }

        .btn-login {
            color: #4b5563;
            background: transparent;
        }

        .btn-login:hover {
            color: #FDB913;
        }

        .btn-register {
            background: #FDB913;
            color: #1a1a1a;
            box-shadow: 0 4px 14px rgba(253, 185, 19, 0.4);
        }

        .btn-register:hover {
            background: #E49D0A;
            transform: translateY(-2px);
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #0066CC 0%, #7C3AED 50%, #6D28D9 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: -200px;
            right: -200px;
            width: 500px;
            height: 500px;
            background: #FDB913;
            opacity: 0.1;
            border-radius: 50%;
            filter: blur(100px);
        }

        .hero::after {
            content: '';
            position: absolute;
            bottom: -200px;
            left: -200px;
            width: 500px;
            height: 500px;
            background: #7C3AED;
            opacity: 0.1;
            border-radius: 50%;
            filter: blur(100px);
        }

        .hero-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 80px 24px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        .hero-content {
            color: white;
        }

        .badge {
            display: inline-block;
            padding: 12px 24px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 50px;
            font-weight: 600;
            margin-bottom: 30px;
        }

        .hero-title {
            font-size: 64px;
            font-weight: 900;
            line-height: 1.1;
            margin-bottom: 30px;
        }

        .hero-title .highlight {
            color: #FDB913;
        }

        .hero-description {
            font-size: 22px;
            line-height: 1.6;
            color: #DBEAFE;
            margin-bottom: 40px;
            max-width: 600px;
        }

        .hero-buttons {
            display: flex;
            gap: 16px;
            margin-bottom: 60px;
        }

        .btn-hero {
            padding: 18px 40px;
            font-size: 20px;
            border-radius: 16px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-hero-primary {
            background: #FDB913;
            color: #1a1a1a;
            box-shadow: 0 20px 40px rgba(253, 185, 19, 0.4);
        }

        .btn-hero-primary:hover {
            background: #E49D0A;
            transform: translateY(-4px);
            box-shadow: 0 25px 50px rgba(253, 185, 19, 0.5);
        }

        .btn-hero-secondary {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
        }

        .btn-hero-secondary:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 40px;
        }

        .stat-item {
            text-align: left;
        }

        .stat-number {
            font-size: 52px;
            font-weight: 900;
            color: #FDB913;
            margin-bottom: 8px;
        }

        .stat-label {
            color: #DBEAFE;
            font-weight: 500;
        }

        .hero-visual {
            position: relative;
        }

        .hero-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 30px;
            padding: 50px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            flex-shrink: 0;
        }

        .feature-icon.yellow { background: #FDB913; }
        .feature-icon.green { background: #10B981; }
        .feature-icon.purple { background: #7C3AED; }

        .feature-content {
            flex: 1;
        }

        .feature-bar {
            height: 16px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            margin-bottom: 8px;
        }

        .feature-bar.small {
            width: 75%;
        }

        .feature-bar.medium {
            width: 60%;
        }

        .feature-bar-mini {
            height: 12px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            width: 50%;
        }

        /* Features Section */
        .features {
            padding: 120px 24px;
            background: white;
        }

        .container {
            max-width: 1280px;
            margin: 0 auto;
        }

        .section-header {
            text-align: center;
            margin-bottom: 80px;
        }

        .section-badge {
            display: inline-block;
            padding: 8px 20px;
            background: rgba(253, 185, 19, 0.2);
            color: #E49D0A;
            border-radius: 50px;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 52px;
            font-weight: 900;
            color: #1a1a1a;
            margin-bottom: 20px;
        }

        .section-description {
            font-size: 22px;
            color: #6b7280;
            max-width: 800px;
            margin: 0 auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 32px;
        }

        .feature-card {
            background: linear-gradient(135deg, #F3F4F6 0%, #E5E7EB 100%);
            padding: 40px;
            border-radius: 24px;
            transition: all 0.3s;
            border: 2px solid transparent;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }

        .feature-card.blue {
            background: linear-gradient(135deg, #DBEAFE 0%, #BFDBFE 100%);
        }

        .feature-card.blue:hover {
            border-color: #0066CC;
        }

        .feature-card.yellow {
            background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%);
        }

        .feature-card.yellow:hover {
            border-color: #FDB913;
        }

        .feature-card.green {
            background: linear-gradient(135deg, #D1FAE5 0%, #A7F3D0 100%);
        }

        .feature-card.green:hover {
            border-color: #10B981;
        }

        .feature-card.purple {
            background: linear-gradient(135deg, #EDE9FE 0%, #DDD6FE 100%);
        }

        .feature-card.purple:hover {
            border-color: #7C3AED;
        }

        .feature-card.orange {
            background: linear-gradient(135deg, #FFEDD5 0%, #FED7AA 100%);
        }

        .feature-card.orange:hover {
            border-color: #F97316;
        }

        .feature-card.pink {
            background: linear-gradient(135deg, #FCE7F3 0%, #FBCFE8 100%);
        }

        .feature-card.pink:hover {
            border-color: #EC4899;
        }

        .feature-card-icon {
            width: 70px;
            height: 70px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
            font-size: 36px;
        }

        .feature-card-icon.blue { background: #0066CC; }
        .feature-card-icon.yellow { background: #FDB913; }
        .feature-card-icon.green { background: #10B981; }
        .feature-card-icon.purple { background: #7C3AED; }
        .feature-card-icon.orange { background: #F97316; }
        .feature-card-icon.pink { background: #EC4899; }

        .feature-card-title {
            font-size: 26px;
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 16px;
        }

        .feature-card-text {
            color: #4b5563;
            line-height: 1.7;
            font-size: 16px;
        }

        /* CTA Section */
        .cta {
            background: linear-gradient(135deg, #0066CC 0%, #7C3AED 50%, #6D28D9 100%);
            padding: 120px 24px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .cta::before,
        .cta::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            opacity: 0.1;
        }

        .cta::before {
            width: 400px;
            height: 400px;
            background: #FDB913;
            top: -100px;
            left: 20%;
        }

        .cta::after {
            width: 400px;
            height: 400px;
            background: white;
            bottom: -100px;
            right: 20%;
        }

        .cta-content {
            max-width: 900px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .cta-title {
            font-size: 56px;
            font-weight: 900;
            color: white;
            margin-bottom: 24px;
            line-height: 1.2;
        }

        .cta-description {
            font-size: 22px;
            color: #DBEAFE;
            margin-bottom: 40px;
        }

        .cta-note {
            color: #DBEAFE;
            font-size: 14px;
            margin-top: 16px;
        }

        /* Footer */
        .footer {
            background: #1a1a1a;
            color: white;
            padding: 60px 24px 30px;
        }

        .footer-container {
            max-width: 1280px;
            margin: 0 auto;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 60px;
            margin-bottom: 60px;
        }

        .footer-brand {
            max-width: 300px;
        }

        .footer-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }

        .footer-text {
            color: #9ca3af;
            line-height: 1.7;
        }

        .footer-title {
            font-weight: 700;
            margin-bottom: 20px;
            font-size: 16px;
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 12px;
        }

        .footer-links a {
            color: #9ca3af;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: #FDB913;
        }

        .footer-bottom {
            border-top: 1px solid #374151;
            padding-top: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .footer-copyright {
            color: #9ca3af;
            font-size: 14px;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .hero-container {
                grid-template-columns: 1fr;
            }

            .hero-visual {
                display: none;
            }

            .features-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .footer-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 42px;
            }

            .hero-description {
                font-size: 18px;
            }

            .hero-buttons {
                flex-direction: column;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .section-title {
                font-size: 36px;
            }

            .cta-title {
                font-size: 36px;
            }

            .footer-grid {
                grid-template-columns: 1fr;
            }

            .footer-bottom {
                flex-direction: column;
                gap: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="{{ route('home') }}" class="logo">
                <div class="logo-icon">📚</div>
                <span class="logo-text">IRIS EXAM</span>
            </a>

            <div class="nav-links">
                <a href="{{ route('login') }}" class="btn btn-login">Connexion</a>
                <!-- <a href="{{ route('register') }}" class="btn btn-register">Inscription</a> -->
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-container">
            <div class="hero-content">
                <div class="badge">🎓 Plateforme N°1 pour vos examens</div>
                
                <h1 class="hero-title">
                    Plateforme d'évaluation <span class="highlight">nouvelle génération</span>
                </h1>
                
                <p class="hero-description">
                    IRIS EXAM révolutionne la manière de créer, gérer et passer des examens en ligne. 
                    <strong>Simple, sécurisé et efficace pour tous.</strong>
                </p>
                
                <div class="hero-buttons">
                    <a href="{{ route('register') }}" class="btn-hero btn-hero-primary">
                        🚀 Commencer gratuitement →
                    </a>
                    <a href="{{ route('login') }}" class="btn-hero btn-hero-secondary">
                        Se connecter
                    </a>
                </div>

                <div class="stats">
                    <div class="stat-item">
                        <div class="stat-number">500+</div>
                        <div class="stat-label">Étudiants actifs</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">50+</div>
                        <div class="stat-label">Enseignants</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">1000+</div>
                        <div class="stat-label">Examens créés</div>
                    </div>
                </div>
            </div>

            <div class="hero-visual">
                <div class="hero-card">
                    <div class="feature-item">
                        <div class="feature-icon yellow">📝</div>
                        <div class="feature-content">
                            <div class="feature-bar"></div>
                            <div class="feature-bar-mini"></div>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon green">✅</div>
                        <div class="feature-content">
                            <div class="feature-bar small"></div>
                            <div class="feature-bar-mini"></div>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon purple">📊</div>
                        <div class="feature-content">
                            <div class="feature-bar medium"></div>
                            <div class="feature-bar-mini"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <div class="section-header">
                <span class="section-badge">NOS AVANTAGES</span>
                <h2 class="section-title">Pourquoi choisir IRIS EXAM ?</h2>
                <p class="section-description">
                    Une solution complète qui transforme vos évaluations en expérience moderne et efficace
                </p>
            </div>

            <div class="features-grid">
                <div class="feature-card blue">
                    <div class="feature-card-icon blue">⚡</div>
                    <h3 class="feature-card-title">Création ultra-rapide</h3>
                    <p class="feature-card-text">
                        Créez vos examens en quelques minutes. QCM, questions ouvertes, vrai/faux... tout est possible !
                    </p>
                </div>

                <div class="feature-card yellow">
                    <div class="feature-card-icon yellow">🛡️</div>
                    <h3 class="feature-card-title">Sécurité maximale</h3>
                    <p class="feature-card-text">
                        Système anti-triche avancé : surveillance, détection de changement d'onglet, verrouillage.
                    </p>
                </div>

                <div class="feature-card green">
                    <div class="feature-card-icon green">📊</div>
                    <h3 class="feature-card-title">Statistiques détaillées</h3>
                    <p class="feature-card-text">
                        Analysez les performances avec des graphiques complets. Identifiez les points d'amélioration.
                    </p>
                </div>

                <div class="feature-card purple">
                    <div class="feature-card-icon purple">⚡</div>
                    <h3 class="feature-card-title">Correction automatique</h3>
                    <p class="feature-card-text">
                        Les QCM sont corrigés instantanément. Libérez du temps pour l'essentiel.
                    </p>
                </div>

                <div class="feature-card orange">
                    <div class="feature-card-icon orange">⏰</div>
                    <h3 class="feature-card-title">Planification flexible</h3>
                    <p class="feature-card-text">
                        Programmez vos examens à l'avance. Contrôlez la durée et gérez les tentatives.
                    </p>
                </div>

                <div class="feature-card pink">
                    <div class="feature-card-icon pink">👥</div>
                    <h3 class="feature-card-title">Gestion multi-classes</h3>
                    <p class="feature-card-text">
                        Organisez vos étudiants par classe et matière. Attribuez les examens en un clic.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="cta-content">
            <h2 class="cta-title">Prêt à révolutionner vos évaluations ?</h2>
            <p class="cta-description">
                Rejoignez des centaines d'établissements qui font déjà confiance à IRIS EXAM
            </p>
            <a href="{{ route('register') }}" class="btn-hero btn-hero-primary">
                Créer mon compte gratuitement →
            </a>
            <p class="cta-note">✨ Aucune carte bancaire requise • Essai gratuit illimité</p>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <div class="footer-logo">
                        <div class="logo-icon">📚</div>
                        <span class="logo-text">IRIS EXAM</span>
                    </div>
                    <p class="footer-text">
                        La plateforme d'évaluation nouvelle génération pour vos examens en ligne.
                    </p>
                </div>

                <div>
                    <h3 class="footer-title">Produit</h3>
                    <ul class="footer-links">
                        <li><a href="#">Fonctionnalités</a></li>
                        <li><a href="#">Tarifs</a></li>
                        <li><a href="#">Sécurité</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="footer-title">Ressources</h3>
                    <ul class="footer-links">
                        <li><a href="#">Documentation</a></li>
                        <li><a href="#">Aide</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="footer-title">Légal</h3>
                    <ul class="footer-links">
                        <li><a href="#">CGU</a></li>
                        <li><a href="#">Confidentialité</a></li>
                        <li><a href="#">Mentions légales</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p class="footer-copyright">© 2025 IRIS EXAM - Tous droits réservés</p>
            </div>
        </div>
    </footer>
</body>
</html>