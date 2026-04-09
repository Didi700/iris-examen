@extends('layouts.app')

@section('title', $classe->nom)

@section('content')
    <div class="space-y-6">
        <!-- En-tête -->
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('enseignant.classes.index') }}" 
                   class="text-gray-600 hover:text-iris-yellow transition-colors">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-iris-black-900">{{ $classe->nom }}</h1>
                    <p class="text-gray-600 mt-1">{{ $classe->code }} • Niveau {{ $classe->niveau }}</p>
                </div>
            </div>
            <a href="{{ route('enseignant.examens.create', ['classe_id' => $classe->id]) }}" 
               class="px-6 py-3 bg-iris-yellow text-iris-black-900 rounded-lg font-semibold hover:bg-iris-yellow-600 transition-all shadow-sm">
                <svg class="inline h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Créer un examen
            </a>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Étudiants -->
            <div class="bg-gradient-to-br from-iris-blue to-blue-600 rounded-xl shadow-sm p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-100">Étudiants</p>
                        <p class="text-4xl font-bold mt-2">{{ $stats['nb_etudiants'] }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Matières -->
            <div class="bg-gradient-to-br from-iris-yellow to-yellow-500 rounded-xl shadow-sm p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-yellow-100">Matières enseignées</p>
                        <p class="text-4xl font-bold mt-2">{{ $stats['nb_matieres'] }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Examens -->
            <div class="bg-gradient-to-br from-iris-green to-green-600 rounded-xl shadow-sm p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-100">Examens créés</p>
                        <p class="text-4xl font-bold mt-2">{{ $stats['nb_examens'] }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Matières enseignées -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm p-8">
                    <h2 class="text-xl font-bold text-iris-black-900 mb-6">Matières enseignées dans cette classe</h2>
                    
                    @if($matieres->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($matieres as $matiere)
                                <div class="border border-gray-200 rounded-xl p-6 hover:border-iris-yellow hover:shadow-md transition-all">
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex items-start space-x-3 flex-1">
                                            <div class="bg-iris-blue bg-opacity-10 rounded-full p-3">
                                                <svg class="h-6 w-6 text-iris-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <h3 class="font-bold text-gray-900 text-lg">{{ $matiere->nom }}</h3>
                                                @if($matiere->code)
                                                    <p class="text-sm text-gray-600 mt-1">{{ $matiere->code }}</p>
                                                @endif
                                                @if($matiere->description)
                                                    <p class="text-xs text-gray-500 mt-2 line-clamp-2">{{ $matiere->description }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex space-x-2">
                                        <a href="{{ route('enseignant.classes.matiere', [$classe->id, $matiere->id]) }}" 
                                           class="flex-1 text-center px-4 py-2 bg-iris-blue text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition-all">
                                            Voir détails
                                        </a>
                                        <a href="{{ route('enseignant.examens.create', ['classe_id' => $classe->id, 'matiere_id' => $matiere->id]) }}" 
                                           class="flex-1 text-center px-4 py-2 bg-iris-yellow text-iris-black-900 rounded-lg text-sm font-semibold hover:bg-iris-yellow-600 transition-all">
                                            Créer examen
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            <p class="text-gray-600">Aucune matière assignée dans cette classe</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Liste des étudiants -->
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-iris-black-900">
                        Étudiants ({{ $stats['nb_etudiants'] }})
                    </h3>
                </div>
                
                @if($etudiants->count() > 0)
                    <div class="space-y-2 max-h-[600px] overflow-y-auto">
                        @foreach($etudiants as $etudiant)
                            @if($etudiant->utilisateur)
                                <div class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors border border-transparent hover:border-iris-yellow">
                                    <div class="bg-iris-blue bg-opacity-10 rounded-full w-10 h-10 flex items-center justify-center flex-shrink-0">
                                        <span class="text-iris-blue font-semibold text-sm">
                                            {{ $etudiant->utilisateur->initiales }}
                                        </span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-gray-900 text-sm truncate">
                                            {{ $etudiant->utilisateur->nom_complet }}
                                        </p>
                                        <p class="text-xs text-gray-600">{{ $etudiant->matricule }}</p>
                                        @if($etudiant->utilisateur->email)
                                            <p class="text-xs text-gray-500 truncate">{{ $etudiant->utilisateur->email }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <p class="text-gray-600">Aucun étudiant dans cette classe</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Informations de la classe -->
        <div class="bg-white rounded-2xl shadow-sm p-8">
            <h2 class="text-xl font-bold text-iris-black-900 mb-6">Informations de la classe</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Année scolaire</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $classe->annee_scolaire }}</p>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Effectif</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $classe->effectif_actuel }} / {{ $classe->effectif_max }}</p>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Statut</p>
                    <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full 
                        @if($classe->statut === 'active') bg-green-100 text-green-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ ucfirst($classe->statut) }}
                    </span>
                </div>

                @if($classe->date_debut && $classe->date_fin)
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Période</p>
                        <p class="text-sm text-gray-900">
                            {{ \Carbon\Carbon::parse($classe->date_debut)->format('d/m/Y') }} - 
                            {{ \Carbon\Carbon::parse($classe->date_fin)->format('d/m/Y') }}
                        </p>
                    </div>
                @endif

                @if($classe->accepte_alternance)
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Alternance</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $classe->nb_etudiants_alternance }} étudiant(s)</p>
                    </div>
                @endif

                @if($classe->accepte_initial)
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Initial</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $classe->nb_etudiants_initial }} étudiant(s)</p>
                    </div>
                @endif
            </div>

            @if($classe->description)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <p class="text-sm font-medium text-gray-600 mb-2">Description</p>
                    <p class="text-gray-900">{{ $classe->description }}</p>
                </div>
            @endif
        </div>
    </div>
@endsection