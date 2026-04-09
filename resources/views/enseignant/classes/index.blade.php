@extends('layouts.app')

@section('title', 'Mes classes')

@section('content')
    <div class="space-y-6">
        <!-- En-tête -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-iris-black-900">Mes classes et matières</h1>
                <p class="text-gray-600 mt-1">Vue d'ensemble de toutes vos affectations</p>
            </div>
        </div>

        <!-- Statistiques globales -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Nombre de classes -->
            <div class="bg-gradient-to-br from-iris-blue to-blue-600 rounded-2xl shadow-sm p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-100">Classes</p>
                        <p class="text-4xl font-bold mt-2">{{ $classesAvecMatieres->count() }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Nombre total de matières -->
            <div class="bg-gradient-to-br from-iris-yellow to-yellow-500 rounded-2xl shadow-sm p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-yellow-100">Matières enseignées</p>
                        <p class="text-4xl font-bold mt-2">{{ $classesAvecMatieres->sum(function($c) { return $c->mes_matieres->count(); }) }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Nombre total d'étudiants -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-lg p-6 text-white hover:shadow-xl transition-all transform hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-100">Étudiants</p>
                        <p class="text-4xl font-bold mt-2">{{ $classesAvecMatieres->sum('etudiants_count') }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Nombre total d'examens -->
            <div class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-2xl shadow-sm p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-purple-100">Examens créés</p>
                        <p class="text-4xl font-bold mt-2">{{ $classesAvecMatieres->sum('nb_examens') }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des classes avec leurs matières -->
        @if($classesAvecMatieres->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @foreach($classesAvecMatieres as $classe)
                    <div class="bg-white rounded-2xl shadow-sm overflow-hidden hover:shadow-lg transition-all">
                        <!-- En-tête de la classe -->
                        <div class="bg-gradient-to-r from-iris-blue to-blue-600 p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-2xl font-bold">{{ $classe->nom }}</h2>
                                    <p class="text-blue-100 mt-1">{{ $classe->code }}</p>
                                </div>
                                <div class="bg-white bg-opacity-20 rounded-full px-4 py-2">
                                    <span class="text-sm font-semibold">{{ $classe->etudiants_count }} étudiants</span>
                                </div>
                            </div>
                        </div>

                        <!-- Corps de la carte -->
                        <div class="p-6">
                            <!-- Matières enseignées -->
                            <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                                <svg class="h-5 w-5 mr-2 text-iris-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                Matières enseignées ({{ $classe->mes_matieres->count() }})
                            </h3>

                            @if($classe->mes_matieres->count() > 0)
                                <div class="space-y-3 mb-6">
                                    @foreach($classe->mes_matieres as $matiere)
                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-iris-yellow hover:bg-opacity-10 transition-colors">
                                            <div class="flex items-center space-x-3">
                                                <div class="bg-iris-blue bg-opacity-10 rounded-full p-2">
                                                    <svg class="h-5 w-5 text-iris-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-gray-900">{{ $matiere->nom }}</p>
                                                    @if($matiere->code)
                                                        <p class="text-xs text-gray-600">{{ $matiere->code }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <a href="{{ route('enseignant.classes.matiere', [$classe->id, $matiere->id]) }}" 
                                               class="px-3 py-1 bg-iris-yellow text-iris-black-900 rounded-lg text-sm font-semibold hover:bg-iris-yellow-600 transition-all">
                                                Voir
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-600 mb-6">Aucune matière assignée</p>
                            @endif

                            <!-- Actions -->
                            <div class="flex items-center space-x-3 pt-4 border-t border-gray-200">
                                <a href="{{ route('enseignant.classes.show', $classe->id) }}" 
                                   class="flex-1 px-4 py-2 bg-iris-blue text-white rounded-lg font-semibold hover:bg-blue-700 transition-all text-center">
                                    <svg class="inline h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Détails
                                </a>
                                <a href="{{ route('enseignant.examens.create', ['classe_id' => $classe->id]) }}" 
                                   class="flex-1 px-4 py-2 bg-iris-yellow text-iris-black-900 rounded-lg font-semibold hover:bg-iris-yellow-600 transition-all text-center">
                                    <svg class="inline h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Créer examen
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Aucune classe assignée</h3>
                <p class="text-gray-600">Vous n'êtes affecté à aucune classe pour le moment.</p>
            </div>
        @endif
    </div>
@endsection