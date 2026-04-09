@extends('layouts.app')

@section('title', 'Passer l\'examen - ' . $examen->titre)

@push('styles')
<style>
    .exam-content {
        user-select: none;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
    }
    
    .cheat-warning {
        animation: shake 0.5s;
    }
    
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-10px); }
        75% { transform: translateX(10px); }
    }
</style>
@endpush

@section('content')
<div class="space-y-6 exam-content">
    <!-- En-tête fixe avec timer -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 sticky top-0 z-50 border-b-4 border-iris-yellow">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $examen->titre }}</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $examen->matiere->nom }} • Tentative {{ $session->numero_tentative }}</p>
            </div>

            <div class="text-center">
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Temps restant</p>
                <div id="timer" class="text-3xl font-bold text-red-600">--:--</div>
            </div>

            <div>
                <button type="button" 
                        onclick="confirmerSoumission()"
                        class="px-6 py-3 bg-iris-yellow text-gray-900 rounded-lg font-bold hover:bg-yellow-600 transition-all shadow-lg">
                    📤 Soumettre l'examen
                </button>
            </div>
        </div>

        <div id="cheat-counter" class="hidden mt-4 p-3 bg-red-100 border-2 border-red-500 rounded-lg">
            <p class="text-red-800 font-bold text-center">
                ⚠️ <span id="cheat-count">0</span> tentative(s) de triche détectée(s)
            </p>
        </div>
    </div>

    <!-- Progression -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Progression</span>
            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300" id="progression-text">0/{{ $questions->count() }}</span>
        </div>
        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
            <div id="progression-bar" class="bg-iris-blue h-3 rounded-full transition-all duration-300" style="width: 0%"></div>
        </div>
    </div>

    <!-- Formulaire -->
    <form id="examen-form" action="{{ route('etudiant.examens.soumettre', $session->id) }}" method="POST">
        @csrf
        
        <input type="hidden" name="temps_passe" id="temps-passe" value="0">
        <input type="hidden" name="changements_onglet" id="changements-onglet" value="0">
        <input type="hidden" name="tentatives_copier" id="tentatives-copier" value="0">

        <div class="space-y-6">
            @foreach($questions as $index => $question)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-8 question-block" data-question-id="{{ $question->id }}">
                    <div class="flex items-start space-x-4">
                        <div class="bg-iris-blue bg-opacity-10 dark:bg-opacity-20 rounded-full w-12 h-12 flex items-center justify-center flex-shrink-0">
                            <span class="text-iris-blue font-bold text-lg">{{ $index + 1 }}</span>
                        </div>

                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Question {{ $index + 1 }}</h3>
                                <span class="px-3 py-1 text-sm font-semibold rounded-full bg-iris-yellow bg-opacity-20 text-yellow-800 dark:text-yellow-300">
                                    {{ $question->pivot->points }} pts
                                </span>
                            </div>

                            <p class="text-gray-900 dark:text-gray-100 mb-6 text-lg leading-relaxed">{{ $question->enonce }}</p>

                            @if($question->type === 'choix_unique' || $question->type === 'vrai_faux')
                                <div class="space-y-3">
                                    @foreach($question->reponses as $reponse)
                                        <label class="flex items-start space-x-3 p-4 border-2 border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer hover:border-iris-yellow hover:bg-yellow-50 dark:hover:bg-gray-700 transition-all">
                                            <input type="radio" 
                                                   name="reponses[{{ $question->id }}]" 
                                                   value="{{ $reponse->id }}"
                                                   {{ isset($reponsesEtudiant[$question->id]) && $reponsesEtudiant[$question->id]->reponse_id == $reponse->id ? 'checked' : '' }}
                                                   class="mt-1 h-5 w-5 text-iris-yellow focus:ring-iris-yellow question-input"
                                                   data-question-id="{{ $question->id }}">
                                            <span class="flex-1 text-gray-900 dark:text-gray-100">{{ $reponse->texte }}</span>
                                        </label>
                                    @endforeach
                                </div>

                            @elseif($question->type === 'choix_multiple')
                                <div class="space-y-3">
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">⚠️ Cochez toutes les bonnes réponses</p>
                                    @foreach($question->reponses as $reponse)
                                        @php
                                            $isChecked = false;
                                            if (isset($reponsesEtudiant[$question->id]) && $reponsesEtudiant[$question->id]->reponses_multiples) {
                                                $reponsesMultiples = json_decode($reponsesEtudiant[$question->id]->reponses_multiples, true);
                                                $isChecked = in_array($reponse->id, $reponsesMultiples ?? []);
                                            }
                                        @endphp
                                        <label class="flex items-start space-x-3 p-4 border-2 border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer hover:border-iris-yellow hover:bg-yellow-50 dark:hover:bg-gray-700 transition-all">
                                            <input type="checkbox" 
                                                   name="reponses[{{ $question->id }}][]" 
                                                   value="{{ $reponse->id }}"
                                                   {{ $isChecked ? 'checked' : '' }}
                                                   class="mt-1 h-5 w-5 text-iris-yellow rounded focus:ring-iris-yellow question-input"
                                                   data-question-id="{{ $question->id }}">
                                            <span class="flex-1 text-gray-900 dark:text-gray-100">{{ $reponse->texte }}</span>
                                        </label>
                                    @endforeach
                                </div>

                            @elseif($question->type === 'texte_libre' || $question->type === 'reponse_courte')
                                <div class="bg-blue-50 dark:bg-blue-900 dark:bg-opacity-20 border-2 border-blue-300 dark:border-blue-700 rounded-lg p-6">
                                    <label for="reponse_{{ $question->id }}" class="block text-sm font-semibold text-blue-900 dark:text-blue-300 mb-3">
                                        ✍️ Votre réponse :
                                    </label>
                                    <textarea name="reponses[{{ $question->id }}]" 
                                              id="reponse_{{ $question->id }}"
                                              rows="8"
                                              required
                                              class="w-full px-4 py-3 border-2 border-blue-300 dark:border-blue-700 dark:bg-gray-800 dark:text-white rounded-lg focus:ring-2 focus:ring-iris-yellow focus:border-iris-yellow transition-all question-input text-lg no-paste"
                                              data-question-id="{{ $question->id }}"
                                              placeholder="Tapez votre réponse ici...">{{ $reponsesEtudiant[$question->id]->reponse_texte ?? '' }}</textarea>
                                    <p class="text-sm text-blue-700 dark:text-blue-300 mt-3">💡 Cette réponse sera corrigée manuellement par l'enseignant</p>
                                </div>

                            @else
                                <div class="bg-yellow-50 dark:bg-yellow-900 dark:bg-opacity-20 border-2 border-yellow-400 dark:border-yellow-700 rounded-lg p-6">
                                    <p class="text-yellow-800 dark:text-yellow-300 font-semibold mb-3">⚠️ Type de question : {{ $question->type }}</p>
                                    <label for="reponse_{{ $question->id }}" class="block text-sm font-semibold text-yellow-900 dark:text-yellow-300 mb-3">
                                        Votre réponse :
                                    </label>
                                    <textarea name="reponses[{{ $question->id }}]" 
                                              id="reponse_{{ $question->id }}"
                                              rows="8"
                                              class="w-full px-4 py-3 border-2 border-yellow-300 dark:border-yellow-700 dark:bg-gray-800 dark:text-white rounded-lg focus:ring-2 focus:ring-iris-yellow focus:border-iris-yellow transition-all question-input text-lg no-paste"
                                              data-question-id="{{ $question->id }}"
                                              placeholder="Tapez votre réponse ici...">{{ $reponsesEtudiant[$question->id]->reponse_texte ?? '' }}</textarea>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-8 mt-6">
            <div class="flex items-center justify-center">
                <button type="button" 
                        onclick="confirmerSoumission()"
                        class="px-12 py-4 bg-iris-yellow text-gray-900 rounded-lg font-bold hover:bg-yellow-600 transition-all shadow-lg text-lg">
                    📤 Soumettre l'examen
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
let changementOnglet = 0;
let tentativesCopier = 0;
let tentativesColler = 0;
let formSubmitted = false;
let lastSaveTime = null;
let isSaving = false;

const dateFin = new Date("{{ $session->date_fin->toIso8601String() }}");
const timerElement = document.getElementById('timer');

function updateTimer() {
    const maintenant = new Date();
    const difference = dateFin - maintenant;

    if (difference <= 0) {
        timerElement.textContent = "00:00";
        //alert('⏰ Le temps est écoulé !');
        formSubmitted = true;
        document.getElementById('examen-form').submit();
        return;
    }

    const heures = Math.floor(difference / (1000 * 60 * 60));
    const minutes = Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60));
    const secondes = Math.floor((difference % (1000 * 60)) / 1000);

    let timeString = heures > 0 
        ? `${heures}h ${minutes.toString().padStart(2, '0')}m`
        : `${minutes.toString().padStart(2, '0')}:${secondes.toString().padStart(2, '0')}`;

    timerElement.textContent = timeString;

    if (difference < 5 * 60 * 1000) {
        timerElement.className = 'text-3xl font-bold text-red-600';
    } else if (difference < 15 * 60 * 1000) {
        timerElement.className = 'text-3xl font-bold text-orange-600';
    }
}

updateTimer();
const timerInterval = setInterval(updateTimer, 1000);

function updateProgression() {
    const totalQuestions = {{ $questions->count() }};
    const questionsRepondues = new Set();

    document.querySelectorAll('.question-input').forEach(input => {
        const questionId = input.getAttribute('data-question-id');
        
        if (input.type === 'radio' || input.type === 'checkbox') {
            if (input.checked) questionsRepondues.add(questionId);
        } else if (input.tagName === 'TEXTAREA') {
            if (input.value.trim() !== '') questionsRepondues.add(questionId);
        }
    });

    const nombreRepondues = questionsRepondues.size;
    const pourcentage = (nombreRepondues / totalQuestions) * 100;

    document.getElementById('progression-bar').style.width = pourcentage + '%';
    document.getElementById('progression-text').textContent = `${nombreRepondues}/${totalQuestions}`;
}

document.querySelectorAll('.question-input').forEach(input => {
    input.addEventListener('change', updateProgression);
    input.addEventListener('input', updateProgression);
});

updateProgression();

function getAllResponses() {
    const reponses = {};
    
    document.querySelectorAll('.question-block').forEach(block => {
        const questionId = block.getAttribute('data-question-id');
        
        const radioChecked = block.querySelector('input[type="radio"]:checked');
        if (radioChecked) {
            reponses[questionId] = radioChecked.value;
            return;
        }
        
        const checkboxes = block.querySelectorAll('input[type="checkbox"]:checked');
        if (checkboxes.length > 0) {
            reponses[questionId] = Array.from(checkboxes).map(cb => cb.value);
            return;
        }
        
        const textarea = block.querySelector('textarea');
        if (textarea && textarea.value.trim() !== '') {
            reponses[questionId] = textarea.value;
        }
    });
    
    return reponses;
}

async function autoSave() {
    if (isSaving) return;
    
    isSaving = true;
    const reponses = getAllResponses();
    
    if (Object.keys(reponses).length === 0) {
        isSaving = false;
        return;
    }
    
    try {
        const response = await fetch('/api/examens/sessions/{{ $session->id }}/autosave', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ reponses })
        });
        
        const data = await response.json();
        
        if (data.success) {
            lastSaveTime = new Date();
            showSaveIndicator('success', `✅ Sauvegardé (${data.saved_count})`);
        } else {
            if (data.time_expired) {
                alert('⏰ Le temps est écoulé !');
                formSubmitted = true;
                document.getElementById('examen-form').submit();
            } else {
                showSaveIndicator('error', '❌ Erreur');
            }
        }
    } catch (error) {
        showSaveIndicator('error', '❌ Erreur réseau');
    } finally {
        isSaving = false;
    }
}

function showSaveIndicator(type, message) {
    let indicator = document.getElementById('save-indicator');
    
    if (!indicator) {
        indicator = document.createElement('div');
        indicator.id = 'save-indicator';
        document.body.appendChild(indicator);
    }
    
    const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
    indicator.className = `fixed bottom-4 right-4 px-4 py-2 rounded-lg shadow-lg text-white text-sm font-medium z-50 transition-all ${bgColor}`;
    indicator.textContent = message;
    indicator.style.opacity = '1';
    
    setTimeout(() => { indicator.style.opacity = '0'; }, 3000);
}

setInterval(autoSave, 30000);

let saveTimeout;
document.querySelectorAll('.question-input').forEach(input => {
    input.addEventListener('change', () => {
        clearTimeout(saveTimeout);
        saveTimeout = setTimeout(autoSave, 2000);
    });
});

function confirmerSoumission() {
    const totalQuestions = {{ $questions->count() }};
    const questionsRepondues = new Set();

    document.querySelectorAll('.question-input').forEach(input => {
        const questionId = input.getAttribute('data-question-id');
        if (input.type === 'radio' || input.type === 'checkbox') {
            if (input.checked) questionsRepondues.add(questionId);
        } else if (input.tagName === 'TEXTAREA') {
            if (input.value.trim() !== '') questionsRepondues.add(questionId);
        }
    });

    const nombreRepondues = questionsRepondues.size;
    const nombreNonRepondues = totalQuestions - nombreRepondues;

    let message = `Vous avez répondu à ${nombreRepondues}/${totalQuestions} question(s).\n\n`;
    if (nombreNonRepondues > 0) message += `⚠️ ${nombreNonRepondues} sans réponse.\n\n`;
    if (changementOnglet > 0) message += `⚠️ ${changementOnglet} changement(s) d'onglet.\n\n`;
    message += `Confirmer la soumission ?\n\n⚠️ Vous ne pourrez plus modifier vos réponses.`;

    if (confirm(message)) {
        document.getElementById('changements-onglet').value = changementOnglet;
        document.getElementById('tentatives-copier').value = tentativesCopier;
        formSubmitted = true;
        document.getElementById('examen-form').submit();
    }
}

document.getElementById('examen-form').addEventListener('submit', () => { formSubmitted = true; });

window.addEventListener('beforeunload', (e) => {
    if (!formSubmitted) {
        autoSave();
        e.preventDefault();
        e.returnValue = 'Examen en cours. Quitter ?';
        return e.returnValue;
    }
});

function showCheatWarning(message) {
    const warning = document.createElement('div');
    warning.className = 'fixed top-20 right-4 bg-red-500 text-white px-6 py-4 rounded-lg shadow-2xl z-50 cheat-warning';
    warning.innerHTML = `<p class="font-bold">${message}</p>`;
    document.body.appendChild(warning);
    setTimeout(() => { warning.remove(); }, 5000);
}

function updateCheatCounter() {
    const total = changementOnglet + tentativesCopier + tentativesColler;
    if (total > 0) {
        document.getElementById('cheat-counter').classList.remove('hidden');
        document.getElementById('cheat-count').textContent = total;
    }
}

document.addEventListener('visibilitychange', () => {
    if (document.hidden) {
        changementOnglet++;
        showCheatWarning(`⚠️ Changement d'onglet (${changementOnglet})`);
        updateCheatCounter();
    }
});

document.addEventListener('copy', (e) => {
    if (e.target.closest('.exam-content')) {
        e.preventDefault();
        tentativesCopier++;
        showCheatWarning('⛔ Copier désactivé !');
        updateCheatCounter();
    }
});

document.addEventListener('paste', (e) => {
    if (e.target.classList.contains('no-paste')) {
        e.preventDefault();
        tentativesColler++;
        showCheatWarning('⛔ Coller désactivé !');
        updateCheatCounter();
    }
});

document.addEventListener('contextmenu', (e) => {
    if (e.target.closest('.exam-content')) {
        e.preventDefault();
        showCheatWarning('⛔ Clic droit désactivé !');
    }
});

document.addEventListener('keydown', (e) => {
    if (e.keyCode === 123 || 
        (e.ctrlKey && (e.keyCode === 85 || e.keyCode === 83)) ||
        (e.ctrlKey && e.shiftKey && (e.keyCode === 73 || e.keyCode === 67))) {
        e.preventDefault();
        showCheatWarning('⛔ Raccourci désactivé !');
        return false;
    }
});

document.getElementById('examen-form').addEventListener('submit', () => {
    clearInterval(timerInterval);
    clearTimeout(saveTimeout);
});

console.log('✅ Système initialisé : Auto-save, Anti-triche, Timer');
</script>
@endpush