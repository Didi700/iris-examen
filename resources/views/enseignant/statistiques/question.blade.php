@extends('layouts.app')

@section('title', 'Statistiques - Question')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <a href="{{ route('enseignant.statistiques.examen', $question->examen_id) }}" 
               class="text-gray-600 hover:text-iris-yellow transition-colors">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-iris-black-900">📝 Analyse détaillée de la question</h1>
                <p class="text-gray-600 mt-1">{{ $question->examen->titre }}</p>
            </div>
        </div>
    </div>

    <!-- Informations de la question -->
    <div class="bg-white rounded-2xl shadow-sm p-8">
        <div class="flex items-start justify-between mb-6">
            <div class="flex-1">
                <div class="flex items-center space-x-3 mb-3">
                    <span class="px-4 py-2 text-sm font-bold rounded-full bg-iris-blue text-white">
                        Question #{{ $question->numero }}
                    </span>
                    <span class="px-4 py-2 text-sm font-semibold rounded-full bg-gray-100 text-gray-700">
                        {{ ucfirst(str_replace('_', ' ', $question->type)) }}
                    </span>
                    <span class="px-4 py-2 text-sm font-semibold rounded-full bg-iris-yellow text-iris-black-900">
                        {{ $question->points }} points
                    </span>
                </div>
                <p class="text-xl text-gray-900 font-medium mb-4">{{ $question->enonce }}</p>
                
                @if($question->image)
                    <div class="mb-4">
                        <img src="{{ asset('storage/' . $question->image) }}" 
                             alt="Image de la question" 
                             class="max-w-md rounded-lg shadow-md">
                    </div>
                @endif

                @if($question->contexte)
                    <div class="bg-blue-50 border-l-4 border-iris-blue p-4 rounded-r-lg">
                        <p class="text-sm text-gray-700"><strong>Contexte :</strong> {{ $question->contexte }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- KPIs de performance -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Nombre de réponses -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold opacity-90">Réponses</h3>
                <svg class="h-8 w-8 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <p class="text-4xl font-bold">{{ $stats['nb_reponses'] }}</p>
            <p class="text-sm opacity-75 mt-1">sur {{ $stats['nb_participants'] }} participants</p>
        </div>

        <!-- Taux de réussite -->
        <div class="bg-gradient-to-br from-{{ $stats['taux_reussite'] >= 70 ? 'green' : ($stats['taux_reussite'] >= 50 ? 'yellow' : 'red') }}-500 to-{{ $stats['taux_reussite'] >= 70 ? 'green' : ($stats['taux_reussite'] >= 50 ? 'yellow' : 'red') }}-600 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold opacity-90">Taux de réussite</h3>
                <svg class="h-8 w-8 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p class="text-4xl font-bold">{{ $stats['taux_reussite'] }}%</p>
            <p class="text-sm opacity-75 mt-1">
                {{ $stats['nb_reussites'] }} réponse(s) correcte(s)
            </p>
        </div>

        <!-- Note moyenne -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold opacity-90">Note moyenne</h3>
                <svg class="h-8 w-8 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
            <p class="text-4xl font-bold">{{ $stats['note_moyenne'] }}</p>
            <p class="text-sm opacity-75 mt-1">sur {{ $question->points }} points</p>
        </div>

        <!-- Difficulté -->
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold opacity-90">Difficulté</h3>
                <svg class="h-8 w-8 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <p class="text-4xl font-bold">{{ $stats['niveau_difficulte'] }}</p>
            <p class="text-sm opacity-75 mt-1">
                @if($stats['taux_reussite'] >= 70)
                    Facile
                @elseif($stats['taux_reussite'] >= 50)
                    Moyenne
                @else
                    Difficile
                @endif
            </p>
        </div>
    </div>

    <!-- Distribution des réponses (selon le type de question) -->
    @if(in_array($question->type, ['qcm', 'vrai_faux']))
        <div class="bg-white rounded-2xl shadow-sm p-8">
            <h2 class="text-2xl font-bold text-iris-black-900 mb-6">📊 Distribution des réponses</h2>
            <div class="space-y-4">
                @foreach($distributionReponses as $reponse)
                    @php
                        $pourcentage = $stats['nb_reponses'] > 0 ? ($reponse['count'] / $stats['nb_reponses']) * 100 : 0;
                        $isCorrect = $reponse['est_correcte'];
                        $couleur = $isCorrect ? 'bg-green-500' : 'bg-red-500';
                    @endphp
                    <div class="border-2 {{ $isCorrect ? 'border-green-200 bg-green-50' : 'border-gray-200' }} rounded-xl p-4">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center space-x-3">
                                @if($isCorrect)
                                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                @else
                                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                @endif
                                <span class="font-medium text-gray-900">{{ $reponse['texte'] }}</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <span class="text-sm font-semibold text-gray-600">
                                    {{ $reponse['count'] }} choix ({{ round($pourcentage, 1) }}%)
                                </span>
                            </div>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="{{ $couleur }} h-3 rounded-full transition-all duration-500" 
                                 style="width: {{ $pourcentage }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Réponses ouvertes (aperçu) -->
    @if($question->type === 'ouverte' && $reponsesOuvertes->isNotEmpty())
        <div class="bg-white rounded-2xl shadow-sm p-8">
            <h2 class="text-2xl font-bold text-iris-black-900 mb-6">📝 Exemples de réponses ouvertes</h2>
            <div class="space-y-4">
                @foreach($reponsesOuvertes->take(5) as $reponse)
                    <div class="border-2 border-gray-200 rounded-xl p-4 hover:border-iris-blue transition-all">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <p class="text-gray-900 mb-2">{{ $reponse->reponse_texte }}</p>
                                <div class="flex items-center space-x-4 text-sm">
                                    <span class="text-gray-600">
                                        {{ $reponse->sessionExamen->etudiant->utilisateur->prenom ?? 'N/A' }}
                                        {{ $reponse->sessionExamen->etudiant->utilisateur->nom ?? 'N/A' }}
                                    </span>
                                    <span class="px-2 py-1 rounded-full {{ $reponse->points_obtenus == $question->points ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }} font-semibold">
                                        {{ $reponse->points_obtenus }}/{{ $question->points }} pts
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @if($reponsesOuvertes->count() > 5)
                <div class="mt-4 text-center">
                    <p class="text-gray-600">Et {{ $reponsesOuvertes->count() - 5 }} autre(s) réponse(s)...</p>
                </div>
            @endif
        </div>
    @endif

    <!-- Statistiques avancées -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Index de discrimination -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">🎯 Index de discrimination</h3>
            <div class="text-center">
                <p class="text-5xl font-bold mb-2" 
                   style="color: {{ $stats['index_discrimination'] >= 0.3 ? '#10B981' : ($stats['index_discrimination'] >= 0.15 ? '#F59E0B' : '#EF4444') }}">
                    {{ $stats['index_discrimination'] }}
                </p>
                <p class="text-gray-600 mb-4">
                    @if($stats['index_discrimination'] >= 0.3)
                        <span class="text-green-600 font-semibold">Excellente discrimination</span>
                    @elseif($stats['index_discrimination'] >= 0.15)
                        <span class="text-yellow-600 font-semibold">Discrimination acceptable</span>
                    @else
                        <span class="text-red-600 font-semibold">Faible discrimination</span>
                    @endif
                </p>
                <p class="text-sm text-gray-500">
                    Cette métrique mesure la capacité de la question à distinguer les bons étudiants des moins bons.
                </p>
            </div>
        </div>

        <!-- Temps moyen de réponse -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">⏱️ Temps de réponse</h3>
            <div class="text-center">
                <p class="text-5xl font-bold text-iris-blue mb-2">
                    {{ $stats['temps_moyen'] }}
                </p>
                <p class="text-gray-600 mb-4">secondes en moyenne</p>
                <div class="flex justify-around text-sm text-gray-600">
                    <div>
                        <p class="font-semibold text-gray-900">{{ $stats['temps_min'] }}s</p>
                        <p>Minimum</p>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">{{ $stats['temps_max'] }}s</p>
                        <p>Maximum</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recommandations pédagogiques -->
    <div class="bg-gradient-to-r from-iris-blue to-blue-600 rounded-2xl shadow-lg p-8 text-white">
        <h2 class="text-2xl font-bold mb-4">💡 Recommandations pédagogiques</h2>
        <div class="space-y-3">
            @if($stats['taux_reussite'] < 50)
                <div class="flex items-start space-x-3">
                    <svg class="h-6 w-6 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <p><strong>Question difficile :</strong> Seulement {{ $stats['taux_reussite'] }}% de réussite. Envisagez de revoir cette notion en cours ou de reformuler la question.</p>
                </div>
            @elseif($stats['taux_reussite'] > 90)
                <div class="flex items-start space-x-3">
                    <svg class="h-6 w-6 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    <p><strong>Question facile :</strong> {{ $stats['taux_reussite'] }}% de réussite. La question pourrait être plus discriminante.</p>
                </div>
            @else
                <div class="flex items-start space-x-3">
                    <svg class="h-6 w-6 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p><strong>Bon équilibre :</strong> Cette question a un niveau de difficulté approprié ({{ $stats['taux_reussite'] }}%).</p>
                </div>
            @endif

            @if($stats['index_discrimination'] < 0.15)
                <div class="flex items-start space-x-3">
                    <svg class="h-6 w-6 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <p><strong>Discrimination faible :</strong> Cette question ne distingue pas bien les bons étudiants. Vérifiez si elle est ambiguë ou trop évidente.</p>
                </div>
            @endif

            @if($question->type === 'qcm' && $distributionReponses->count() > 0)
                @php
                    $reponsesNonChoisies = $distributionReponses->where('count', 0)->count();
                @endphp
                @if($reponsesNonChoisies > 0)
                    <div class="flex items-start space-x-3">
                        <svg class="h-6 w-6 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p><strong>Distracteurs :</strong> {{ $reponsesNonChoisies }} réponse(s) n'a/ont jamais été choisie(s). Ces options pourraient être plus crédibles.</p>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection