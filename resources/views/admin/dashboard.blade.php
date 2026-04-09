@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="space-y-6">
        <!-- En-tête de bienvenue -->
        <div class="bg-gradient-to-r from-iris-yellow to-yellow-500 rounded-2xl shadow-sm p-8 text-iris-black-900">
            <h1 class="text-4xl font-bold mb-2">Bienvenue, {{ auth()->user()->prenom }} ! 👋</h1>
            <p class="text-lg opacity-90">Tableau de bord administrateur - IRIS EXAM</p>
        </div>

        <!-- Statistiques principales -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Étudiants -->
            <div class="bg-white rounded-2xl shadow-sm p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Étudiants</p>
                        <p class="text-3xl font-bold text-iris-black-900 mt-2">{{ $stats['etudiants'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $stats['etudiants_actifs'] }} actif(s)</p>
                    </div>
                    <div class="bg-iris-blue bg-opacity-10 rounded-full p-3">
                        <svg class="h-8 w-8 text-iris-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Enseignants -->
            <div class="bg-white rounded-2xl shadow-sm p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Enseignants</p>
                        <p class="text-3xl font-bold text-iris-black-900 mt-2">{{ $stats['enseignants'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $stats['enseignants_actifs'] }} actif(s)</p>
                    </div>
                    <div class="bg-iris-green bg-opacity-10 rounded-full p-3">
                        <svg class="h-8 w-8 text-iris-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Classes -->
            <div class="bg-white rounded-2xl shadow-sm p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Classes</p>
                        <p class="text-3xl font-bold text-iris-black-900 mt-2">{{ $stats['classes'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $stats['classes_actives'] }} active(s)</p>
                    </div>
                    <div class="bg-iris-yellow bg-opacity-20 rounded-full p-3">
                        <svg class="h-8 w-8 text-iris-yellow-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Examens -->
            <div class="bg-white rounded-2xl shadow-sm p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Examens</p>
                        <p class="text-3xl font-bold text-iris-black-900 mt-2">{{ $stats['examens'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">À venir</p>
                    </div>
                    <div class="bg-purple-100 rounded-full p-3">
                        <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="bg-white rounded-2xl shadow-sm p-8">
            <h2 class="text-2xl font-bold text-iris-black-900 mb-6">Actions rapides</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Créer un utilisateur -->
                <a href="{{ route('admin.utilisateurs.create') }}" 
                   class="flex items-center p-4 bg-gradient-to-r from-iris-blue to-blue-600 text-white rounded-xl hover:shadow-lg transition-all transform hover:-translate-y-1">
                    <svg class="h-6 w-6 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span class="font-semibold">Créer un utilisateur</span>
                </a>

                <!-- Créer une classe -->
                <a href="{{ route('admin.classes.create') }}" 
                   class="flex items-center p-4 bg-gradient-to-r from-iris-yellow to-yellow-500 text-iris-black-900 rounded-xl hover:shadow-lg transition-all transform hover:-translate-y-1">
                    <svg class="h-6 w-6 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span class="font-semibold">Créer une classe</span>
                </a>

                <!-- Créer une matière -->
                <a href="{{ route('admin.matieres.create') }}" 
                   class="flex items-center p-4 bg-gradient-to-r from-iris-brown to-brown-600 text-black rounded-xl hover:shadow-lg transition-all transform hover:-translate-y-1">
                    <svg class="h-6 w-6 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span class="font-semibold">Créer une matière</span>
                </a>

                <!-- Voir tous les utilisateurs -->
                <a href="{{ route('admin.utilisateurs.index') }}" 
                   class="flex items-center p-4 bg-gradient-to-r from-purple-500 to-purple-700 text-white rounded-xl hover:shadow-lg transition-all transform hover:-translate-y-1">
                    <svg class="h-6 w-6 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span class="font-semibold">Voir les utilisateurs</span>
                </a>
            </div>
        </div>

        <!-- Menu de navigation principal -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Gestion des utilisateurs -->
            <a href="{{ route('admin.utilisateurs.index') }}" 
               class="block bg-white rounded-2xl shadow-sm p-6 hover:shadow-lg transition-all group transform hover:-translate-y-1">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-iris-blue bg-opacity-10 rounded-full p-3 group-hover:bg-opacity-20 transition-colors">
                        <svg class="h-8 w-8 text-iris-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <svg class="h-5 w-5 text-gray-400 group-hover:text-iris-blue transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-iris-black-900 mb-2">Gestion des utilisateurs</h3>
                <p class="text-gray-600 text-sm">Gérer les étudiants, enseignants et administrateurs</p>
                <div class="mt-4 flex items-center text-sm text-iris-blue font-medium">
                    <span>{{ $stats['total_utilisateurs'] }} utilisateur(s) au total</span>
                </div>
            </a>

            <!-- Gestion des classes -->
            <a href="{{ route('admin.classes.index') }}" 
               class="block bg-white rounded-2xl shadow-sm p-6 hover:shadow-lg transition-all group transform hover:-translate-y-1">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-iris-yellow bg-opacity-20 rounded-full p-3 group-hover:bg-opacity-30 transition-colors">
                        <svg class="h-8 w-8 text-iris-yellow-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <svg class="h-5 w-5 text-gray-400 group-hover:text-iris-yellow-700 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-iris-black-900 mb-2">Gestion des classes</h3>
                <p class="text-gray-600 text-sm">Créer et gérer les classes et leurs effectifs</p>
                <div class="mt-4 flex items-center text-sm text-iris-yellow-700 font-medium">
                    <span>{{ $stats['classes'] }} classe(s)</span>
                </div>
            </a>

            <!-- Gestion des matières -->
            <a href="{{ route('admin.matieres.index') }}" 
               class="block bg-white rounded-2xl shadow-sm p-6 hover:shadow-lg transition-all group transform hover:-translate-y-1">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-iris-green bg-opacity-10 rounded-full p-3 group-hover:bg-opacity-20 transition-colors">
                        <svg class="h-8 w-8 text-iris-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <svg class="h-5 w-5 text-gray-400 group-hover:text-iris-green transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-iris-black-900 mb-2">Gestion des matières</h3>
                <p class="text-gray-600 text-sm">Gérer les matières et leurs coefficients</p>
            </a>
        </div>

        <!-- Dernières activités -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Derniers utilisateurs créés -->
            <div class="bg-white rounded-2xl shadow-sm p-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-iris-black-900">Derniers utilisateurs créés</h2>
                    <a href="{{ route('admin.utilisateurs.index') }}" 
                       class="text-sm text-iris-blue hover:text-blue-700 font-medium">
                        Voir tous →
                    </a>
                </div>
                @if($derniersUtilisateurs->count() > 0)
                    <div class="space-y-3">
                        @foreach($derniersUtilisateurs as $utilisateur)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-iris-yellow bg-opacity-20 rounded-full flex items-center justify-center flex-shrink-0">
                                        <span class="text-iris-yellow-700 font-semibold text-sm">
                                            {{ substr($utilisateur->prenom, 0, 1) }}{{ substr($utilisateur->nom, 0, 1) }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-iris-black-900">{{ $utilisateur->nomComplet() }}</p>
                                        <p class="text-xs text-gray-600">
                                            <span class="px-2 py-1 rounded-full
                                                @if($utilisateur->role->nom === 'etudiant') bg-iris-blue bg-opacity-20 text-iris-blue
                                                @elseif($utilisateur->role->nom === 'enseignant') bg-iris-green bg-opacity-20 text-iris-green
                                                @else bg-iris-yellow bg-opacity-20 text-iris-yellow-700
                                                @endif">
                                                {{ ucfirst(str_replace('_', ' ', $utilisateur->role->nom)) }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                <span class="text-xs text-gray-500">{{ $utilisateur->created_at->diffForHumans() }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <p class="text-gray-500 text-sm mt-2">Aucun utilisateur récent</p>
                    </div>
                @endif
            </div>

            <!-- Dernières classes créées -->
            <div class="bg-white rounded-2xl shadow-sm p-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-iris-black-900">Dernières classes créées</h2>
                    <a href="{{ route('admin.classes.index') }}" 
                       class="text-sm text-iris-blue hover:text-blue-700 font-medium">
                        Voir toutes →
                    </a>
                </div>
                @if($dernieresClasses->count() > 0)
                    <div class="space-y-3">
                        @foreach($dernieresClasses as $classe)
                            <a href="{{ route('admin.classes.show', $classe) }}" 
                               class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <div>
                                    <p class="font-medium text-iris-black-900">{{ $classe->nom }}</p>
                                    <p class="text-xs text-gray-600">{{ $classe->code }} - {{ $classe->niveau }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-semibold text-iris-yellow-700">{{ $classe->effectif_actuel }}/{{ $classe->effectif_max }}</p>
                                    <span class="text-xs text-gray-500">{{ $classe->created_at->diffForHumans() }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <p class="text-gray-500 text-sm mt-2">Aucune classe récente</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Classes populaires -->
        @if($classesPopulaires->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm p-8">
                <h2 class="text-xl font-bold text-iris-black-900 mb-6">Classes les plus remplies</h2>
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    @foreach($classesPopulaires as $classe)
                        <a href="{{ route('admin.classes.show', $classe) }}" 
                           class="p-4 bg-gradient-to-br from-iris-yellow to-yellow-500 rounded-xl text-iris-black-900 hover:shadow-lg transition-all transform hover:-translate-y-1">
                            <p class="font-bold text-lg truncate">{{ $classe->nom }}</p>
                            <p class="text-sm opacity-90 truncate">{{ $classe->code }}</p>
                            <div class="mt-3">
                                <p class="text-2xl font-bold">{{ $classe->etudiants_count }}</p>
                                <p class="text-xs opacity-80">étudiant(s)</p>
                            </div>
                            <div class="mt-2">
                                <div class="w-full bg-white bg-opacity-30 rounded-full h-1.5">
                                    <div class="bg-iris-black-900 h-1.5 rounded-full" 
                                         style="width: {{ $classe->pourcentageRemplissage() }}%"></div>
                                </div>
                                <p class="text-xs opacity-80 mt-1">{{ $classe->pourcentageRemplissage() }}% rempli</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection