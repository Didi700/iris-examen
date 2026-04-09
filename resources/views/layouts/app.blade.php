<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'IRIS EXAM')</title>
    
    <!-- Dark Mode Script - IMPORTANT: Avant le body -->
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'iris-yellow': {
                            DEFAULT: '#FDB913',
                            600: '#E49D0A',
                        },
                        'iris-blue': {
                            DEFAULT: '#0066CC',
                            600: '#0052A3',
                        },
                        'iris-black': {
                            DEFAULT: '#1A1A1A',
                            900: '#1A1A1A',
                        },
                    }
                }
            }
        }
    </script>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        body { 
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
        }
        html {
            margin: 0;
            padding: 0;
        }
        
        /* Lazy Loading Images */
        img[loading="lazy"] {
            opacity: 0;
            transition: opacity 0.3s;
        }
        
        img[loading="lazy"].loaded {
            opacity: 1;
        }
        
        /* Skeleton Loader */
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }
        
        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        /* Alpine.js cloak */
        [x-cloak] { display: none !important; }
    </style>
    
    @stack('styles')

    <script>
        // Lazy loading des images
        document.addEventListener('DOMContentLoaded', function() {
            const images = document.querySelectorAll('img[loading="lazy"]');
            images.forEach(img => {
                img.addEventListener('load', function() {
                    this.classList.add('loaded');
                });
                if (img.complete) {
                    img.classList.add('loaded');
                }
            });
        });
    </script>
</head>
<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
    <!-- ✅ NAVIGATION RESPONSIVE AVEC HAMBURGER -->
    <nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 sticky top-0 z-50 shadow-sm transition-colors duration-200" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-3">
                        <div class="w-20 h-16 rounded-lg overflow-hidden flex-shrink-0">
                            <!-- <span class="text-white font-bold text-xl">📚</span>-->
                            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-full h-full object-cover">
                        </div>
                        <span class="text-xl font-bold text-gray-900 dark:text-white">IRIS EXAM</span>
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                @auth
                    <div class="hidden md:flex items-center space-x-1">
                        @if(auth()->user()->estEnseignant())
                            <a href="{{ route('enseignant.dashboard') }}" 
                               class="px-3 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('enseignant.dashboard') ? 'bg-iris-yellow text-gray-900' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                🏠 Dashboard
                            </a>
                            
                            <a href="{{ route('enseignant.classes.index') }}" 
                               class="px-3 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('enseignant.classes.*') ? 'bg-iris-yellow text-gray-900' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                👥 Classes
                            </a>
                            
                            <a href="{{ route('enseignant.examens.index') }}" 
                               class="px-3 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('enseignant.examens.*') ? 'bg-iris-yellow text-gray-900' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                📝 Examens
                            </a>
                            
                            <a href="{{ route('enseignant.questions.index') }}" 
                               class="px-3 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('enseignant.questions.*') ? 'bg-iris-yellow text-gray-900' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                ❓ Questions
                            </a>

                            <a href="{{ route('enseignant.import-export.index') }}" 
                               class="px-3 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('enseignant.import-export.*') ? 'bg-iris-yellow text-gray-900' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                📥 Import
                            </a>
                            
                            <a href="{{ route('enseignant.corrections.index') }}" 
                               class="px-3 py-2 rounded-lg text-sm font-medium transition-all relative {{ request()->routeIs('enseignant.corrections.*') ? 'bg-iris-yellow text-gray-900' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                ✏️ Corrections
                                @php
                                    $nbCorrections = 0;
                                    try {
                                        if (\Illuminate\Support\Facades\Schema::hasColumn('sessions_examen', 'statut_correction')) {
                                            $nbCorrections = \App\Models\SessionExamen::whereHas('examen', function($q) {
                                                $q->where('enseignant_id', auth()->id());
                                            })
                                            ->where('statut', 'termine')
                                            ->where('statut_correction', 'en_attente')
                                            ->count();
                                        }
                                    } catch (\Exception $e) {
                                        $nbCorrections = 0;
                                    }
                                @endphp
                                @if($nbCorrections > 0)
                                    <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">
                                        {{ $nbCorrections }}
                                    </span>
                                @endif
                            </a>

                            {{-- ✅ LIEN ALERTES AVEC BADGE --}}
                            <a href="{{ route('enseignant.alertes.index') }}" 
                               class="px-3 py-2 rounded-lg text-sm font-medium transition-all relative {{ request()->routeIs('enseignant.alertes.*') ? 'bg-iris-yellow text-gray-900' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                🚨 Alertes
                                @php
                                    $nbAlertes = 0;
                                    try {
                                        $nbAlertes = \App\Models\SessionExamen::whereHas('examen', function($q) {
                                            $q->where('enseignant_id', auth()->id());
                                        })
                                        ->avecAlertes()
                                        ->sansDecision()
                                        ->count();
                                    } catch (\Exception $e) {
                                        $nbAlertes = 0;
                                    }
                                @endphp
                                @if($nbAlertes > 0)
                                    <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">
                                        {{ $nbAlertes }}
                                    </span>
                                @endif
                            </a>

                            <a href="{{ route('enseignant.statistiques.index') }}" 
                               class="px-3 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('enseignant.statistiques.*') ? 'bg-iris-yellow text-gray-900' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                📊 Stats
                            </a>

                        @elseif(auth()->user()->estEtudiant())
                            <a href="{{ route('etudiant.dashboard') }}" 
                               class="px-3 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('etudiant.dashboard') ? 'bg-iris-yellow text-gray-900' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                🏠 Dashboard
                            </a>
                            
                            <a href="{{ route('etudiant.calendrier') }}" 
                               class="px-3 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('etudiant.calendrier') ? 'bg-iris-yellow text-gray-900' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                📅 Calendrier
                            </a>
                            
                            <a href="{{ route('etudiant.examens.index') }}" 
                               class="px-3 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('etudiant.examens.*') ? 'bg-iris-yellow text-gray-900' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                📝 Examens
                            </a>

                            <a href="{{ route('etudiant.resultats.index') }}" 
                               class="px-3 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('etudiant.resultats.*') ? 'bg-iris-yellow text-gray-900' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                📊 Résultats
                            </a>

                        @elseif(auth()->user()->estAdmin())
                            <a href="{{ route('admin.dashboard') }}" 
                               class="px-3 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-iris-yellow text-gray-900' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                🏠 Dashboard
                            </a>
                            
                            <a href="{{ route('admin.utilisateurs.index') }}" 
                               class="px-3 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('admin.utilisateurs.*') ? 'bg-iris-yellow text-gray-900' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                👥 Utilisateurs
                            </a>
                            
                            <a href="{{ route('admin.classes.index') }}" 
                               class="px-3 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('admin.classes.*') ? 'bg-iris-yellow text-gray-900' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                🎓 Classes
                            </a>
                            
                            <a href="{{ route('admin.matieres.index') }}" 
                               class="px-3 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('admin.matieres.*') ? 'bg-iris-yellow text-gray-900' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                📚 Matières
                            </a>
                        @endif
                    </div>
                @endauth

                <!-- Right Side (Icons + User Menu) -->
                <div class="flex items-center space-x-2">
                    @auth
                        <!-- Dark Mode Toggle -->
                        <button id="theme-toggle" 
                                class="p-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                            </svg>
                            <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
                            </svg>
                        </button>

                        <!-- 🔔 NOTIFICATIONS (Hidden on mobile) -->
                        <div x-data="{ open: false }" class="relative hidden sm:block">
                            <button @click="open = !open" 
                                    class="relative p-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                
                                @php
                                    try {
                                        $unreadCount = auth()->user()->unreadNotifications()->count();
                                    } catch (\Exception $e) {
                                        $unreadCount = 0;
                                    }
                                @endphp
                                
                                @if($unreadCount > 0)
                                    <span class="absolute top-0 right-0 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full">
                                        {{ $unreadCount }}
                                    </span>
                                @endif
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition
                                 x-cloak
                                 class="absolute right-0 mt-2 w-96 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 z-50">
                                
                                <!-- Header -->
                                <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center justify-between">
                                        <h3 class="font-semibold text-gray-900 dark:text-white">Notifications</h3>
                                        @if($unreadCount > 0)
                                            <form action="{{ route('notifications.readAll') }}" method="POST">
                                                @csrf
                                                <button type="submit" class="text-xs text-iris-blue hover:text-blue-700 font-medium">
                                                    Tout marquer lu
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>

                                <!-- Liste -->
                                <div class="max-h-96 overflow-y-auto">
                                    @php
                                        try {
                                            $recentNotifications = auth()->user()->unreadNotifications()->take(5)->get();
                                        } catch (\Exception $e) {
                                            $recentNotifications = collect([]);
                                        }
                                    @endphp
                                    
                                    @forelse($recentNotifications as $notification)
                                        @php 
                                            $data = $notification->data ?? [];
                                        @endphp
                                        <a href="#" 
                                           onclick="event.preventDefault(); document.getElementById('notif-form-{{ $notification->id }}').submit();"
                                           class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 border-b border-gray-200 dark:border-gray-700 transition-colors">
                                            <div class="flex items-start space-x-3">
                                                <div class="text-2xl">{{ $data['icon'] ?? '🔔' }}</div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $data['message'] ?? 'Nouvelle notification' }}
                                                    </p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                        {{ $notification->created_at->diffForHumans() }}
                                                    </p>
                                                </div>
                                                <span class="flex-shrink-0 w-2 h-2 bg-blue-600 rounded-full"></span>
                                            </div>
                                        </a>
                                        <form id="notif-form-{{ $notification->id }}" action="{{ route('notifications.read', $notification->id) }}" method="POST" class="hidden">
                                            @csrf
                                        </form>
                                    @empty
                                        <div class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                            <div class="text-4xl mb-2">🔕</div>
                                            <p class="text-sm">Aucune notification</p>
                                        </div>
                                    @endforelse
                                </div>

                                <!-- Footer -->
                                <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                                    <a href="{{ route('notifications.index') }}" 
                                       class="block text-center text-sm text-iris-blue hover:text-blue-700 font-medium">
                                        Voir toutes les notifications
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- ✅ BOUTON HAMBURGER (Mobile only) -->
                        <button @click="mobileMenuOpen = !mobileMenuOpen" 
                                class="md:hidden p-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        <!-- Menu utilisateur (Desktop) -->
                        <div class="relative hidden md:block" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    class="flex items-center space-x-2 px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                                    <span class="text-white font-bold text-xs">
                                        {{ substr(auth()->user()->prenom, 0, 1) }}{{ substr(auth()->user()->nom, 0, 1) }}
                                    </span>
                                </div>
                                <svg class="h-4 w-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition
                                 x-cloak
                                 class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-lg shadow-lg py-2 border border-gray-200 dark:border-gray-700">
                                
                                <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ auth()->user()->prenom }} {{ auth()->user()->nom }}</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">{{ auth()->user()->email }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">{{ auth()->user()->matricule }}</p>
                                </div>

                                <a href="{{ route('profil.changer-mot-de-passe') }}" 
                                   class="w-full flex items-center space-x-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <svg class="h-5 w-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                    </svg>
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Changer mot de passe</span>
                                </a>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center space-x-3 px-4 py-3 hover:bg-red-50 dark:hover:bg-red-900 transition-colors text-left border-t border-gray-200 dark:border-gray-700">
                                        <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        <span class="text-sm text-red-600 font-semibold">Déconnexion</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('login') }}" 
                               class="px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:text-iris-yellow transition-colors">
                                Connexion
                            </a>
                            <a href="{{ route('register') }}" 
                               class="px-4 py-2 bg-iris-yellow text-gray-900 rounded-lg text-sm font-bold hover:bg-yellow-600 transition-all shadow-lg">
                                Inscription
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>

        <!-- ✅ MENU MOBILE (Hamburger Menu) -->
        @auth
            <div x-show="mobileMenuOpen" 
                 x-transition
                 x-cloak
                 class="md:hidden border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                <div class="px-4 pt-2 pb-4 space-y-1">
                    <!-- User Info Mobile -->
                    <div class="px-3 py-3 border-b border-gray-200 dark:border-gray-700 mb-2">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ auth()->user()->prenom }} {{ auth()->user()->nom }}</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">{{ ucfirst(auth()->user()->role->nom) }}</p>
                    </div>

                    @if(auth()->user()->estEnseignant())
                        <a href="{{ route('enseignant.dashboard') }}" 
                           class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('enseignant.dashboard') ? 'bg-iris-yellow text-gray-900' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            🏠 Dashboard
                        </a>
                        <a href="{{ route('enseignant.classes.index') }}" 
                           class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('enseignant.classes.*') ? 'bg-iris-yellow text-gray-900' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            👥 Classes
                        </a>
                        <a href="{{ route('enseignant.examens.index') }}" 
                           class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('enseignant.examens.*') ? 'bg-iris-yellow text-gray-900' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            📝 Examens
                        </a>
                        <a href="{{ route('enseignant.questions.index') }}" 
                           class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('enseignant.questions.*') ? 'bg-iris-yellow text-gray-900' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            ❓ Questions
                        </a>
                        <a href="{{ route('enseignant.import-export.index') }}" 
                           class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('enseignant.import-export.*') ? 'bg-iris-yellow text-gray-900' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            📥 Import/Export
                        </a>
                        <a href="{{ route('enseignant.corrections.index') }}" 
                           class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('enseignant.corrections.*') ? 'bg-iris-yellow text-gray-900' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            ✏️ Corrections
                        </a>
                        {{-- ✅ LIEN ALERTES MOBILE --}}
                        <a href="{{ route('enseignant.alertes.index') }}" 
                           class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('enseignant.alertes.*') ? 'bg-iris-yellow text-gray-900' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            🚨 Alertes
                        </a>
                        <a href="{{ route('enseignant.statistiques.index') }}" 
                           class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('enseignant.statistiques.*') ? 'bg-iris-yellow text-gray-900' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            📊 Statistiques
                        </a>

                    @elseif(auth()->user()->estEtudiant())
                        <a href="{{ route('etudiant.dashboard') }}" 
                           class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('etudiant.dashboard') ? 'bg-iris-yellow text-gray-900' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            🏠 Dashboard
                        </a>
                        <a href="{{ route('etudiant.calendrier') }}" 
                           class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('etudiant.calendrier') ? 'bg-iris-yellow text-gray-900' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            📅 Calendrier
                        </a>
                        <a href="{{ route('etudiant.examens.index') }}" 
                           class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('etudiant.examens.*') ? 'bg-iris-yellow text-gray-900' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            📝 Examens
                        </a>
                        <a href="{{ route('etudiant.resultats.index') }}" 
                           class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('etudiant.resultats.*') ? 'bg-iris-yellow text-gray-900' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            📊 Résultats
                        </a>

                    @elseif(auth()->user()->estAdmin())
                        <a href="{{ route('admin.dashboard') }}" 
                           class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-iris-yellow text-gray-900' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            🏠 Dashboard
                        </a>
                        <a href="{{ route('admin.utilisateurs.index') }}" 
                           class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('admin.utilisateurs.*') ? 'bg-iris-yellow text-gray-900' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            👥 Utilisateurs
                        </a>
                        <a href="{{ route('admin.classes.index') }}" 
                           class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('admin.classes.*') ? 'bg-iris-yellow text-gray-900' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            🎓 Classes
                        </a>
                        <a href="{{ route('admin.matieres.index') }}" 
                           class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('admin.matieres.*') ? 'bg-iris-yellow text-gray-900' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            📚 Matières
                        </a>
                    @endif

                    <!-- Actions Mobile -->
                    <div class="pt-3 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('profil.changer-mot-de-passe') }}" 
                           class="block px-3 py-2 rounded-lg text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            🔑 Changer mot de passe
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-3 py-2 rounded-lg text-base font-medium text-red-600 hover:bg-red-50 dark:hover:bg-red-900">
                                🚪 Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endauth
    </nav>

    <!-- Messages Flash -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        @if(session('success'))
            <div x-data="{ show: true }" 
                 x-show="show"
                 x-transition
                 x-init="setTimeout(() => show = false, 5000)"
                 class="bg-green-50 dark:bg-green-900 border-l-4 border-green-400 p-4 rounded-lg mb-4 shadow-sm">
                <div class="flex items-start">
                    <svg class="h-6 w-6 text-green-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="flex-1">
                        <p class="text-green-800 dark:text-green-200 font-medium text-sm sm:text-base">{!! session('success') !!}</p>
                    </div>
                    <button @click="show = false" class="ml-3 text-green-600 hover:text-green-800 flex-shrink-0">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }" 
                 x-show="show"
                 x-transition
                 x-init="setTimeout(() => show = false, 7000)"
                 class="bg-red-50 dark:bg-red-900 border-l-4 border-red-400 p-4 rounded-lg mb-4 shadow-sm">
                <div class="flex items-start">
                    <svg class="h-6 w-6 text-red-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="flex-1">
                        <p class="text-red-800 dark:text-red-200 font-medium text-sm sm:text-base">{{ session('error') }}</p>
                    </div>
                    <button @click="show = false" class="ml-3 text-red-600 hover:text-red-800 flex-shrink-0">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>
        @endif

        @if(session('warning'))
            <div x-data="{ show: true }" 
                 x-show="show"
                 x-transition
                 x-init="setTimeout(() => show = false, 6000)"
                 class="bg-yellow-50 dark:bg-yellow-900 border-l-4 border-yellow-400 p-4 rounded-lg mb-4 shadow-sm">
                <div class="flex items-start">
                    <svg class="h-6 w-6 text-yellow-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div class="flex-1">
                        <p class="text-yellow-800 dark:text-yellow-200 font-medium text-sm sm:text-base">{!! session('warning') !!}</p>
                    </div>
                    <button @click="show = false" class="ml-3 text-yellow-600 hover:text-yellow-800 flex-shrink-0">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>
        @endif

        @if(session('info'))
            <div x-data="{ show: true }" 
                 x-show="show"
                 x-transition
                 x-init="setTimeout(() => show = false, 5000)"
                 class="bg-blue-50 dark:bg-blue-900 border-l-4 border-blue-400 p-4 rounded-lg mb-4 shadow-sm">
                <div class="flex items-start">
                    <svg class="h-6 w-6 text-blue-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="flex-1">
                        <p class="text-blue-800 dark:text-blue-200 font-medium text-sm sm:text-base">{{ session('info') }}</p>
                    </div>
                    <button @click="show = false" class="ml-3 text-blue-600 hover:text-blue-800 flex-shrink-0">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="w-full">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 mt-20 transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0">
                <p class="text-sm text-gray-600 dark:text-gray-400 text-center sm:text-left">© 2025 IRIS EXAM - Tous droits réservés</p>
                <div class="flex items-center space-x-6">
                    <a href="#" class="text-sm text-gray-600 dark:text-gray-400 hover:text-iris-yellow transition-colors">À propos</a>
                    <a href="#" class="text-sm text-gray-600 dark:text-gray-400 hover:text-iris-yellow transition-colors">Contact</a>
                    <a href="#" class="text-sm text-gray-600 dark:text-gray-400 hover:text-iris-yellow transition-colors">Aide</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Dark Mode Script -->
    <script>
        const themeToggleBtn = document.getElementById('theme-toggle');
        const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

        // Show correct icon on load
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            themeToggleLightIcon?.classList.remove('hidden');
        } else {
            themeToggleDarkIcon?.classList.remove('hidden');
        }

        // Toggle theme
        themeToggleBtn?.addEventListener('click', function() {
            themeToggleDarkIcon.classList.toggle('hidden');
            themeToggleLightIcon.classList.toggle('hidden');

            if (localStorage.theme === 'dark') {
                document.documentElement.classList.remove('dark');
                localStorage.theme = 'light';
            } else {
                document.documentElement.classList.add('dark');
                localStorage.theme = 'dark';
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>