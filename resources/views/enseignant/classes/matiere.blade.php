@extends('layouts.app')

@section('title', $classe->nom . ' - ' . $matiere->nom)

@section('content')
    <div class="space-y-6">
        <!-- En-tête avec fil d'Ariane -->
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('enseignant.classes.index') }}" 
                   class="text-gray-600 hover:text-iris-yellow">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <div class="flex items-center space-x-2 text-sm text-gray-600 mb-1">
                        <a href="{{ route('enseignant.classes.index') }}" class="hover:text-iris-yellow">Mes classes</a>
                        <span>›</span>
                        <a href="{{ route('enseignant.classes.show', $classe->id) }}" class="hover:text-iris-yellow">{{ $classe->nom }}</a>
                        <span>›</span>
                        <span class="text-iris-blue font-semibold">{{ $matiere->nom }}</span>
                    </div>
                    <h1 class="text-3xl font-bold text-iris-black-900">{{ $classe->nom }} - {{ $matiere->nom }}</h1>
                    <p class="text-gray-600 mt-1">{{ $classe->code }}</p>
                </div>
            </div>
            <a href="{{ route('enseignant.examens.create', ['classe_id' => $classe->id, 'matiere_id' => $matiere->id]) }}" 
               class="px-6 py-3 bg-iris-yellow text-iris-black-900 rounded-lg font-semibold hover:bg-iris-yellow-600 transition-all shadow-sm">
                <svg class="inline h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Créer un examen
            </a>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Étudiants -->
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Étudiants</p>
                        <p class="text-3xl font-bold text-iris-black-900 mt-2">{{ $stats['nb_etudiants'] }}</p>
                    </div>
                    <div class="bg-iris-blue bg-opacity-10 rounded-full p-3">
                        <svg class="h-8 w-8 text-iris-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Examens -->
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Examens</p>
                        <p class="text-3xl font-bold text-iris-black-900 mt-2">{{ $stats['nb_examens'] }}</p>
                    </div>
                    <div class="bg-iris-yellow bg-opacity-20 rounded-full p-3">
                        <svg class="h-8 w-8 text-iris-yellow-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Sessions -->
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Sessions passées</p>
                        <p class="text-3xl font-bold text-iris-black-900 mt-2">{{ $stats['nb_sessions'] }}</p>
                    </div>
                    <div class="bg-iris-green bg-opacity-10 rounded-full p-3">
                        <svg class="h-8 w-8 text-iris-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Moyenne -->
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Moyenne classe</p>
                        <p class="text-3xl font-bold text-iris-black-900 mt-2">
                            {{ $stats['moyenne_classe'] ? number_format($stats['moyenne_classe'], 2) : '-' }}
                        </p>
                    </div>
                    <div class="bg-purple-100 rounded-full p-3">
                        <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Examens -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm p-8">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold text-iris-black-900">Examens ({{ $examens->count() }})</h2>
                        <a href="{{ route('enseignant.examens.create', ['classe_id' => $classe->id, 'matiere_id' => $matiere->id]) }}" 
                           class="px-4 py-2 bg-iris-yellow text-iris-black-900 rounded-lg font-semibold hover:bg-iris-yellow-600 transition-all text-sm">
                            + Nouvel examen
                        </a>
                    </div>

                    @if($examens->count() > 0)
                        <div class="space-y-4">
                            @foreach($examens as $examen)
                                <div class="border border-gray-200 rounded-lg p-6 hover:border-iris-yellow transition-colors">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-3 mb-2">
                                                <h3 class="text-lg font-bold text-iris-black-900">{{ $examen->titre }}</h3>
                                                <span class="px-3 py-1 text-xs font-semibold rounded-full
                                                    @if($examen->statut === 'brouillon') bg-gray-100 text-gray-800
                                                    @elseif($examen->statut === 'publie') bg-green-100 text-green-800
                                                    @else bg-red-100 text-red-800
                                                    @endif">
                                                    {{ $examen->statut_libelle }}
                                                </span>
                                                @if($examen->type_examen === 'document')
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                                        PDF
                                                    </span>
                                                @else
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-iris-blue bg-opacity-20 text-iris-blue">
                                                        En ligne
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            <div class="flex items-center space-x-4 text-sm text-gray-600 mb-3">
                                                <span class="flex items-center">
                                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    {{ $examen->date_debut->format('d/m/Y') }}
                                                </span>
                                                <span class="flex items-center">
                                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    {{ $examen->duree_minutes }} min
                                                </span>
                                                <span class="flex items-center">
                                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                    </svg>
                                                    {{ $examen->sessions_count }} session(s)
                                                </span>
                                            </div>

                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('enseignant.examens.show', $examen->id) }}" 
                                                   class="px-4 py-2 bg-iris-blue text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition-all">
                                                    Voir détails
                                                </a>
                                                @if($examen->statut === 'brouillon')
                                                    <a href="{{ route('enseignant.examens.edit', $examen->id) }}" 
                                                       class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-300 transition-all">
                                                        Modifier
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Aucun examen créé</h3>
                            <p class="text-gray-600 mb-4">Créez votre premier examen pour cette classe et matière.</p>
                            <a href="{{ route('enseignant.examens.create', ['classe_id' => $classe->id, 'matiere_id' => $matiere->id]) }}" 
                               class="inline-block px-6 py-3 bg-iris-yellow text-iris-black-900 rounded-lg font-semibold hover:bg-iris-yellow-600 transition-all">
                                Créer un examen
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Liste des étudiants avec performances -->
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <h3 class="text-lg font-bold text-iris-black-900 mb-4">Étudiants ({{ $etudiants->count() }})</h3>
                
                @if($etudiants->count() > 0)
                    <div class="space-y-3 max-h-[600px] overflow-y-auto">
                        @foreach($etudiants as $etudiant)
                            @php
                                $sessionsEtudiant = $etudiant->sessionsExamen;
                                $moyenneEtudiant = $sessionsEtudiant->whereNotNull('note_obtenue')->avg('note_obtenue');
                                $nbSessions = $sessionsEtudiant->count();
                            @endphp
                            <div class="border border-gray-200 rounded-lg p-3 hover:border-iris-yellow transition-colors">
                                <div class="flex items-center space-x-3">
                                    <div class="bg-iris-blue bg-opacity-10 rounded-full w-10 h-10 flex items-center justify-center flex-shrink-0">
                                        <span class="text-iris-blue font-semibold text-sm">
                                            {{ strtoupper(substr($etudiant->utilisateur->prenom, 0, 1)) }}{{ strtoupper(substr($etudiant->utilisateur->nom, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-gray-900 text-sm truncate">
                                            {{ $etudiant->utilisateur->nom_complet }}
                                        </p>
                                        <div class="flex items-center space-x-2 text-xs text-gray-600">
                                            <span>{{ $nbSessions }} session(s)</span>
                                            @if($moyenneEtudiant)
                                                <span>•</span>
                                                <span class="font-semibold 
                                                    @if($moyenneEtudiant >= 10) text-green-600
                                                    @else text-red-600
                                                    @endif">
                                                    {{ number_format($moyenneEtudiant, 1) }}/20
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-600 text-center py-8">Aucun étudiant dans cette classe</p>
                @endif
            </div>
        </div>
    </div>
@endsection