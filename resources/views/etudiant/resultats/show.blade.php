@extends('layouts.app')

@section('title', 'Ma Copie - ' . $session->examen->titre)

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <a href="{{ route('etudiant.resultats.index') }}" 
               class="text-gray-600 hover:text-iris-yellow transition-colors">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-iris-black-900">📄 Ma Copie</h1>
                <p class="text-gray-600 mt-1">{{ $session->examen->titre }}</p>
            </div>
        </div>

        <!-- ✅ BOUTONS EXPORT PDF -->
        <div class="flex gap-3">
            <!-- Correction détaillée -->
            <a href="{{ route('etudiant.pdf.correction', $session->id) }}" 
               class="px-6 py-3 bg-purple-600 text-white rounded-xl font-bold hover:bg-purple-700 transition-all flex items-center shadow-lg">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                ✏️ Correction PDF
            </a>
            
            <!-- Certificat (si réussi) -->
            @if($details['est_reussi'])
                <a href="{{ route('etudiant.pdf.certificat', $session->id) }}" 
                   class="px-6 py-3 bg-yellow-600 text-white rounded-xl font-bold hover:bg-yellow-700 transition-all flex items-center shadow-lg">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                    🏆 Certificat
                </a>
            @endif
        </div>
    </div>

    <!-- Résumé de la copie -->
    <div class="bg-white rounded-2xl shadow-sm p-8">
        <div class="grid grid-cols-2 md:grid-cols-5 gap-6">
            <!-- Note finale -->
            <div class="text-center">
                <div class="bg-gradient-to-br {{ $details['est_reussi'] ? 'from-green-500 to-green-600' : 'from-red-500 to-red-600' }} rounded-2xl p-6 text-white">
                    <p class="text-sm font-semibold opacity-90 mb-2">Note finale</p>
                    <p class="text-4xl font-bold">{{ number_format($details['note_sur_20'], 2) }}</p>
                    <p class="text-sm opacity-75 mt-1">/20</p>
                </div>
            </div>

            <!-- Pourcentage -->
            <div class="text-center">
                <p class="text-sm text-gray-600 mb-2">Pourcentage</p>
                <p class="text-4xl font-bold text-gray-900">{{ round($details['pourcentage']) }}%</p>
                <p class="text-sm text-gray-500 mt-1">
                    {{ $details['note_obtenue'] }}/{{ $details['note_maximale'] }} pts
                </p>
            </div>

            <!-- Questions correctes -->
            <div class="text-center">
                <p class="text-sm text-gray-600 mb-2">Questions</p>
                <p class="text-4xl font-bold text-iris-blue">{{ $details['questions_correctes'] }}</p>
                <p class="text-sm text-gray-500 mt-1">
                    / {{ $details['questions_totales'] }} correctes
                </p>
            </div>

            <!-- Temps passé -->
            <div class="text-center">
                <p class="text-sm text-gray-600 mb-2">Temps passé</p>
                <p class="text-2xl font-bold text-gray-900">{{ $details['temps_passe'] }}</p>
                <p class="text-sm text-gray-500 mt-1">
                    / {{ $session->examen->duree_minutes }} min
                </p>
            </div>

            <!-- Statut -->
            <div class="text-center">
                <p class="text-sm text-gray-600 mb-2">Résultat</p>
                <div class="mt-2">
                    @if($details['est_reussi'])
                        <span class="inline-flex items-center px-4 py-2 bg-green-100 text-green-800 rounded-full font-bold">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Réussi
                        </span>
                    @else
                        <span class="inline-flex items-center px-4 py-2 bg-red-100 text-red-800 rounded-full font-bold">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Échoué
                        </span>
                    @endif
                </div>
                <p class="text-xs text-gray-500 mt-2">
                    Seuil: {{ $session->examen->seuil_reussite }}%
                </p>
            </div>
        </div>

        <!-- Informations supplémentaires -->
        <div class="mt-6 pt-6 border-t border-gray-200">
            <div class="grid grid-cols-3 gap-4 text-sm text-gray-600">
                <div>
                    <span class="font-semibold">Matière :</span> {{ $session->examen->matiere->nom }}
                </div>
                <div>
                    <span class="font-semibold">Classe :</span> {{ $session->examen->classe->nom }}
                </div>
                <div>
                    <span class="font-semibold">Date de passage :</span> {{ $session->created_at->format('d/m/Y à H:i') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Détail des questions -->
    <div class="space-y-6">
        @foreach($session->examen->questions as $index => $question)
            @php
                $reponse = $reponses->get($question->id);
                $pointsObtenus = $reponse ? $reponse->points_obtenus : 0;
                $pointsMax = $question->pivot->points;
                $estCorrecte = $reponse && $reponse->est_correcte;
            @endphp

            <div class="bg-white rounded-2xl shadow-sm p-8">
                <div class="flex items-start space-x-4">
                    <!-- Numéro de la question -->
                    <div class="bg-iris-blue bg-opacity-10 rounded-full w-12 h-12 flex items-center justify-center flex-shrink-0">
                        <span class="text-iris-blue font-bold text-lg">{{ $index + 1 }}</span>
                    </div>

                    <div class="flex-1">
                        <!-- En-tête de la question -->
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-900">Question {{ $index + 1 }}</h3>
                            <div class="flex items-center space-x-3">
                                <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $estCorrecte ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $estCorrecte ? '✓ Correcte' : '✗ Incorrecte' }}
                                </span>
                                <span class="px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">
                                    {{ number_format($pointsObtenus, 1) }}/{{ $pointsMax }} pts
                                </span>
                            </div>
                        </div>

                        <!-- Énoncé -->
                        <div class="bg-gray-50 rounded-lg p-4 mb-4">
                            <p class="text-gray-900 font-medium">{{ $question->enonce }}</p>
                        </div>

                        <!-- Votre réponse -->
                        <div class="mb-4">
                            <p class="text-sm font-semibold text-gray-700 mb-2">📝 Votre réponse :</p>
                            
                            @if($question->type === 'qcm_unique' || $question->type === 'qcm_multiple')
                                <div class="space-y-2">
                                    @foreach($question->propositions as $prop)
                                        @php
                                            $reponsesDonnees = $reponse ? json_decode($reponse->reponse_donnee, true) : [];
                                            $estSelectionnee = is_array($reponsesDonnees) && in_array($prop->id, $reponsesDonnees);
                                            $estBonneReponse = $prop->est_correcte;
                                        @endphp

                                        <div class="flex items-center space-x-3 p-3 rounded-lg
                                            @if($estSelectionnee && $estBonneReponse) bg-green-100 border-2 border-green-300
                                            @elseif($estSelectionnee && !$estBonneReponse) bg-red-100 border-2 border-red-300
                                            @elseif(!$estSelectionnee && $estBonneReponse) bg-blue-50 border-2 border-blue-300
                                            @else bg-gray-50 border-2 border-gray-200
                                            @endif">
                                            
                                            @if($estSelectionnee)
                                                <svg class="h-5 w-5 {{ $estBonneReponse ? 'text-green-600' : 'text-red-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <circle cx="10" cy="10" r="8" />
                                                </svg>
                                            @else
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 20 20">
                                                    <circle cx="10" cy="10" r="8" stroke-width="2" />
                                                </svg>
                                            @endif

                                            <span class="flex-1 {{ $estSelectionnee ? 'font-semibold' : '' }}">
                                                {{ $prop->texte }}
                                            </span>

                                            @if($estBonneReponse && !$estSelectionnee)
                                                <span class="text-sm text-blue-600 font-semibold">✓ Bonne réponse</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                            @elseif($question->type === 'vrai_faux')
                                @php
                                    $reponseEtudiant = $reponse ? $reponse->reponse_donnee : null;
                                    $bonneReponse = $question->reponse_correcte;
                                @endphp
                                
                                <div class="space-y-2">
                                    <div class="flex items-center space-x-3 p-3 rounded-lg
                                        @if($reponseEtudiant === 'vrai' && $bonneReponse === 'vrai') bg-green-100 border-2 border-green-300
                                        @elseif($reponseEtudiant === 'vrai' && $bonneReponse !== 'vrai') bg-red-100 border-2 border-red-300
                                        @elseif($reponseEtudiant !== 'vrai' && $bonneReponse === 'vrai') bg-blue-50 border-2 border-blue-300
                                        @else bg-gray-50 border-2 border-gray-200
                                        @endif">
                                        
                                        @if($reponseEtudiant === 'vrai')
                                            <svg class="h-5 w-5 {{ $bonneReponse === 'vrai' ? 'text-green-600' : 'text-red-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <circle cx="10" cy="10" r="8" />
                                            </svg>
                                        @else
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 20 20">
                                                <circle cx="10" cy="10" r="8" stroke-width="2" />
                                            </svg>
                                        @endif
                                        
                                        <span class="flex-1 {{ $reponseEtudiant === 'vrai' ? 'font-semibold' : '' }}">Vrai</span>
                                        
                                        @if($bonneReponse === 'vrai' && $reponseEtudiant !== 'vrai')
                                            <span class="text-sm text-blue-600 font-semibold">✓ Bonne réponse</span>
                                        @endif
                                    </div>

                                    <div class="flex items-center space-x-3 p-3 rounded-lg
                                        @if($reponseEtudiant === 'faux' && $bonneReponse === 'faux') bg-green-100 border-2 border-green-300
                                        @elseif($reponseEtudiant === 'faux' && $bonneReponse !== 'faux') bg-red-100 border-2 border-red-300
                                        @elseif($reponseEtudiant !== 'faux' && $bonneReponse === 'faux') bg-blue-50 border-2 border-blue-300
                                        @else bg-gray-50 border-2 border-gray-200
                                        @endif">
                                        
                                        @if($reponseEtudiant === 'faux')
                                            <svg class="h-5 w-5 {{ $bonneReponse === 'faux' ? 'text-green-600' : 'text-red-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <circle cx="10" cy="10" r="8" />
                                            </svg>
                                        @else
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 20 20">
                                                <circle cx="10" cy="10" r="8" stroke-width="2" />
                                            </svg>
                                        @endif
                                        
                                        <span class="flex-1 {{ $reponseEtudiant === 'faux' ? 'font-semibold' : '' }}">Faux</span>
                                        
                                        @if($bonneReponse === 'faux' && $reponseEtudiant !== 'faux')
                                            <span class="text-sm text-blue-600 font-semibold">✓ Bonne réponse</span>
                                        @endif
                                    </div>
                                </div>

                            @elseif($question->type === 'reponse_courte' || $question->type === 'texte_libre')
                                <div class="bg-{{ $estCorrecte ? 'green' : 'red' }}-50 border-2 border-{{ $estCorrecte ? 'green' : 'red' }}-200 rounded-lg p-4">
                                    <p class="text-gray-900 whitespace-pre-wrap">{{ $reponse ? $reponse->reponse_donnee : 'Aucune réponse' }}</p>
                                </div>

                                @if($question->reponse_correcte && $question->type === 'reponse_courte')
                                    <div class="mt-3 bg-blue-50 border-2 border-blue-200 rounded-lg p-4">
                                        <p class="text-sm font-semibold text-blue-900 mb-1">✓ Réponse attendue :</p>
                                        <p class="text-gray-900">{{ $question->reponse_correcte }}</p>
                                    </div>
                                @endif
                            @endif
                        </div>

                        <!-- Commentaire du correcteur -->
                        @if($reponse && $reponse->commentaire_correcteur)
                            <div class="bg-purple-50 border-l-4 border-purple-400 p-4 rounded-r-lg">
                                <p class="text-sm font-semibold text-purple-900 mb-2">💬 Commentaire de l'enseignant :</p>
                                <p class="text-gray-900">{{ $reponse->commentaire_correcteur }}</p>
                            </div>
                        @endif

                        <!-- Explication (si disponible) -->
                        @if($question->explication)
                            <div class="mt-4 bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg">
                                <p class="text-sm font-semibold text-blue-900 mb-2">💡 Explication :</p>
                                <p class="text-gray-900">{{ $question->explication }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Récapitulatif final -->
    <div class="bg-gradient-to-r from-iris-blue to-purple-600 rounded-2xl shadow-lg p-8 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-2xl font-bold mb-2">
                    @if($details['est_reussi'])
                        🎉 Félicitations !
                    @else
                        💪 Continue tes efforts !
                    @endif
                </h3>
                <p class="opacity-90">
                    @if($details['est_reussi'])
                        Tu as réussi cet examen avec {{ number_format($details['note_sur_20'], 2) }}/20.
                    @else
                        Ne te décourage pas, tu as obtenu {{ number_format($details['note_sur_20'], 2) }}/20. Continue à travailler !
                    @endif
                </p>
            </div>
            <div class="text-right">
                <a href="{{ route('etudiant.examens.index') }}" 
                   class="inline-flex items-center px-6 py-3 bg-white text-iris-blue rounded-lg font-bold hover:bg-gray-100 transition-all">
                    📝 Voir les prochains examens
                </a>
            </div>
        </div>
    </div>
</div>
@endsection