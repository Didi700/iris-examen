@extends('layouts.app')

@section('title', 'Créer une question')

@section('content')
    <div class="space-y-6">
        <!-- En-tête -->
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('enseignant.questions.index') }}" 
                   class="text-gray-600 hover:text-iris-yellow transition-colors">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-iris-black-900">Créer une question</h1>
                    <p class="text-gray-600 mt-1">Ajoutez une nouvelle question à votre banque</p>
                </div>
            </div>
        </div>

        <!-- Afficher les erreurs -->
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
        <form action="{{ route('enseignant.questions.store') }}" method="POST" id="question-form">
            @csrf

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
                                    <option value="{{ $matiere->id }}" {{ old('matiere_id') == $matiere->id ? 'selected' : '' }}>
                                        {{ $matiere->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('matiere_id')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Type -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                                Type de question <span class="text-red-500">*</span>
                            </label>
                            <select name="type" 
                                    id="type" 
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iris-yellow focus:border-transparent @error('type') border-red-500 @enderror">
                                <option value="">Sélectionnez un type</option>
                                <option value="choix_unique" {{ old('type') === 'choix_unique' ? 'selected' : '' }}>Choix unique (QCM)</option>
                                <option value="choix_multiple" {{ old('type') === 'choix_multiple' ? 'selected' : '' }}>Choix multiple</option>
                                <option value="vrai_faux" {{ old('type') === 'vrai_faux' ? 'selected' : '' }}>Vrai/Faux</option>
                                <option value="reponse_courte" {{ old('type') === 'reponse_courte' ? 'selected' : '' }}>Réponse courte</option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
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
                                <option value="facile" {{ old('difficulte') === 'facile' ? 'selected' : '' }}>Facile</option>
                                <option value="moyen" {{ old('difficulte', 'moyen') === 'moyen' ? 'selected' : '' }}>Moyen</option>
                                <option value="difficile" {{ old('difficulte') === 'difficile' ? 'selected' : '' }}>Difficile</option>
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
                                  placeholder="Saisissez l'énoncé de votre question...">{{ old('enonce') }}</textarea>
                        @error('enonce')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Réponses -->
                <div class="bg-white rounded-2xl shadow-sm p-8" id="reponses-section" style="display: none;">
                    <h2 class="text-xl font-bold text-iris-black-900 mb-4">
                        Réponses 
                        <span class="text-sm font-normal text-gray-600">(Cochez la ou les bonnes réponses)</span>
                    </h2>

                    <div id="reponses-container" class="space-y-3 mb-4">
                        <!-- Les réponses seront ajoutées ici dynamiquement -->
                    </div>

                    <button type="button" 
                            id="add-reponse-btn"
                            class="px-4 py-2 bg-iris-blue text-white rounded-lg font-semibold hover:bg-blue-700 transition-all">
                        <svg class="inline h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Ajouter une réponse
                    </button>
                </div>

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
                                  placeholder="Explication qui sera affichée aux étudiants après correction...">{{ old('explication') }}</textarea>
                        @error('explication')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="bg-white rounded-2xl shadow-sm p-8">
                    <div class="flex items-center justify-between">
                        <a href="{{ route('enseignant.questions.index') }}" 
                           class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition-all">
                            Annuler
                        </a>

                        <button type="submit" 
                                class="px-8 py-3 bg-iris-yellow text-iris-black-900 rounded-lg font-bold hover:bg-iris-yellow-600 transition-all shadow-lg">
                            <svg class="inline h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Créer la question
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        console.log('🚀 Script de création de question chargé');
        
        let reponseCounter = 0;
        const typeSelect = document.getElementById('type');
        const reponsesSection = document.getElementById('reponses-section');
        const reponsesContainer = document.getElementById('reponses-container');
        const addReponseBtn = document.getElementById('add-reponse-btn');

        // ============================================
        // GESTION DU CHANGEMENT DE TYPE
        // ============================================
        typeSelect.addEventListener('change', function() {
            const type = this.value;
            console.log('📝 Type sélectionné:', type);
            
            if (!type || type === 'reponse_courte') {
                reponsesSection.style.display = 'none';
                console.log('✅ Réponses cachées (réponse courte ou aucun type)');
                return;
            }
            
            // Afficher la section des réponses
            reponsesSection.style.display = 'block';
            reponsesContainer.innerHTML = '';
            reponseCounter = 0;
            
            console.log('✅ Section réponses affichée');
            
            // Ajouter les réponses par défaut
            if (type === 'vrai_faux') {
                console.log('➕ Ajout de 2 réponses Vrai/Faux');
                ajouterReponse('Vrai', true);
                ajouterReponse('Faux', false);
                addReponseBtn.style.display = 'none';
            } else {
                console.log('➕ Ajout de 4 réponses par défaut');
                ajouterReponse('', false);
                ajouterReponse('', false);
                ajouterReponse('', false);
                ajouterReponse('', false);
                addReponseBtn.style.display = 'inline-flex';
            }
        });

        // ============================================
        // AJOUTER UNE RÉPONSE
        // ============================================
        addReponseBtn.addEventListener('click', function() {
            console.log('➕ Ajout manuel d\'une réponse');
            ajouterReponse('', false);
        });

        function ajouterReponse(texteParDefaut = '', estCorrecteParDefaut = false) {
            const type = typeSelect.value;
            const index = reponseCounter;
            
            const div = document.createElement('div');
            div.className = 'flex items-start space-x-3 p-4 bg-gray-50 border-2 border-gray-200 rounded-lg reponse-item hover:border-iris-yellow transition-all';
            div.dataset.index = index;
            
            // Pour choix_multiple : checkbox avec name unique
            // Pour choix_unique et vrai_faux : radio avec name commun
            let inputHtml;
            if (type === 'choix_multiple') {
                inputHtml = `
                    <input type="checkbox" 
                           name="reponses[${index}][est_correcte]" 
                           value="1"
                           ${estCorrecteParDefaut ? 'checked' : ''}
                           class="mt-3 h-5 w-5 text-iris-yellow rounded focus:ring-iris-yellow checkbox-correcte">
                `;
            } else {
                inputHtml = `
                    <input type="radio" 
                           name="reponse_correcte_radio" 
                           value="${index}"
                           ${estCorrecteParDefaut ? 'checked' : ''}
                           class="mt-3 h-5 w-5 text-iris-yellow focus:ring-iris-yellow radio-correcte">
                `;
            }
            
            div.innerHTML = `
                ${inputHtml}
                <div class="flex-1">
                    <input type="text" 
                           name="reponses[${index}][texte]" 
                           value="${texteParDefaut}"
                           placeholder="Texte de la réponse ${index + 1}"
                           required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iris-yellow focus:border-transparent">
                </div>
                ${type !== 'vrai_faux' ? `
                <button type="button" 
                        class="btn-supprimer px-3 py-3 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-all">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>` : ''}
            `;
            
            // Ajouter l'événement de suppression
            const btnSupprimer = div.querySelector('.btn-supprimer');
            if (btnSupprimer) {
                btnSupprimer.addEventListener('click', function() {
                    supprimerReponse(div);
                });
            }
            
            reponsesContainer.appendChild(div);
            reponseCounter++;
            
            console.log(`✅ Réponse ${index} ajoutée`);
        }

        // ============================================
        // SUPPRIMER UNE RÉPONSE
        // ============================================
        function supprimerReponse(element) {
            const items = document.querySelectorAll('.reponse-item');
            if (items.length <= 2) {
                alert('⚠️ Vous devez conserver au moins 2 réponses.');
                return;
            }
            element.remove();
            console.log('🗑️ Réponse supprimée');
        }

        // ============================================
        // VALIDATION DU FORMULAIRE
        // ============================================
        document.getElementById('question-form').addEventListener('submit', function(e) {
            const type = typeSelect.value;
            
            console.log('📤 Tentative de soumission du formulaire');
            console.log('Type:', type);
            
            if (!type) {
                e.preventDefault();
                alert('⚠️ Veuillez sélectionner un type de question.');
                return false;
            }
            
            // Pas de validation pour réponse courte
            if (type === 'reponse_courte') {
                console.log('✅ Réponse courte - validation OK');
                return true;
            }
            
            // Vérifier qu'il y a au moins 2 réponses
            const items = document.querySelectorAll('.reponse-item');
            console.log('Nombre de réponses:', items.length);
            
            if (items.length < 2) {
                e.preventDefault();
                alert('⚠️ Vous devez avoir au moins 2 réponses.');
                return false;
            }
            
            // Validation spécifique selon le type
            if (type === 'choix_multiple') {
                // Pour choix multiple : au moins une checkbox cochée
                const checkboxes = document.querySelectorAll('.checkbox-correcte:checked');
                console.log('Checkboxes cochées:', checkboxes.length);
                
                if (checkboxes.length === 0) {
                    e.preventDefault();
                    alert('⚠️ Vous devez cocher au moins une réponse comme correcte.');
                    return false;
                }
            } else {
                // Pour choix unique et vrai/faux : un radio doit être coché
                const radioChecked = document.querySelector('.radio-correcte:checked');
                console.log('Radio coché:', radioChecked ? 'Oui' : 'Non');
                
                if (!radioChecked) {
                    e.preventDefault();
                    alert('⚠️ Vous devez sélectionner une réponse correcte.');
                    return false;
                }
                
                // Ajouter un champ caché pour indiquer quelle réponse est correcte
                const selectedIndex = radioChecked.value;
                console.log('Index de la réponse correcte:', selectedIndex);
                
                // Supprimer les anciens champs cachés (si formulaire re-soumis)
                const oldHiddens = document.querySelectorAll('input[name*="[est_correcte]"][type="hidden"]');
                oldHiddens.forEach(h => h.remove());
                
                // Ajouter le nouveau champ caché
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = `reponses[${selectedIndex}][est_correcte]`;
                hiddenInput.value = '1';
                this.appendChild(hiddenInput);
                
                console.log('✅ Champ caché ajouté pour la réponse correcte');
            }
            
            console.log('✅ Validation OK - Soumission du formulaire');
            return true;
        });

        // ============================================
        // INITIALISATION SI OLD() PRÉSENT
        // ============================================
        @if(old('type'))
            console.log('🔄 Restauration du type depuis old()');
            typeSelect.dispatchEvent(new Event('change'));
        @endif
    </script>
    @endpush
@endsection