@extends('layouts.app')

@section('title', $examen->titre)

@section('content')
    <div class="space-y-6">
        <!-- En-tête -->
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('enseignant.examens.index') }}" 
                   class="text-gray-600 hover:text-iris-yellow transition-colors">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <div class="flex items-center space-x-3">
                        <h1 class="text-3xl font-bold text-iris-black-900">{{ $examen->titre }}</h1>
                        <span class="px-3 py-1 text-sm font-semibold rounded-full
                            @if($examen->statut === 'brouillon') bg-gray-100 text-gray-800
                            @elseif($examen->statut === 'publie') bg-green-100 text-green-800
                            @elseif($examen->statut === 'termine') bg-blue-100 text-blue-800
                            @else bg-purple-100 text-purple-800
                            @endif">
                            {{ ucfirst($examen->statut) }}
                        </span>
                    </div>
                    <p class="text-gray-600 mt-1">{{ $examen->matiere->nom }} • {{ $examen->classe->nom }}</p>
                </div>
            </div>

            <div class="flex items-center space-x-2">
                @if($examen->statut === 'brouillon')
                    <a href="{{ route('enseignant.examens.edit', $examen->id) }}" 
                       class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition-all">
                        Modifier
                    </a>
                    <form action="{{ route('enseignant.examens.publier', $examen->id) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                onclick="return confirm('Publier cet examen ? Les étudiants pourront y accéder.')"
                                class="px-6 py-3 bg-iris-yellow text-iris-black-900 rounded-lg font-bold hover:bg-iris-yellow-600 transition-all">
                            Publier
                        </button>
                    </form>
                @endif

                <!-- Bouton Export PDF -->
                <a href="{{ route('enseignant.examens.export-pdf', $examen->id) }}" 
                   class="px-6 py-3 bg-red-600 text-white rounded-xl font-bold hover:bg-red-700 transition-all flex items-center shadow-lg">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export PDF
                </a>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Questions</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['nb_questions'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $stats['note_totale_questions'] }} points total</p>
                    </div>
                    <div class="bg-iris-blue bg-opacity-10 rounded-full p-3">
                        <svg class="h-6 w-6 text-iris-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Sessions totales</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['nb_sessions'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $stats['nb_sessions_terminees'] }} terminée(s)</p>
                    </div>
                    <div class="bg-green-100 rounded-full p-3">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Moyenne</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">
                            {{ $stats['moyenne'] ? number_format($stats['moyenne'], 1) : '-' }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">/ {{ $examen->note_totale }}</p>
                    </div>
                    <div class="bg-iris-yellow bg-opacity-20 rounded-full p-3">
                        <svg class="h-6 w-6 text-iris-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Durée</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $examen->duree_minutes }}</p>
                        <p class="text-xs text-gray-500 mt-1">minutes</p>
                    </div>
                    <div class="bg-purple-100 rounded-full p-3">
                        <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Informations principales -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Description -->
                @if($examen->description)
                    <div class="bg-white rounded-2xl shadow-sm p-8">
                        <h2 class="text-xl font-bold text-iris-black-900 mb-4">Description</h2>
                        <p class="text-gray-700 leading-relaxed">{{ $examen->description }}</p>
                    </div>
                @endif

                <!-- Instructions -->
                @if($examen->instructions)
                    <div class="bg-white rounded-2xl shadow-sm p-8">
                        <h2 class="text-xl font-bold text-iris-black-900 mb-4">Instructions</h2>
                        <div class="bg-blue-50 border-l-4 border-iris-blue p-6 rounded-r-lg">
                            <p class="text-gray-900 whitespace-pre-line">{{ $examen->instructions }}</p>
                        </div>
                    </div>
                @endif

                <!-- Questions (si examen en ligne) -->
                @if($examen->type_examen === 'en_ligne')
                    <div class="bg-white rounded-2xl shadow-sm p-8">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-xl font-bold text-iris-black-900">Questions ({{ $examen->questions->count() }})</h2>
                            @if($examen->statut === 'brouillon')
                                <a href="{{ route('enseignant.examens.questions', $examen->id) }}" 
                                   class="px-4 py-2 bg-iris-yellow text-iris-black-900 rounded-lg font-semibold hover:bg-iris-yellow-600 transition-all">
                                    Gérer les questions
                                </a>
                            @endif
                        </div>

                        @if($examen->questions->count() > 0)
                            <div class="space-y-4">
                                @foreach($examen->questions as $index => $question)
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center space-x-3 mb-2">
                                                    <span class="bg-iris-blue bg-opacity-10 rounded-full w-8 h-8 flex items-center justify-center">
                                                        <span class="text-iris-blue font-bold text-sm">{{ $index + 1 }}</span>
                                                    </span>
                                                    <h3 class="font-semibold text-gray-900">{{ Str::limit($question->enonce, 100) }}</h3>
                                                </div>
                                                <div class="flex items-center space-x-4 text-xs text-gray-600 ml-11">
                                                    <span class="px-2 py-1 bg-gray-100 rounded">{{ ucfirst(str_replace('_', ' ', $question->type)) }}</span>
                                                    <span class="px-2 py-1 bg-gray-100 rounded">{{ $question->reponses->count() }} réponses</span>
                                                    <span class="px-2 py-1 bg-gray-100 rounded">Difficulté: {{ ucfirst($question->difficulte) }}</span>
                                                </div>
                                            </div>
                                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-iris-yellow bg-opacity-20 text-iris-yellow-700">
                                                {{ $question->pivot->points }} pts
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-sm text-gray-600 mb-3">Aucune question ajoutée</p>
                                @if($examen->statut === 'brouillon')
                                    <a href="{{ route('enseignant.examens.questions', $examen->id) }}" 
                                       class="inline-block px-4 py-2 bg-iris-yellow text-iris-black-900 rounded-lg font-semibold hover:bg-iris-yellow-600 transition-all">
                                        Ajouter des questions
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Fichier PDF (si examen document) -->
                @if($examen->type_examen === 'document' && $examen->fichier_sujet_path)
                    <div class="bg-white rounded-2xl shadow-sm p-8">
                        <h2 class="text-xl font-bold text-iris-black-900 mb-4">Sujet de l'examen</h2>
                        <a href="{{ Storage::url($examen->fichier_sujet_path) }}" 
                           target="_blank"
                           class="inline-flex items-center px-6 py-3 bg-purple-100 text-purple-800 rounded-lg hover:bg-purple-200 transition-all font-semibold">
                            <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Télécharger le sujet PDF
                        </a>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Informations -->
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <h3 class="text-lg font-bold text-iris-black-900 mb-4">Informations</h3>
                    
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-gray-600 mb-1">Type d'examen</p>
                            <p class="font-semibold text-gray-900">
                                {{ $examen->type_examen === 'en_ligne' ? 'En ligne' : 'Document PDF' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-600 mb-1">Durée</p>
                            <p class="font-semibold text-gray-900">{{ $examen->duree_minutes }} minutes</p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-600 mb-1">Note totale</p>
                            <p class="font-semibold text-gray-900">{{ $examen->note_totale }} points</p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-600 mb-1">Seuil de réussite</p>
                            <p class="font-semibold text-gray-900">{{ $examen->seuil_reussite }}%</p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-600 mb-1">Tentatives max</p>
                            <p class="font-semibold text-gray-900">{{ $examen->nombre_tentatives_max }}</p>
                        </div>
                    </div>
                </div>

                <!-- Dates -->
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <h3 class="text-lg font-bold text-iris-black-900 mb-4">Dates</h3>
                    
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-gray-600 mb-1">Date de début</p>
                            <p class="font-semibold text-gray-900">{{ $examen->date_debut->format('d/m/Y à H:i') }}</p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-600 mb-1">Date de fin</p>
                            <p class="font-semibold text-gray-900">{{ $examen->date_fin->format('d/m/Y à H:i') }}</p>
                        </div>

                        @if(now()->gte($examen->date_debut) && now()->lte($examen->date_fin))
                            <div class="pt-3 border-t border-gray-200">
                                <div class="bg-green-50 rounded-lg p-3">
                                    <p class="text-sm font-semibold text-green-800">✓ Examen en cours</p>
                                </div>
                            </div>
                        @elseif(now()->lt($examen->date_debut))
                            <div class="pt-3 border-t border-gray-200">
                                <div class="bg-blue-50 rounded-lg p-3">
                                    <p class="text-sm font-semibold text-blue-800">À venir</p>
                                </div>
                            </div>
                        @else
                            <div class="pt-3 border-t border-gray-200">
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <p class="text-sm font-semibold text-gray-800">Terminé</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Options (si en ligne) -->
                @if($examen->type_examen === 'en_ligne')
                    <div class="bg-white rounded-2xl shadow-sm p-6">
                        <h3 class="text-lg font-bold text-iris-black-900 mb-4">Options</h3>
                        
                        <div class="space-y-2 text-sm">
                            <div class="flex items-center space-x-2">
                                @if($examen->melanger_questions)
                                    <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                @else
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                @endif
                                <span class="text-gray-700">Questions mélangées</span>
                            </div>

                            <div class="flex items-center space-x-2">
                                @if($examen->melanger_reponses)
                                    <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                @else
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                @endif
                                <span class="text-gray-700">Réponses mélangées</span>
                            </div>

                            <div class="flex items-center space-x-2">
                                @if($examen->afficher_resultats_immediatement)
                                    <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                @else
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                @endif
                                <span class="text-gray-700">Résultats immédiats</span>
                            </div>

                            <div class="flex items-center space-x-2">
                                @if($examen->autoriser_retour_arriere)
                                    <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                @else
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                @endif
                                <span class="text-gray-700">Retour arrière autorisé</span>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Actions -->
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <h3 class="text-lg font-bold text-iris-black-900 mb-4">Actions</h3>
                    
                    <div class="space-y-2">
                        <form action="{{ route('enseignant.examens.duplicate', $examen->id) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-semibold hover:bg-gray-200 transition-all">
                                Dupliquer l'examen
                            </button>
                        </form>

                        @if($examen->statut !== 'archive')
                            <form action="{{ route('enseignant.examens.archiver', $examen->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        onclick="return confirm('Archiver cet examen ?')"
                                        class="w-full px-4 py-2 bg-purple-100 text-purple-700 rounded-lg font-semibold hover:bg-purple-200 transition-all">
                                    Archiver
                                </button>
                            </form>
                        @endif

                        @if($examen->sessions->count() === 0)
                            <form action="{{ route('enseignant.examens.destroy', $examen->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('Supprimer définitivement cet examen ? Cette action est irréversible.')"
                                        class="w-full px-4 py-2 bg-red-100 text-red-700 rounded-lg font-semibold hover:bg-red-200 transition-all">
                                    Supprimer
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection