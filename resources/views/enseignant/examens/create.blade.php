@extends('layouts.app')

@section('title', 'Créer un examen')

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
                    <h1 class="text-3xl font-bold text-iris-black-900">Créer un examen</h1>
                    <p class="text-gray-600 mt-1">Créez un nouveau examen pour vos étudiants</p>
                </div>
            </div>
        </div>

        <!-- Erreurs -->
        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Erreurs de validation</h3>
                        <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Formulaire -->
        <form action="{{ route('enseignant.examens.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="space-y-6">
                <!-- Informations générales -->
                <div class="bg-white rounded-2xl shadow-sm p-8">
                    <h2 class="text-xl font-bold text-iris-black-900 mb-6">Informations générales</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Titre -->
                        <div class="md:col-span-2">
                            <label for="titre" class="block text-sm font-medium text-gray-700 mb-2">
                                Titre de l'examen <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="titre" 
                                   id="titre" 
                                   value="{{ old('titre') }}"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iris-yellow focus:border-transparent">
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Description
                            </label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="3"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iris-yellow focus:border-transparent">{{ old('description') }}</textarea>
                        </div>

                        <!-- Matière -->
                        <div>
                            <label for="matiere_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Matière <span class="text-red-500">*</span>
                            </label>
                            <select name="matiere_id" 
                                    id="matiere_id" 
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iris-yellow focus:border-transparent">
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
                                    <option value="{{ $matiere->id }}" {{ old('matiere_id') == $matiere->id ? 'selected' : '' }}>
                                        {{ $matiere->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Classe -->
                        <div>
                            <label for="classe_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Classe <span class="text-red-500">*</span>
                            </label>
                            <select name="classe_id" 
                                    id="classe_id" 
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iris-yellow focus:border-transparent">
                                <option value="">Sélectionnez une classe</option>
                                @php
                                    $classes = DB::table('enseignant_classe')
                                        ->where('enseignant_id', auth()->id())
                                        ->join('classes', 'enseignant_classe.classe_id', '=', 'classes.id')
                                        ->select('classes.*')
                                        ->distinct()
                                        ->orderBy('classes.nom')
                                        ->get();
                                @endphp
                                @foreach($classes as $classe)
                                    <option value="{{ $classe->id }}" {{ old('classe_id') == $classe->id ? 'selected' : '' }}>
                                        {{ $classe->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Type d'examen -->
                        <div>
                            <label for="type_examen" class="block text-sm font-medium text-gray-700 mb-2">
                                Type d'examen <span class="text-red-500">*</span>
                            </label>
                            <select name="type_examen" 
                                    id="type_examen" 
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iris-yellow focus:border-transparent">
                                <option value="en_ligne" {{ old('type_examen') == 'en_ligne' ? 'selected' : '' }}>En ligne</option>
                                <option value="document" {{ old('type_examen') == 'document' ? 'selected' : '' }}>Document PDF</option>
                            </select>
                        </div>

                        <!-- Durée -->
                        <div>
                            <label for="duree_minutes" class="block text-sm font-medium text-gray-700 mb-2">
                                Durée (minutes) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   name="duree_minutes" 
                                   id="duree_minutes" 
                                   value="{{ old('duree_minutes', 60) }}"
                                   min="1"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iris-yellow focus:border-transparent">
                        </div>
                    </div>
                </div>

                <!-- Dates -->
                <div class="bg-white rounded-2xl shadow-sm p-8">
                    <h2 class="text-xl font-bold text-iris-black-900 mb-6">Dates et horaires</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Date début -->
                        <div>
                            <label for="date_debut" class="block text-sm font-medium text-gray-700 mb-2">
                                Date et heure de début <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" 
                                   name="date_debut" 
                                   id="date_debut" 
                                   value="{{ old('date_debut') }}"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iris-yellow focus:border-transparent">
                        </div>

                        
                    </div>
                </div>

                <!-- Questions -->
                <div class="bg-white rounded-2xl shadow-sm p-8">
                    <h2 class="text-xl font-bold text-iris-black-900 mb-6">Questions de l'examen</h2>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Sélectionner des questions depuis votre banque <span class="text-red-500">*</span>
                        </label>
                        
                        <div class="border-2 border-gray-300 rounded-lg p-4 max-h-96 overflow-y-auto">
                            @php
                                $questionsDisponibles = DB::table('banque_questions')
                                    ->where('enseignant_id', auth()->id())
                                    ->where('est_active', true)
                                    ->whereNull('deleted_at')
                                    ->orderBy('created_at', 'desc')
                                    ->get();
                            @endphp

                            @if($questionsDisponibles->count() > 0)
                                <div class="space-y-3">
                                    @foreach($questionsDisponibles as $question)
                                        <label class="flex items-start space-x-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                            <input type="checkbox" 
                                                   name="questions[]" 
                                                   value="{{ $question->id }}"
                                                   class="mt-1 h-5 w-5 text-iris-yellow rounded focus:ring-iris-yellow question-checkbox">
                                            <div class="flex-1">
                                                <p class="font-medium text-gray-900">{{ $question->enonce }}</p>
                                                <div class="flex items-center space-x-4 mt-2 text-xs text-gray-600">
                                                    <span class="px-2 py-1 bg-gray-100 rounded">{{ ucfirst($question->type) }}</span>
                                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded">{{ ucfirst($question->difficulte) }}</span>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <input type="number" 
                                                       name="points[{{ $question->id }}]" 
                                                       value="5"
                                                       min="0.5"
                                                       step="0.5"
                                                       class="w-20 px-2 py-1 border border-gray-300 rounded text-sm points-input"
                                                       placeholder="Points">
                                                <p class="text-xs text-gray-500 mt-1">Points</p>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <p class="text-gray-600">Aucune question disponible dans votre banque</p>
                                    <a href="{{ route('enseignant.questions.create') }}" 
                                       class="mt-3 inline-block text-iris-blue hover:underline">
                                        Créer des questions →
                                    </a>
                                </div>
                            @endif
                        </div>

                        <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                            <p class="text-sm text-blue-900">
                                <strong id="questions-count">0</strong> question(s) sélectionnée(s) • 
                                Note totale : <strong id="total-points">0</strong> points
                            </p>
                        </div>
                    </div>

                    @error('questions')
                        <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Instructions -->
                <div class="bg-white rounded-2xl shadow-sm p-8">
                    <h2 class="text-xl font-bold text-iris-black-900 mb-6">Instructions</h2>

                    <div>
                        <label for="instructions" class="block text-sm font-medium text-gray-700 mb-2">
                            Instructions pour les étudiants
                        </label>
                        <textarea name="instructions" 
                                  id="instructions" 
                                  rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iris-yellow focus:border-transparent">{{ old('instructions') }}</textarea>
                    </div>
                </div>

                <!-- Paramètres -->
                <div class="bg-white rounded-2xl shadow-sm p-8">
                    <h2 class="text-xl font-bold text-iris-black-900 mb-6">Paramètres</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Seuil de réussite -->
                        <div>
                            <label for="seuil_reussite" class="block text-sm font-medium text-gray-700 mb-2">
                                Seuil de réussite (%) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   name="seuil_reussite" 
                                   id="seuil_reussite" 
                                   value="{{ old('seuil_reussite', 50) }}"
                                   min="0"
                                   max="100"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iris-yellow focus:border-transparent">
                        </div>

                        <!-- Nombre de tentatives -->
                        <div>
                            <label for="nombre_tentatives_max" class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre de tentatives autorisées <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   name="nombre_tentatives_max" 
                                   id="nombre_tentatives_max" 
                                   value="{{ old('nombre_tentatives_max', 1) }}"
                                   min="1"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iris-yellow focus:border-transparent">
                        </div>
                    </div>

                    <div class="mt-6 space-y-3">
                        <!-- Mélanger questions -->
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" 
                                   name="melanger_questions" 
                                   value="1"
                                   {{ old('melanger_questions') ? 'checked' : '' }}
                                   class="h-5 w-5 text-iris-yellow rounded focus:ring-iris-yellow">
                            <span class="text-gray-700">Mélanger l'ordre des questions</span>
                        </label>

                        <!-- Mélanger réponses -->
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" 
                                   name="melanger_reponses" 
                                   value="1"
                                   {{ old('melanger_reponses') ? 'checked' : '' }}
                                   class="h-5 w-5 text-iris-yellow rounded focus:ring-iris-yellow">
                            <span class="text-gray-700">Mélanger l'ordre des réponses</span>
                        </label>

                        <!-- Afficher résultats -->
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" 
                                   name="afficher_resultats_immediatement" 
                                   value="1"
                                   {{ old('afficher_resultats_immediatement') ? 'checked' : '' }}
                                   class="h-5 w-5 text-iris-yellow rounded focus:ring-iris-yellow">
                            <span class="text-gray-700">Afficher les résultats immédiatement après soumission</span>
                        </label>
                    </div>
                </div>

                <!-- Statut -->
                <div class="bg-white rounded-2xl shadow-sm p-8">
                    <h2 class="text-xl font-bold text-iris-black-900 mb-6">Statut de publication</h2>

                    <div>
                        <label for="statut" class="block text-sm font-medium text-gray-700 mb-2">
                            Statut <span class="text-red-500">*</span>
                        </label>
                        <select name="statut" 
                                id="statut" 
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iris-yellow focus:border-transparent">
                            <option value="brouillon" {{ old('statut') == 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                            <option value="publie" {{ old('statut') == 'publie' ? 'selected' : '' }}>Publié</option>
                        </select>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="bg-white rounded-2xl shadow-sm p-8">
                    <div class="flex items-center justify-between">
                        <a href="{{ route('enseignant.examens.index') }}" 
                           class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition-all">
                            Annuler
                        </a>

                        <button type="submit" 
                                class="px-8 py-3 bg-iris-yellow text-iris-black-900 rounded-lg font-bold hover:bg-iris-yellow-600 transition-all shadow-lg">
                            📝 Créer l'examen
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.question-checkbox');
            const countElement = document.getElementById('questions-count');
            const pointsElement = document.getElementById('total-points');

            function updateCount() {
                let count = 0;
                let totalPoints = 0;

                checkboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        count++;
                        const questionId = checkbox.value;
                        const pointsInput = document.querySelector(`input[name="points[${questionId}]"]`);
                        totalPoints += parseFloat(pointsInput.value) || 0;
                    }
                });

                countElement.textContent = count;
                pointsElement.textContent = totalPoints.toFixed(1);
            }

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateCount);
                
                const questionId = checkbox.value;
                const pointsInput = document.querySelector(`input[name="points[${questionId}]"]`);
                if (pointsInput) {
                    pointsInput.addEventListener('input', updateCount);
                }
            });
        });
    </script>
@endsection