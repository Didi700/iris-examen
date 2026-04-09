@extends('layouts.app')

@section('title', 'Gérer les questions - ' . $examen->titre)

@section('content')
    <div class="space-y-6">
        <!-- En-tête -->
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('enseignant.examens.show', $examen->id) }}" 
                   class="text-gray-600 hover:text-iris-yellow transition-colors">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-iris-black-900">Gérer les questions</h1>
                    <p class="text-gray-600 mt-1">{{ $examen->titre }}</p>
                </div>
            </div>

            <a href="{{ route('enseignant.questions.create') }}" 
               class="px-6 py-3 bg-iris-blue text-white rounded-lg font-semibold hover:bg-iris-blue-600 transition-all">
                + Créer une nouvelle question
            </a>
        </div>

        <!-- Messages -->
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
                <p class="text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                <p class="text-red-800">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold opacity-90">Questions ajoutées</h3>
                    <svg class="h-8 w-8 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-4xl font-bold">{{ $questionsExamen->count() }}</p>
            </div>

            <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-2xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold opacity-90">Points totaux</h3>
                    <svg class="h-8 w-8 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                </div>
                <p class="text-4xl font-bold">{{ $questionsExamen->sum('pivot.points') }}</p>
                <p class="text-sm opacity-75 mt-1">/ {{ $examen->note_totale }}</p>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold opacity-90">Questions disponibles</h3>
                    <svg class="h-8 w-8 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-4xl font-bold">{{ $questionsDisponibles->count() }}</p>
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold opacity-90">Note cible</h3>
                    <svg class="h-8 w-8 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <p class="text-4xl font-bold">{{ $examen->note_totale }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Questions de l'examen -->
            <div class="bg-white rounded-2xl shadow-sm p-8">
                <h2 class="text-xl font-bold text-iris-black-900 mb-6">
                    Questions de l'examen ({{ $questionsExamen->count() }})
                </h2>

                @if($questionsExamen->count() > 0)
                    <div class="space-y-4">
                        @foreach($questionsExamen as $question)
                            <div class="border-2 border-gray-200 rounded-lg p-4 hover:border-iris-yellow transition-all">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900 mb-2">{{ $question->enonce }}</p>
                                        <div class="flex items-center space-x-3 text-xs">
                                            <span class="px-2 py-1 bg-gray-100 rounded">{{ ucfirst($question->type) }}</span>
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded">{{ ucfirst($question->difficulte) }}</span>
                                            <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded font-semibold">{{ $question->pivot->points }} pts</span>
                                        </div>
                                    </div>
                                    <form action="{{ route('enseignant.examens.retirer-question', [$examen->id, $question->id]) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Retirer cette question ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="ml-4 text-red-600 hover:text-red-800">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="h-16 w-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-gray-600">Aucune question ajoutée</p>
                    </div>
                @endif
            </div>

            <!-- Questions disponibles -->
            <div class="bg-white rounded-2xl shadow-sm p-8">
                <h2 class="text-xl font-bold text-iris-black-900 mb-6">Questions disponibles</h2>
                <p class="text-sm text-gray-600 mb-4">Cliquez pour ajouter à l'examen</p>

                @if($questionsDisponibles->count() > 0)
                    <form action="{{ route('enseignant.examens.ajouter-questions', $examen->id) }}" method="POST" id="form-add-questions">
                        @csrf
                        
                        <div class="space-y-4 max-h-[600px] overflow-y-auto mb-6">
                            @foreach($questionsDisponibles as $question)
                                <div class="border-2 border-gray-200 rounded-lg p-4 hover:bg-gray-50 question-item">
                                    <label class="flex items-start cursor-pointer">
                                        <input type="checkbox" 
                                               name="questions_ids[]" 
                                               value="{{ $question->id }}"
                                               class="mt-1 h-5 w-5 text-iris-yellow rounded question-check">
                                        <div class="ml-3 flex-1">
                                            <p class="font-medium text-gray-900 mb-2">{{ $question->enonce }}</p>
                                            <div class="flex items-center space-x-3 text-xs mb-3">
                                                <span class="px-2 py-1 bg-gray-100 rounded">{{ ucfirst($question->type) }}</span>
                                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded">{{ ucfirst($question->difficulte) }}</span>
                                            </div>
                                            <div class="points-field hidden">
                                                <label class="block text-xs font-medium text-gray-700 mb-1">Points</label>
                                                <input type="number" 
                                                       name="points_{{ $question->id }}" 
                                                       value="5"
                                                       min="0.5"
                                                       step="0.5"
                                                       class="w-24 px-3 py-1 border border-gray-300 rounded text-sm">
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <button type="submit" 
                                id="btn-add"
                                class="w-full px-6 py-3 bg-iris-yellow text-iris-black-900 rounded-lg font-bold hover:bg-iris-yellow-600 transition-all disabled:opacity-50"
                                disabled>
                            + Ajouter les questions (<span id="count">0</span>)
                        </button>
                    </form>
                @else
                    <div class="text-center py-12">
                        <p class="text-gray-600">Aucune question disponible</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        const checkboxes = document.querySelectorAll('.question-check');
        const btnAdd = document.getElementById('btn-add');
        const countSpan = document.getElementById('count');

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const item = this.closest('.question-item');
                const pointsField = item.querySelector('.points-field');
                
                if (this.checked) {
                    pointsField.classList.remove('hidden');
                } else {
                    pointsField.classList.add('hidden');
                }
                
                updateCount();
            });
        });

        function updateCount() {
            const checked = document.querySelectorAll('.question-check:checked').length;
            countSpan.textContent = checked;
            btnAdd.disabled = checked === 0;
        }

        // Transformer en format attendu par le contrôleur
        document.getElementById('form-add-questions').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData();
            const checked = document.querySelectorAll('.question-check:checked');
            
            checked.forEach((cb, index) => {
                const qId = cb.value;
                const pointsInput = document.querySelector(`input[name="points_${qId}"]`);
                
                formData.append(`questions[${index}][question_id]`, qId);
                formData.append(`questions[${index}][points]`, pointsInput.value);
            });
            
            // Créer un nouveau formulaire avec les bonnes données
            const newForm = document.createElement('form');
            newForm.method = 'POST';
            newForm.action = this.action;
            
            // Token CSRF
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            newForm.appendChild(csrfInput);
            
            // Ajouter les données
            for (let [key, value] of formData.entries()) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = value;
                newForm.appendChild(input);
            }
            
            document.body.appendChild(newForm);
            newForm.submit();
        });
    </script>
@endsection