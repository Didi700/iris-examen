@extends('layouts.app')

@section('title', 'Modifier la question')

@section('content')
    <div class="space-y-6">
        <!-- En-tête -->
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('enseignant.questions.show', $question->id) }}" 
                   class="text-gray-600 hover:text-iris-yellow transition-colors">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-iris-black-900">Modifier la question</h1>
                    <p class="text-gray-600 mt-1">{{ $question->matiere->nom }}</p>
                </div>
            </div>
        </div>

        <!-- Formulaire -->
        <form action="{{ route('enseignant.questions.update', $question->id) }}" method="POST" id="question-form">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Informations de base -->
                <div class="bg-white rounded-2xl shadow-sm p-8">
                    <h2 class="text-xl font-bold text-iris-black-900 mb-6">Informations de base</h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Matière -->
                        <div>
                            <label for="matiere_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Matière <span class="text-red-500">*</span>
                            </label>
                            <select name="matiere_id" 
                                    id="matiere_id" 
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iris-yellow focus:border-transparent @error('matiere_id') border-red-500 @enderror">
                                <option value="">Sélectionnez une matière</option>
                                @php
                                    $matieres = DB::table('enseignant_classe')
                                        ->where('enseignant_id', auth()->id())
                                        ->join('matieres', 'enseignant_classe.matiere_id', '=', 'matieres.id')
                                        ->select('matieres.*')
                                        ->distinct()
                                        ->orderBy('matieres.nom')
                                        ->get();
                                @endphp
                                @foreach($matieres as $matiere)
                                    <option value="{{ $matiere->id }}" {{ old('matiere_id', $question->matiere_id) == $matiere->id ? 'selected' : '' }}>
                                        {{ $matiere->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('matiere_id')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Type (lecture seule) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Type de question</label>
                            <div class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-700">
                                {{ ucfirst(str_replace('_', ' ', $question->type)) }}
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Le type ne peut pas être modifié</p>
                        </div>

                        <!-- Difficulté -->
                        <div>
                            <label for="difficulte" class="block text-sm font-medium text-gray-700 mb-2">
                                Difficulté <span class="text-red-500">*</span>
                            </label>
                            <select name="difficulte" 
                                    id="difficulte" 
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iris-yellow focus:border-transparent @error('difficulte') border-red-500 @enderror">
                                <option value="">Sélectionnez</option>
                                <option value="facile" {{ old('difficulte', $question->difficulte) === 'facile' ? 'selected' : '' }}>Facile</option>
                                <option value="moyen" {{ old('difficulte', $question->difficulte) === 'moyen' ? 'selected' : '' }}>Moyen</option>
                                <option value="difficile" {{ old('difficulte', $question->difficulte) === 'difficile' ? 'selected' : '' }}>Difficile</option>
                            </select>
                            @error('difficulte')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Énoncé -->
                <div class="bg-white rounded-2xl shadow-sm p-8">
                    <h2 class="text-xl font-bold text-iris-black-900 mb-6">Énoncé</h2>

                    <div>
                        <label for="enonce" class="block text-sm font-medium text-gray-700 mb-2">
                            Énoncé de la question <span class="text-red-500">*</span>
                        </label>
                        <textarea name="enonce" 
                                  id="enonce" 
                                  rows="4"
                                  required
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iris-yellow focus:border-transparent @error('enonce') border-red-500 @enderror"
                                  placeholder="Saisissez l'énoncé de votre question...">{{ old('enonce', $question->enonce) }}</textarea>
                        @error('enonce')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Réponses -->
                @if($question->type !== 'reponse_courte')
                    <div class="bg-white rounded-2xl shadow-sm p-8" id="reponses-section">
                        <h2 class="text-xl font-bold text-iris-black-900 mb-6">Réponses</h2>

                        <div id="reponses-container" class="space-y-4">
                            @foreach($question->reponses as $index => $reponse)
                                <div class="flex items-start space-x-3 p-4 border border-gray-200 rounded-lg reponse-item">
                                    <input type="{{ $question->type === 'choix_multiple' ? 'checkbox' : 'radio' }}" 
                                           name="reponses[{{ $index }}][est_correcte]" 
                                           {{ $reponse->est_correcte ? 'checked' : '' }}
                                           class="mt-3 h-5 w-5 text-iris-yellow focus:ring-iris-yellow">
                                    <div class="flex-1">
                                        <input type="hidden" name="reponses[{{ $index }}][id]" value="{{ $reponse->id }}">
                                        <input type="text" 
                                               name="reponses[{{ $index }}][texte]" 
                                               value="{{ old('reponses.'.$index.'.texte', $reponse->texte) }}"
                                               placeholder="Texte de la réponse"
                                               required
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iris-yellow focus:border-transparent">
                                    </div>
                                    @if($question->type !== 'vrai_faux')
                                        <button type="button" 
                                                onclick="removeReponse(this)"
                                                class="px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-all">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        @if($question->type !== 'vrai_faux')
                            <button type="button" 
                                    id="add-reponse-btn"
                                    class="mt-4 px-4 py-2 bg-iris-blue text-white rounded-lg font-semibold hover:bg-blue-700 transition-all">
                                <svg class="inline h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Ajouter une réponse
                            </button>
                        @endif
                    </div>
                @endif

                <!-- Explication -->
                <div class="bg-white rounded-2xl shadow-sm p-8">
                    <h2 class="text-xl font-bold text-iris-black-900 mb-6">Explication (optionnelle)</h2>

                    <div>
                        <label for="explication" class="block text-sm font-medium text-gray-700 mb-2">
                            Explication de la réponse
                        </label>
                        <textarea name="explication" 
                                  id="explication" 
                                  rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iris-yellow focus:border-transparent @error('explication') border-red-500 @enderror"
                                  placeholder="Explication qui sera affichée aux étudiants après correction...">{{ old('explication', $question->explication) }}</textarea>
                        @error('explication')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="bg-white rounded-2xl shadow-sm p-8">
                    <div class="flex items-center justify-between">
                        <a href="{{ route('enseignant.questions.show', $question->id) }}" 
                           class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition-all">
                            Annuler
                        </a>

                        <button type="submit" 
                                class="px-8 py-3 bg-iris-yellow text-iris-black-900 rounded-lg font-bold hover:bg-iris-yellow-600 transition-all shadow-lg">
                            <svg class="inline h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Enregistrer les modifications
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        let reponseCounter = {{ $question->reponses->count() }};
        const reponsesContainer = document.getElementById('reponses-container');
        const addReponseBtn = document.getElementById('add-reponse-btn');
        const questionType = "{{ $question->type }}";

        // Ajouter une réponse
        if (addReponseBtn) {
            addReponseBtn.addEventListener('click', function() {
                addReponse();
            });
        }

        function addReponse() {
            const inputType = questionType === 'choix_multiple' ? 'checkbox' : 'radio';
            
            const reponseDiv = document.createElement('div');
            reponseDiv.className = 'flex items-start space-x-3 p-4 border border-gray-200 rounded-lg reponse-item';
            reponseDiv.innerHTML = `
                <input type="${inputType}" 
                       name="reponses[${reponseCounter}][est_correcte]" 
                       class="mt-3 h-5 w-5 text-iris-yellow focus:ring-iris-yellow">
                <div class="flex-1">
                    <input type="text" 
                           name="reponses[${reponseCounter}][texte]" 
                           placeholder="Texte de la réponse"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iris-yellow focus:border-transparent">
                </div>
                <button type="button" 
                        onclick="removeReponse(this)"
                        class="px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-all">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            `;
            
            reponsesContainer.appendChild(reponseDiv);
            reponseCounter++;
        }

        function removeReponse(button) {
            const reponsesCount = document.querySelectorAll('.reponse-item').length;
            if (reponsesCount <= 2) {
                alert('Vous devez conserver au moins 2 réponses.');
                return;
            }
            button.closest('.reponse-item').remove();
        }

        // Validation du formulaire
        document.getElementById('question-form').addEventListener('submit', function(e) {
            if (questionType !== 'reponse_courte') {
                const reponses = document.querySelectorAll('.reponse-item');
                
                if (reponses.length < 2) {
                    e.preventDefault();
                    alert('Vous devez avoir au moins 2 réponses.');
                    return false;
                }

                const checkedInputs = document.querySelectorAll('input[name*="[est_correcte]"]:checked');
                
                if (checkedInputs.length === 0) {
                    e.preventDefault();
                    alert('Vous devez marquer au moins une réponse comme correcte.');
                    return false;
                }
            }
        });
    </script>
    @endpush
@endsection