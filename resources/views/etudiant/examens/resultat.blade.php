@extends('layouts.app')

@section('title', 'Résultat - ' . $examen->titre)

@section('content')
    <div class="space-y-6">
        <!-- En-tête -->
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('etudiant.examens.show', $examen->id) }}" 
                   class="text-gray-600 hover:text-iris-yellow transition-colors">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-iris-black-900">Résultat de l'examen</h1>
                    <p class="text-gray-600 mt-1">{{ $examen->titre }} • Tentative {{ $session->numero_tentative }}</p>
                </div>
            </div>
        </div>

        <!-- Carte de résultat -->
        @if($session->note_obtenue !== null)
            <div class="bg-gradient-to-br {{ $session->estReussi() ? 'from-green-500 to-green-700' : 'from-red-500 to-red-700' }} rounded-2xl shadow-lg p-8 text-white">
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-white bg-opacity-20 rounded-full mb-4">
                        @if($session->estReussi())
                            <svg class="h-16 w-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        @else
                            <svg class="h-16 w-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        @endif
                    </div>

                    <h2 class="text-4xl font-bold mb-2">
                        {{ number_format($session->note_obtenue, 1) }}/{{ $session->note_maximale ?? $examen->note_totale }}
                    </h2>
                    
                    <p class="text-2xl font-semibold mb-1">
                        {{ $session->estReussi() ? '🎉 Félicitations ! Vous avez réussi !' : '😔 Malheureusement, vous n\'avez pas réussi.' }}
                    </p>
                    
                    <p class="text-lg opacity-90">
                        Pourcentage : {{ number_format($session->pourcentage ?? $session->calculerPourcentage(), 1) }}%
                        (Seuil : {{ $examen->seuil_reussite }}%)
                    </p>
                </div>
            </div>
        @else
            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-6 rounded-r-lg">
                <div class="flex items-start">
                    <svg class="h-6 w-6 text-yellow-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <h3 class="text-sm font-semibold text-yellow-800 mb-1">En attente de correction</h3>
                        <p class="text-sm text-yellow-700">
                            Votre examen a été soumis avec succès. Les résultats seront disponibles après correction par l'enseignant.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Questions répondues</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $session->questions_repondues ?? $reponses->count() }}/{{ $examen->questions->count() }}</p>
                    </div>
                    <div class="bg-iris-blue bg-opacity-10 rounded-full p-3">
                        <svg class="h-6 w-6 text-iris-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Temps passé</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $session->getTempsPasseFormate() }}</p>
                    </div>
                    <div class="bg-iris-yellow bg-opacity-20 rounded-full p-3">
                        <svg class="h-6 w-6 text-iris-yellow-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            @if($session->note_obtenue !== null)
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Réponses correctes</p>
                            @php
                                $correctes = $reponses->where('est_correcte', true)->count();
                            @endphp
                            <p class="text-2xl font-bold text-green-600 mt-1">{{ $correctes }}/{{ $reponses->count() }}</p>
                        </div>
                        <div class="bg-green-100 rounded-full p-3">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Réponses incorrectes</p>
                            @php
                                $incorrectes = $reponses->where('est_correcte', false)->count();
                            @endphp
                            <p class="text-2xl font-bold text-red-600 mt-1">{{ $incorrectes }}</p>
                        </div>
                        <div class="bg-red-100 rounded-full p-3">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- ⚠️ ALERTE SI DÉCISION ENSEIGNANT --}}
        @if($session->decision_enseignant && $session->decision_enseignant !== 'aucune')   
            <div class="bg-red-50 dark:bg-red-900 dark:bg-opacity-20 border-2 border-red-300 dark:border-red-700 rounded-xl p-6 mt-6">   
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        @if($session->decision_enseignant === 'avertissement')
                            <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center">
                                <span class="text-2xl">⚠️</span>
                            </div>
                        @elseif($session->decision_enseignant === 'annulation')                   
                            <div class="w-12 h-12 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center">                       
                                <span class="text-2xl">❌</span>
                            </div>
                        @elseif($session->decision_enseignant === 'sanction')
                            <div class="w-12 h-12 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center">
                                <span class="text-2xl">🚫</span>
                            </div>
                        @endif
                    </div>
            
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-red-800 dark:text-red-200 mb-2">           
                            @if($session->decision_enseignant === 'avertissement')
                                ⚠️ Avertissement de l'enseignant
                            @elseif($session->decision_enseignant === 'annulation')
                                ❌ Examen annulé
                            @elseif($session->decision_enseignant === 'sanction')
                                🚫 Sanction appliquée
                            @endif
                        </h3>
                
                
                        @if($session->commentaire_enseignant)
                            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 mt-3">
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Commentaire :</p>
                                <p class="text-base text-gray-900 dark:text-white italic">
                                    "{{ $session->commentaire_enseignant }}"                      
                                </p>
                            </div>
                        @endif
                
                        @if($session->alertes_triche && count($session->alertes_triche) > 0)
                            <div class="mt-4">
                                <p class="text-sm font-semibold text-red-700 dark:text-red-300 mb-2">
                                    Comportements détectés :
                                </p>
                                <ul class="text-sm text-red-600 dark:text-red-400 space-y-1">
                                    @foreach($session->alertes_triche as $alerte)
                                        <li>• 
                                            @if($alerte['type'] === 'changement_onglet')
                                                Changement d'onglet
                                            @elseif($alerte['type'] === 'tentative_copier')    
                                                Tentative de copier
                                            @else                                       
                                                {{ $alerte['type'] }}
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                
                        <p class="text-xs text-red-600 dark:text-red-400 mt-4">
                            Décision prise le {{ $session->date_decision?->format('d/m/Y à H:i') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Détails des réponses -->
        @if($session->note_obtenue !== null)
            <div class="bg-white rounded-2xl shadow-sm p-8">
                <h2 class="text-xl font-bold text-iris-black-900 mb-6">Détails des réponses</h2>

                <div class="space-y-6">
                    @foreach($examen->questions as $index => $question)
                        @php
                            $reponseEtudiant = $reponses->get($question->id);
                        @endphp

                        <div class="border-2 {{ $reponseEtudiant && $reponseEtudiant->est_correcte ? 'border-green-200 bg-green-50' : ($reponseEtudiant && $reponseEtudiant->est_correcte === false ? 'border-red-200 bg-red-50' : 'border-gray-200') }} rounded-xl p-6">
                            <div class="flex items-start space-x-4">
                                <!-- Numéro -->
                                <div class="bg-iris-blue bg-opacity-10 rounded-full w-12 h-12 flex items-center justify-center flex-shrink-0">
                                    <span class="text-iris-blue font-bold text-lg">{{ $index + 1 }}</span>
                                </div>

                                <div class="flex-1">
                                    <!-- En-tête -->
                                    <div class="flex items-center justify-between mb-3">
                                        <h3 class="text-lg font-bold text-gray-900">Question {{ $index + 1 }}</h3>
                                        <div class="flex items-center space-x-2">
                                            @if($reponseEtudiant && $reponseEtudiant->est_correcte !== null)
                                                @if($reponseEtudiant->est_correcte)
                                                    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                                        ✓ Correct
                                                    </span>
                                                @else
                                                    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                                        ✗ Incorrect
                                                    </span>
                                                @endif
                                            @else
                                                <span class="px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    En correction
                                                </span>
                                            @endif
                                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">
                                                {{ $reponseEtudiant ? number_format($reponseEtudiant->points_obtenus ?? 0, 1) : 0 }}/{{ $question->pivot->points }} pts
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Énoncé -->
                                    <p class="text-gray-900 mb-4">{{ $question->enonce }}</p>

                                    <!-- Réponses selon le type -->
                                    @if($question->type !== 'reponse_courte')
                                        <div class="space-y-2 mb-4">
                                            @foreach($question->reponses as $reponse)
                                                @php
                                                    $estReponseEtudiant = false;
                                                    if ($reponseEtudiant) {
                                                        if ($question->type === 'choix_multiple') {
                                                            $reponsesMultiples = json_decode($reponseEtudiant->reponses_multiples ?? '[]', true);
                                                            $estReponseEtudiant = in_array($reponse->id, $reponsesMultiples);
                                                        } else {
                                                            $estReponseEtudiant = $reponseEtudiant->reponse_id == $reponse->id;
                                                        }
                                                    }
                                                    $estBonneReponse = $reponse->est_correcte;
                                                @endphp

                                                <div class="flex items-center space-x-3 p-3 rounded-lg {{ $estBonneReponse ? 'bg-green-100 border-2 border-green-500' : ($estReponseEtudiant ? 'bg-red-100 border-2 border-red-500' : 'bg-gray-50') }}">
                                                    @if($estBonneReponse)
                                                        <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    @elseif($estReponseEtudiant)
                                                        <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    @else
                                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <circle cx="12" cy="12" r="10" />
                                                        </svg>
                                                    @endif
                                                    <span class="{{ $estBonneReponse ? 'font-semibold text-green-800' : ($estReponseEtudiant ? 'font-semibold text-red-800' : 'text-gray-700') }}">
                                                        {{ $reponse->texte }}
                                                        @if($estReponseEtudiant && !$estBonneReponse)
                                                            <span class="text-sm ml-2">(Votre réponse)</span>
                                                        @endif
                                                        @if($estBonneReponse)
                                                            <span class="text-sm ml-2">(Bonne réponse)</span>
                                                        @endif
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <!-- Réponse courte -->
                                        <div class="bg-gray-100 rounded-lg p-4 mb-4">
                                            <p class="text-sm font-semibold text-gray-700 mb-2">Votre réponse :</p>
                                            <p class="text-gray-900">{{ $reponseEtudiant->reponse_texte ?? 'Aucune réponse' }}</p>
                                        </div>
                                    @endif

                                    <!-- Explication -->
                                    @if($question->explication)
                                        <div class="p-4 bg-blue-50 border-l-4 border-iris-blue rounded-r-lg">
                                            <p class="text-sm font-semibold text-blue-900 mb-1">💡 Explication</p>
                                            <p class="text-blue-800">{{ $question->explication }}</p>
                                        </div>
                                    @endif

                                    <!-- Commentaire du correcteur -->
                                    @if($reponseEtudiant && $reponseEtudiant->commentaire_correcteur)
                                        <div class="p-4 bg-purple-50 border-l-4 border-purple-500 rounded-r-lg mt-3">
                                            <p class="text-sm font-semibold text-purple-900 mb-1">📝 Commentaire de l'enseignant</p>
                                            <p class="text-purple-800">{{ $reponseEtudiant->commentaire_correcteur }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Actions -->
        <div class="bg-white rounded-2xl shadow-sm p-8">
            <div class="flex items-center justify-center space-x-4">
                <a href="{{ route('etudiant.examens.index') }}" 
                   class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition-all">
                    Retour aux examens
                </a>

                @if($session->numero_tentative < $examen->nombre_tentatives_max && now()->gte($examen->date_debut) && now()->lte($examen->date_fin))
                    <a href="{{ route('etudiant.examens.show', $examen->id) }}" 
                       class="px-6 py-3 bg-iris-yellow text-iris-black-900 rounded-lg font-semibold hover:bg-iris-yellow-600 transition-all">
                        🔄 Réessayer (Tentative {{ $session->numero_tentative + 1 }}/{{ $examen->nombre_tentatives_max }})
                    </a>
                @endif
            </div>
        </div>
    </div>
@endsection