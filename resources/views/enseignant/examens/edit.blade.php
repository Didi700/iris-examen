@extends('layouts.app')

@section('title', 'Modifier - ' . $examen->titre)

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
                    <h1 class="text-3xl font-bold text-iris-black-900">Modifier l'examen</h1>
                    <p class="text-gray-600 mt-1">{{ $examen->titre }}</p>
                </div>
            </div>
        </div>

        <!-- Formulaire -->
        <form action="{{ route('enseignant.examens.update', $examen->id) }}" method="POST">
            @csrf
            @method('PUT')

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
                                   value="{{ old('titre', $examen->titre) }}"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iris-yellow focus:border-transparent @error('titre') border-red-500 @enderror"
                                   placeholder="Ex: Examen final de mathématiques">
                            @error('titre')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

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
                                @foreach($matieres as $matiere)
                                    <option value="{{ $matiere->id }}" {{ old('matiere_id', $examen->matiere_id) == $matiere->id ? 'selected' : '' }}>
                                        {{ $matiere->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('matiere_id')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Classe -->
                        <div>
                            <label for="classe_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Classe <span class="text-red-500">*</span>
                            </label>
                            <select name="classe_id" 
                                    id="classe_id" 
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iris-yellow focus:border-transparent @error('classe_id') border-red-500 @enderror">
                                <option value="">Sélectionnez une classe</option>
                                @foreach($classes as $classe)
                                    <option value="{{ $classe->id }}" {{ old('classe_id', $examen->classe_id) == $classe->id ? 'selected' : '' }}>
                                        {{ $classe->nom }} ({{ $classe->code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('classe_id')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Description
                            </label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="3"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iris-yellow focus:border-transparent @error('description') border-red-500 @enderror"
                                      placeholder="Description de l'examen...">{{ old('description', $examen->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Instructions -->
                        <div class="md:col-span-2">
                            <label for="instructions" class="block text-sm font-medium text-gray-700 mb-2">
                                Instructions pour les étudiants
                            </label>
                            <textarea name="instructions" 
                                      id="instructions" 
                                      rows="4"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iris-yellow focus:border-transparent @error('instructions') border-red-500 @enderror"
                                      placeholder="Instructions importantes pour passer cet examen...">{{ old('instructions', $examen->instructions) }}</textarea>
                            @error('instructions')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Type d'examen (lecture seule) -->
                <div class="bg-white rounded-2xl shadow-sm p-8">
                    <h2 class="text-xl font-bold text-iris-black-900 mb-4">Type d'examen</h2>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600 mb-2">Type d'examen (non modifiable)</p>
                        <p class="font-semibold text-gray-900">
                            @if($examen->type_examen === 'en_ligne')
                                <svg class="inline h-5 w-5 mr-2 text-iris-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Examen en ligne
                            @else
                                <svg class="inline h-5 w-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                Examen PDF
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Configuration -->
                <div class="bg-white rounded-2xl shadow-sm p-8">
                    <h2 class="text-xl font-bold text-iris-black-900 mb-6">Configuration</h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Durée -->
                        <div>
                            <label for="duree_minutes" class="block text-sm font-medium text-gray-700 mb-2">
                                Durée (minutes) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   name="duree_minutes" 
                                   id="duree_minutes" 
                                   value="{{ old('duree_minutes', $examen->duree_minutes) }}"
                                   min="1"
                                   max="600"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iris-yellow focus:border-transparent @error('duree_minutes') border-red-500 @enderror">
                            @error('duree_minutes')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Note totale -->
                        <div>
                            <label for="note_totale" class="block text-sm font-medium text-gray-700 mb-2">
                                Note totale <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   name="note_totale" 
                                   id="note_totale" 
                                   value="{{ old('note_totale', $examen->note_totale) }}"
                                   min="1"
                                   step="0.25"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iris-yellow focus:border-transparent @error('note_totale') border-red-500 @enderror">
                            @error('note_totale')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Seuil de réussite -->
                        <div>
                            <label for="seuil_reussite" class="block text-sm font-medium text-gray-700 mb-2">
                                Seuil de réussite (%) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   name="seuil_reussite" 
                                   id="seuil_reussite" 
                                   value="{{ old('seuil_reussite', $examen->seuil_reussite) }}"
                                   min="0"
                                   max="100"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iris-yellow focus:border-transparent @error('seuil_reussite') border-red-500 @enderror">
                            @error('seuil_reussite')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nombre de tentatives -->
                        <div>
                            <label for="nombre_tentatives_max" class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre de tentatives max <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   name="nombre_tentatives_max" 
                                   id="nombre_tentatives_max" 
                                   value="{{ old('nombre_tentatives_max', $examen->nombre_tentatives_max) }}"
                                   min="1"
                                   max="10"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iris-yellow focus:border-transparent @error('nombre_tentatives_max') border-red-500 @enderror">
                            @error('nombre_tentatives_max')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date de début -->
                        <div>
                            <label for="date_debut" class="block text-sm font-medium text-gray-700 mb-2">
                                Date de début <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" 
                                   name="date_debut" 
                                   id="date_debut" 
                                   value="{{ old('date_debut', $examen->date_debut->format('Y-m-d\TH:i')) }}"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iris-yellow focus:border-transparent @error('date_debut') border-red-500 @enderror">
                            @error('date_debut')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date de fin -->
                        <div>
                            <label for="date_fin" class="block text-sm font-medium text-gray-700 mb-2">
                                Date de fin <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" 
                                   name="date_fin" 
                                   id="date_fin" 
                                   value="{{ old('date_fin', $examen->date_fin->format('Y-m-d\TH:i')) }}"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iris-yellow focus:border-transparent @error('date_fin') border-red-500 @enderror">
                            @error('date_fin')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Options (uniquement pour examen en ligne) -->
                @if($examen->type_examen === 'en_ligne')
                    <div class="bg-white rounded-2xl shadow-sm p-8">
                        <h2 class="text-xl font-bold text-iris-black-900 mb-6">Options de l'examen</h2>

                        <div class="space-y-4">
                            <label class="flex items-start space-x-3 cursor-pointer">
                                <input type="checkbox" 
                                       name="melanger_questions" 
                                       {{ old('melanger_questions', $examen->melanger_questions) ? 'checked' : '' }}
                                       class="mt-1 h-5 w-5 text-iris-yellow rounded focus:ring-iris-yellow">
                                <div>
                                    <p class="font-semibold text-gray-900">Mélanger les questions</p>
                                    <p class="text-sm text-gray-600">L'ordre des questions sera différent pour chaque étudiant</p>
                                </div>
                            </label>

                            <label class="flex items-start space-x-3 cursor-pointer">
                                <input type="checkbox" 
                                       name="melanger_reponses" 
                                       {{ old('melanger_reponses', $examen->melanger_reponses) ? 'checked' : '' }}
                                       class="mt-1 h-5 w-5 text-iris-yellow rounded focus:ring-iris-yellow">
                                <div>
                                    <p class="font-semibold text-gray-900">Mélanger les réponses</p>
                                    <p class="text-sm text-gray-600">L'ordre des réponses sera différent pour chaque étudiant</p>
                                </div>
                            </label>

                            <label class="flex items-start space-x-3 cursor-pointer">
                                <input type="checkbox" 
                                       name="afficher_resultats_immediatement" 
                                       {{ old('afficher_resultats_immediatement', $examen->afficher_resultats_immediatement) ? 'checked' : '' }}
                                       class="mt-1 h-5 w-5 text-iris-yellow rounded focus:ring-iris-yellow">
                                <div>
                                    <p class="font-semibold text-gray-900">Afficher les résultats immédiatement</p>
                                    <p class="text-sm text-gray-600">Les étudiants verront leurs résultats dès la soumission</p>
                                </div>
                            </label>

                            <label class="flex items-start space-x-3 cursor-pointer">
                                <input type="checkbox" 
                                       name="autoriser_retour_arriere" 
                                       {{ old('autoriser_retour_arriere', $examen->autoriser_retour_arriere) ? 'checked' : '' }}
                                       class="mt-1 h-5 w-5 text-iris-yellow rounded focus:ring-iris-yellow">
                                <div>
                                    <p class="font-semibold text-gray-900">Autoriser le retour arrière</p>
                                    <p class="text-sm text-gray-600">Les étudiants peuvent revenir sur les questions précédentes</p>
                                </div>
                            </label>
                        </div>
                    </div>
                @endif

                <!-- Boutons d'action -->
                <div class="bg-white rounded-2xl shadow-sm p-8">
                    <div class="flex items-center justify-between">
                        <a href="{{ route('enseignant.examens.show', $examen->id) }}" 
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
@endsection