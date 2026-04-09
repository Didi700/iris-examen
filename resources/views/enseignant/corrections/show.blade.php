@extends('layouts.app')

@section('title', 'Correction - ' . $session->examen->titre)

@push('styles')
<style>
    .question-card {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }
    
    .question-card.qcm {
        border-left-color: #10B981;
    }
    
    .question-card.ouverte {
        border-left-color: #F59E0B;
    }
    
    .question-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .reponse-etudiant {
        background: #DBEAFE;
        padding: 1rem;
        border-radius: 0.5rem;
        border-left: 4px solid #3B82F6;
        color: #1E3A8A;
        font-weight: 500;
    }
    
    .dark .reponse-etudiant {
        background: #1E3A8A;
        color: #BFDBFE;
        border-left-color: #60A5FA;
    }
    
    .reponse-correcte {
        background: #D1FAE5 !important;
        color: #065F46 !important;
        border-left-color: #10B981 !important;
    }
    
    .dark .reponse-correcte {
        background: #065F46 !important;
        color: #D1FAE5 !important;
        border-left-color: #34D399 !important;
    }
    
    .reponse-incorrecte {
        background: #FEE2E2 !important;
        color: #991B1B !important;
        border-left-color: #EF4444 !important;
    }
    
    .dark .reponse-incorrecte {
        background: #991B1B !important;
        color: #FEE2E2 !important;
        border-left-color: #F87171 !important;
    }
    
    .reponse-partielle {
        background: #FED7AA !important;
        color: #92400E !important;
        border-left-color: #F59E0B !important;
    }
    
    .dark .reponse-partielle {
        background: #92400E !important;
        color: #FED7AA !important;
        border-left-color: #FBBF24 !important;
    }
    
    .sticky-header {
        position: sticky;
        top: 0;
        z-index: 10;
        background: white;
        border-bottom: 2px solid #E5E7EB;
    }
    
    .dark .sticky-header {
        background: #1F2937;
        border-bottom-color: #374151;
    }

    .sticky-footer {
        position: sticky;
        bottom: 0;
        z-index: 10;
        background: white;
        border-top: 2px solid #E5E7EB;
    }
    
    .dark .sticky-footer {
        background: #1F2937;
        border-top-color: #374151;
    }
    
    .reponse-etudiant p {
        color: inherit !important;
        margin: 0;
    }

    .option-checkbox {
        width: 1.25rem;
        height: 1.25rem;
    }
</style>
@endpush

@section('content')
<form method="POST" action="{{ route('enseignant.corrections.corriger', $session) }}" class="space-y-6">
    @csrf
    
    <div class="sticky-header p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-4">
                <a href="{{ route('enseignant.corrections.index') }}" 
                   class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
                    ← Retour
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        ✏️ Correction : {{ $session->examen->titre }}
                    </h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        👤 {{ $session->etudiant->prenom ?? 'N/A' }} {{ $session->etudiant->nom ?? 'N/A' }} 
                        ({{ $session->etudiant->matricule ?? 'N/A' }})
                    </p>
                </div>
            </div>
            
            <div class="flex items-center space-x-3">
                @if($session->statut_correction === 'en_attente' || $session->statut_correction === 'corrige')
                    <button type="submit" 
                            class="px-6 py-2.5 bg-iris-blue text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                        💾 Enregistrer
                    </button>
                    
                    @if($stats['questions_a_corriger'] === 0)
                        <button type="submit" 
                                name="publier" 
                                value="1"
                                class="px-6 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
                            ✅ Publier les notes
                        </button>
                    @endif
                @endif

                @if($session->statut_correction === 'publie')
                    <form method="POST" action="{{ route('enseignant.corrections.depublier', $session) }}" class="inline">
                        @csrf
                        <button type="submit" 
                                onclick="return confirm('Voulez-vous vraiment dépublier cette correction ? L\'étudiant ne verra plus ses résultats.')"
                                class="px-6 py-2.5 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors font-semibold">
                            🔒 Dépublier
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
            <div class="bg-blue-50 dark:bg-blue-900 dark:bg-opacity-20 rounded-lg p-3">
                <div class="text-xs text-blue-600 dark:text-blue-400 mb-1">Questions totales</div>
                <div class="text-xl font-bold text-blue-700 dark:text-blue-300">{{ $stats['questions_total'] }}</div>
            </div>
            <div class="bg-green-50 dark:bg-green-900 dark:bg-opacity-20 rounded-lg p-3">
                <div class="text-xs text-green-600 dark:text-green-400 mb-1">Auto-corrigées</div>
                <div class="text-xl font-bold text-green-700 dark:text-green-300">{{ $stats['questions_auto'] }}</div>
            </div>
            <div class="bg-purple-50 dark:bg-purple-900 dark:bg-opacity-20 rounded-lg p-3">
                <div class="text-xs text-purple-600 dark:text-purple-400 mb-1">Manuelles corrigées</div>
                <div class="text-xl font-bold text-purple-700 dark:text-purple-300">{{ $stats['questions_corrigees'] }}</div>
            </div>
            <div class="bg-orange-50 dark:bg-orange-900 dark:bg-opacity-20 rounded-lg p-3">
                <div class="text-xs text-orange-600 dark:text-orange-400 mb-1">À corriger</div>
                <div class="text-xl font-bold text-orange-700 dark:text-orange-300">{{ $stats['questions_a_corriger'] }}</div>
            </div>
            <div class="bg-indigo-50 dark:bg-indigo-900 dark:bg-opacity-20 rounded-lg p-3">
                <div class="text-xs text-indigo-600 dark:text-indigo-400 mb-1">Note actuelle</div>
                <div class="text-xl font-bold text-indigo-700 dark:text-indigo-300">{{ number_format($stats['note_actuelle'], 1) }}</div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                <div class="text-xs text-gray-600 dark:text-gray-400 mb-1">Note maximale</div>
                <div class="text-xl font-bold text-gray-700 dark:text-gray-300">{{ $stats['note_maximale'] }}</div>
            </div>
        </div>

        @if($stats['questions_total'] > 0)
            <div class="mt-4">
                <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <span>Progression de la correction</span>
                    <span class="font-semibold">
                        {{ $stats['questions_auto'] + $stats['questions_corrigees'] }} / {{ $stats['questions_total'] }} 
                        ({{ round((($stats['questions_auto'] + $stats['questions_corrigees']) / $stats['questions_total']) * 100) }}%)
                    </span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-3">
                    <div class="bg-gradient-to-r from-iris-blue to-green-500 h-3 rounded-full transition-all duration-500" 
                         style="width: {{ round((($stats['questions_auto'] + $stats['questions_corrigees']) / $stats['questions_total']) * 100) }}%"></div>
                </div>
            </div>
        @endif

        @if($session->statut_correction === 'publie')
            <div class="mt-4 p-4 bg-green-50 dark:bg-green-900 dark:bg-opacity-20 border-l-4 border-green-500 rounded-r-lg">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-green-600 dark:text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm font-semibold text-green-800 dark:text-green-200">
                        ✅ Cette correction a été publiée. L'étudiant peut voir ses résultats.
                    </p>
                </div>
            </div>
        @endif>

        @if($stats['questions_a_corriger'] > 0)
            <div class="mt-4 p-4 bg-orange-50 dark:bg-orange-900 dark:bg-opacity-20 border-l-4 border-orange-500 rounded-r-lg">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-orange-600 dark:text-orange-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <p class="text-sm font-semibold text-orange-800 dark:text-orange-200">
                        ⚠️ Il reste {{ $stats['questions_a_corriger'] }} question(s) à corriger avant de pouvoir publier les résultats.
                    </p>
                </div>
            </div>
        @endif
    </div>

    <div class="space-y-6 px-6 pb-6">
        @foreach($session->examen->questions as $index => $question)
            @php
                $reponseEtudiant = $reponses->get($question->id);
                $pointsMax = $question->pivot->points ?? 0;
                $estQCM = in_array($question->type, ['choix_unique', 'choix_multiple', 'vrai_faux']);
                $estOuverte = in_array($question->type, ['ouverte', 'reponse_courte', 'texte_libre']);
            @endphp

            <div class="question-card {{ $estQCM ? 'qcm' : 'ouverte' }} bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <div class="flex items-center flex-wrap gap-2 mb-3">
                            <span class="px-3 py-1 bg-iris-blue text-white rounded-full text-sm font-semibold">
                                Question {{ $index + 1 }}
                            </span>
                            <span class="px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full text-sm font-semibold">
                                {{ $pointsMax }} point(s)
                            </span>
                            @if($estQCM)
                                <span class="px-3 py-1 bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 rounded-full text-xs font-semibold">
                                    ✅ Correction automatique
                                </span>
                            @else
                                <span class="px-3 py-1 bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200 rounded-full text-xs font-semibold">
                                    ✏️ Correction manuelle
                                </span>
                            @endif
                            
                            @if($reponseEtudiant && $reponseEtudiant->points_obtenus !== null)
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded-full text-xs font-semibold">
                                    ✓ Corrigée
                                </span>
                            @elseif($estOuverte)
                                <span class="px-3 py-1 bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 rounded-full text-xs font-semibold">
                                    ⏳ En attente
                                </span>
                            @endif
                        </div>
                        
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                            {{ $question->texte }}
                        </h3>
                        
                        @if($question->image)
                            <img src="{{ Storage::url($question->image) }}" 
                                 alt="Image question" 
                                 class="max-w-md rounded-lg mb-3 shadow-md">
                        @endif
                    </div>
                </div>

                @if($reponseEtudiant)
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                            📝 Réponse de l'étudiant :
                        </label>
                        
                        @if($question->type === 'choix_unique' || $question->type === 'vrai_faux')
                            @php
                                $reponseDonnee = $reponseEtudiant->reponse_donnee;
                                
                                if (is_array($reponseDonnee)) {
                                    $reponseId = $reponseDonnee['reponse_id'] ?? $reponseDonnee[0] ?? null;
                                } elseif (is_string($reponseDonnee)) {
                                    $decoded = json_decode($reponseDonnee, true);
                                    $reponseId = is_array($decoded) ? ($decoded['reponse_id'] ?? null) : $reponseDonnee;
                                } else {
                                    $reponseId = $reponseDonnee;
                                }
                                
                                $reponseChoisie = $question->reponses->where('id', $reponseId)->first();
                                $reponseCorrecte = $question->reponses->where('est_correcte', true)->first();
                            @endphp
                            
                            <div class="reponse-etudiant {{ $reponseChoisie && $reponseChoisie->est_correcte ? 'reponse-correcte' : 'reponse-incorrecte' }}">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        @if($reponseChoisie)
                                            <p class="font-medium">
                                                {{ $reponseChoisie->texte }}
                                            </p>
                                        @else
                                            <p class="italic opacity-75">Aucune réponse donnée</p>
                                        @endif
                                    </div>
                                    @if($reponseChoisie && $reponseChoisie->est_correcte)
                                        <span class="text-2xl ml-3">✓</span>
                                    @else
                                        <span class="text-2xl ml-3">✗</span>
                                    @endif
                                </div>
                            </div>
                            
                            @if($reponseChoisie && !$reponseChoisie->est_correcte && $reponseCorrecte)
                                <div class="mt-3 p-3 bg-green-50 dark:bg-green-900 dark:bg-opacity-20 border-l-4 border-green-500 rounded-r-lg">
                                    <p class="text-sm text-green-700 dark:text-green-300">
                                        <strong>✓ Bonne réponse :</strong> {{ $reponseCorrecte->texte }}
                                    </p>
                                </div>
                            @endif

                        @elseif($question->type === 'choix_multiple')
                            @php
                                $reponseDonnee = $reponseEtudiant->reponse_donnee;
                                
                                if (is_array($reponseDonnee)) {
                                    $reponsesChoisies = $reponseDonnee;
                                } elseif (is_string($reponseDonnee)) {
                                    $reponsesChoisies = json_decode($reponseDonnee, true) ?? [];
                                } else {
                                    $reponsesChoisies = [];
                                }
                                
                                $reponsesCorrectes = $question->reponses->where('est_correcte', true)->pluck('id')->toArray();
                                
                                $bonnesReponses = array_intersect($reponsesChoisies, $reponsesCorrectes);
                                $mauvaiseReponses = array_diff($reponsesChoisies, $reponsesCorrectes);
                                $reponsesManquantes = array_diff($reponsesCorrectes, $reponsesChoisies);
                                
                                $estParfait = empty($mauvaiseReponses) && empty($reponsesManquantes);
                                $estPartiel = !empty($bonnesReponses) && (!empty($mauvaiseReponses) || !empty($reponsesManquantes));
                                $estIncorrect = empty($bonnesReponses);
                            @endphp
                            
                            <div class="mb-3 p-3 rounded-lg {{ $estParfait ? 'bg-green-50 dark:bg-green-900 dark:bg-opacity-20 border-l-4 border-green-500' : ($estPartiel ? 'bg-orange-50 dark:bg-orange-900 dark:bg-opacity-20 border-l-4 border-orange-500' : 'bg-red-50 dark:bg-red-900 dark:bg-opacity-20 border-l-4 border-red-500') }}">
                                <p class="text-sm font-semibold {{ $estParfait ? 'text-green-800 dark:text-green-200' : ($estPartiel ? 'text-orange-800 dark:text-orange-200' : 'text-red-800 dark:text-red-200') }}">
                                    @if($estParfait)
                                        ✓ Toutes les réponses sont correctes
                                    @elseif($estPartiel)
                                        ⚠️ Réponse partielle : {{ count($bonnesReponses) }}/{{ count($reponsesCorrectes) }} bonnes réponses
                                    @else
                                        ✗ Aucune bonne réponse
                                    @endif
                                </p>
                            </div>

                            <div class="space-y-2">
                                @foreach($question->reponses as $reponse)
                                    @php
                                        $estChoisie = in_array($reponse->id, $reponsesChoisies);
                                        $estBonne = $reponse->est_correcte;
                                        
                                        if ($estChoisie && $estBonne) {
                                            $classeCSS = 'bg-green-50 dark:bg-green-900 dark:bg-opacity-20 border-2 border-green-500';
                                        } elseif ($estChoisie && !$estBonne) {
                                            $classeCSS = 'bg-red-50 dark:bg-red-900 dark:bg-opacity-20 border-2 border-red-500';
                                        } elseif (!$estChoisie && $estBonne) {
                                            $classeCSS = 'bg-blue-50 dark:bg-blue-900 dark:bg-opacity-20 border-2 border-blue-300';
                                        } else {
                                            $classeCSS = 'bg-gray-50 dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600';
                                        }
                                    @endphp
                                    
                                    <div class="flex items-center space-x-3 p-3 rounded-lg {{ $classeCSS }}">
                                        <input type="checkbox" 
                                               {{ $estChoisie ? 'checked' : '' }} 
                                               disabled 
                                               class="option-checkbox rounded">
                                        <span class="flex-1 {{ $estChoisie ? 'font-semibold' : '' }} text-gray-900 dark:text-white">
                                            {{ $reponse->texte }}
                                        </span>
                                        @if($estBonne && !$estChoisie)
                                            <span class="text-blue-600 dark:text-blue-400 font-bold text-sm">(Correcte)</span>
                                        @endif
                                        @if($estChoisie && $estBonne)
                                            <span class="text-green-600 dark:text-green-400 font-bold text-xl">✓</span>
                                        @elseif($estChoisie && !$estBonne)
                                            <span class="text-red-600 dark:text-red-400 font-bold text-xl">✗</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                        @else
                            @php
                                $reponseDonnee = $reponseEtudiant->reponse_donnee;
                                $texteReponse = 'Aucune réponse donnée';
                                
                                if (!empty($reponseDonnee)) {
                                    if (is_array($reponseDonnee)) {
                                        $texteReponse = $reponseDonnee['texte'] 
                                            ?? $reponseDonnee['reponse'] 
                                            ?? $reponseDonnee[0] 
                                            ?? json_encode($reponseDonnee);
                                    } elseif (is_string($reponseDonnee)) {
                                        $decoded = json_decode($reponseDonnee, true);
                                        if (is_array($decoded)) {
                                            $texteReponse = $decoded['texte'] 
                                                ?? $decoded['reponse'] 
                                                ?? $decoded[0] 
                                                ?? $reponseDonnee;
                                        } else {
                                            $texteReponse = $reponseDonnee;
                                        }
                                    } else {
                                        $texteReponse = (string)$reponseDonnee;
                                    }
                                }
                            @endphp
                            
                            <div class="reponse-etudiant">
                                <p class="whitespace-pre-wrap text-gray-900 dark:text-white" style="color: inherit !important;">
                                    {{ $texteReponse }}
                                </p>
                            </div>
                        @endif
                    </div>

                    @if($estOuverte && in_array($session->statut_correction, ['en_attente', 'corrige']))
                        <div class="border-t-2 border-gray-200 dark:border-gray-700 pt-5 mt-5 space-y-4">
                            <h4 class="text-md font-bold text-gray-900 dark:text-white mb-3">
                                ✏️ Correction de la réponse
                            </h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Points obtenus <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" 
                                           name="points[{{ $question->id }}]" 
                                           value="{{ old('points.' . $question->id, $reponseEtudiant->points_obtenus) }}"
                                           min="0" 
                                           max="{{ $pointsMax }}" 
                                           step="0.5"
                                           required
                                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-iris-blue focus:ring-iris-blue text-lg font-semibold"
                                           placeholder="0 - {{ $pointsMax }}">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        Maximum : {{ $pointsMax }} points
                                    </p>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    💬 Commentaire de correction
                                </label>
                                <textarea name="commentaire[{{ $question->id }}]" 
                                          rows="4"
                                          class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-iris-blue focus:ring-iris-blue"
                                          placeholder="Ajoutez un commentaire pour l'étudiant (facultatif)...">{{ old('commentaire.' . $question->id, $reponseEtudiant->commentaire ?? $reponseEtudiant->commentaire_correcteur) }}</textarea>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    Ce commentaire sera visible par l'étudiant après publication des résultats.
                                </p>
                            </div>
                        </div>
                    @elseif($estOuverte && $session->statut_correction === 'publie' && $reponseEtudiant->points_obtenus !== null)
                        <div class="border-t-2 border-gray-200 dark:border-gray-700 pt-5 mt-5">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    📊 Note attribuée :
                                </span>
                                <span class="text-2xl font-bold text-iris-blue">
                                    {{ number_format($reponseEtudiant->points_obtenus, 1) }} / {{ $pointsMax }}
                                </span>
                            </div>
                            
                            @if($reponseEtudiant->commentaire ?? $reponseEtudiant->commentaire_correcteur)
                                <div class="p-4 bg-blue-50 dark:bg-blue-900 dark:bg-opacity-20 border-l-4 border-iris-blue rounded-r-lg">
                                    <p class="text-sm text-gray-700 dark:text-gray-300">
                                        <strong>💬 Commentaire du correcteur :</strong><br>
                                        <span class="mt-1 block">{{ $reponseEtudiant->commentaire ?? $reponseEtudiant->commentaire_correcteur }}</span>
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endif
                @else
                    <div class="text-center py-12 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <svg class="h-16 w-16 mx-auto text-gray-400 dark:text-gray-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400 font-medium">
                            Aucune réponse donnée par l'étudiant
                        </p>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    @if($session->statut_correction === 'en_attente' || $session->statut_correction === 'corrige')
        <div class="sticky-footer p-6">
            <div class="flex items-center justify-between">
                <a href="{{ route('enseignant.corrections.index') }}" 
                   class="px-6 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors font-semibold">
                    ← Annuler
                </a>
                
                <div class="flex items-center space-x-3">
                    <button type="submit" 
                            class="px-8 py-2.5 bg-iris-blue text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold shadow-lg">
                        💾 Enregistrer la correction
                    </button>
                    
                    @if($stats['questions_a_corriger'] === 0)
                        <button type="submit" 
                                name="publier" 
                                value="1"
                                onclick="return confirm('Voulez-vous publier les résultats à l\'étudiant ? Il pourra voir sa note et ses corrections.')"
                                class="px-8 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold shadow-lg">
                            ✅ Enregistrer et publier
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endif
</form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const pointsInputs = document.querySelectorAll('input[name^="points"]');
    
    pointsInputs.forEach(input => {
        input.addEventListener('input', function() {
            const max = parseFloat(this.getAttribute('max'));
            const value = parseFloat(this.value);
            
            if (value > max) {
                this.value = max;
                showAlert('warning', `La note ne peut pas dépasser ${max} points`);
            }
            
            if (value < 0) {
                this.value = 0;
                showAlert('warning', 'La note ne peut pas être négative');
            }
        });
    });

    function showAlert(type, message) {
        const alertClass = type === 'warning' ? 'bg-orange-100 text-orange-800 border-orange-500' : 'bg-red-100 text-red-800 border-red-500';
        
        const alertDiv = document.createElement('div');
        alertDiv.className = `fixed top-4 right-4 p-4 rounded-lg border-l-4 shadow-lg z-50 ${alertClass}`;
        alertDiv.innerHTML = `
            <div class="flex items-center space-x-2">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span class="font-semibold">${message}</span>
            </div>
        `;
        
        document.body.appendChild(alertDiv);
        
        setTimeout(() => {
            alertDiv.remove();
        }, 3000);
    }
});
</script>
@endpush