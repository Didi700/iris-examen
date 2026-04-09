@extends('layouts.app')

@section('title', 'Affectation en masse')

@section('content')
    <div class="max-w-6xl mx-auto space-y-6">
        <!-- En-tête -->
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.classes.show', $classe) }}" 
               class="text-gray-600 hover:text-iris-yellow">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-iris-black-900">Affectation en masse</h1>
                <p class="text-gray-600 mt-1">Classe : {{ $classe->nom }} - {{ $classe->code }}</p>
            </div>
        </div>

        <!-- Infos classe -->
        <div class="bg-gradient-to-r from-iris-yellow to-yellow-500 rounded-2xl p-6 text-iris-black-900">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <p class="text-sm opacity-80">Effectif actuel</p>
                    <p class="text-2xl font-bold">{{ $classe->effectif_actuel }} / {{ $classe->effectif_max }}</p>
                </div>
                <div>
                    <p class="text-sm opacity-80">Places restantes</p>
                    <p class="text-2xl font-bold">{{ $classe->placesRestantes() }}</p>
                </div>
                <div>
                    <p class="text-sm opacity-80">Étudiants disponibles</p>
                    <p class="text-2xl font-bold">{{ $etudiantsDisponibles->count() }}</p>
                </div>
            </div>
        </div>

        @if($classe->estComplete())
            <div class="bg-red-50 border border-red-200 rounded-2xl p-6">
                <div class="flex items-center">
                    <svg class="h-6 w-6 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <p class="text-red-800 font-medium">Cette classe a atteint son effectif maximum.</p>
                </div>
            </div>
        @elseif($etudiantsDisponibles->isEmpty())
            <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6">
                <div class="flex items-center">
                    <svg class="h-6 w-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-blue-800 font-medium">Tous les étudiants sont déjà affectés à cette classe.</p>
                </div>
            </div>
        @else
            <!-- Formulaire -->
            <form method="POST" action="{{ route('admin.affectations.masse.store', $classe) }}" class="space-y-6">
                @csrf

                <!-- Configuration globale -->
                <div class="bg-white rounded-2xl shadow-sm p-8 space-y-6">
                    <h3 class="text-lg font-semibold text-iris-black-900">Configuration pour tous les étudiants</h3>

                    <!-- Régime -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Régime de formation <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @if($classe->accepte_initial)
                                <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors border-gray-300 has-[:checked]:border-iris-blue has-[:checked]:bg-iris-blue has-[:checked]:bg-opacity-10">
                                    <input 
                                        type="radio" 
                                        name="regime" 
                                        value="initial" 
                                        {{ old('regime') === 'initial' ? 'checked' : '' }}
                                        class="h-4 w-4 text-iris-blue focus:ring-iris-blue"
                                        required
                                    >
                                    <div class="ml-3">
                                        <span class="block text-sm font-medium text-gray-900">Formation initiale</span>
                                        <span class="block text-xs text-gray-500">Sans alternance</span>
                                    </div>
                                </label>
                            @endif

                            @if($classe->accepte_alternance)
                                <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors border-gray-300 has-[:checked]:border-iris-yellow has-[:checked]:bg-iris-yellow has-[:checked]:bg-opacity-10">
                                    <input 
                                        type="radio" 
                                        name="regime" 
                                        value="alternance" 
                                        {{ old('regime') === 'alternance' ? 'checked' : '' }}
                                        class="h-4 w-4 text-iris-yellow focus:ring-iris-yellow"
                                    >
                                    <div class="ml-3">
                                        <span class="block text-sm font-medium text-gray-900">Alternance</span>
                                        <span class="block text-xs text-gray-500">En entreprise</span>
                                    </div>
                                </label>
                            @endif

                            @if($classe->accepte_formation_continue)
                                <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors border-gray-300 has-[:checked]:border-iris-green has-[:checked]:bg-iris-green has-[:checked]:bg-opacity-10">
                                    <input 
                                        type="radio" 
                                        name="regime" 
                                        value="formation_continue" 
                                        {{ old('regime') === 'formation_continue' ? 'checked' : '' }}
                                        class="h-4 w-4 text-iris-green focus:ring-iris-green"
                                    >
                                    <div class="ml-3">
                                        <span class="block text-sm font-medium text-gray-900">Formation continue</span>
                                        <span class="block text-xs text-gray-500">Professionnels</span>
                                    </div>
                                </label>
                            @endif
                        </div>
                        @error('regime')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date d'inscription -->
                    <div>
                        <label for="date_inscription" class="block text-sm font-medium text-gray-700">
                            Date d'inscription <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="date" 
                            name="date_inscription" 
                            id="date_inscription" 
                            value="{{ old('date_inscription', date('Y-m-d')) }}"
                            required
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow @error('date_inscription') border-red-500 @enderror"
                        >
                        @error('date_inscription')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Sélection des étudiants -->
                <div class="bg-white rounded-2xl shadow-sm p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-iris-black-900">
                            Sélectionner les étudiants 
                            <span class="text-sm font-normal text-gray-600">(Maximum : {{ $classe->placesRestantes() }})</span>
                        </h3>
                        <div class="flex space-x-2">
                            <button 
                                type="button" 
                                onclick="selectAll()" 
                                class="px-4 py-2 bg-iris-blue text-white text-sm rounded-lg hover:bg-blue-700 transition-all"
                            >
                                Tout sélectionner
                            </button>
                            <button 
                                type="button" 
                                onclick="deselectAll()" 
                                class="px-4 py-2 bg-gray-200 text-gray-700 text-sm rounded-lg hover:bg-gray-300 transition-all"
                            >
                                Tout désélectionner
                            </button>
                        </div>
                    </div>

                    @error('etudiants')
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        </div>
                    @enderror

                    <!-- Barre de recherche -->
                    <div class="mb-4">
                        <input 
                            type="text" 
                            id="searchEtudiant" 
                            placeholder="Rechercher un étudiant..." 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow"
                            onkeyup="filterEtudiants()"
                        >
                    </div>

                    <!-- Liste des étudiants -->
                    <div class="max-h-96 overflow-y-auto space-y-2" id="etudiantsList">
                        @foreach($etudiantsDisponibles as $etudiant)
                            <label class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer transition-colors etudiant-item" data-nom="{{ strtolower($etudiant->nomComplet()) }}" data-matricule="{{ strtolower($etudiant->matricule) }}" data-email="{{ strtolower($etudiant->email) }}">
                                <input 
                                    type="checkbox" 
                                    name="etudiants[]" 
                                    value="{{ $etudiant->id }}"
                                    {{ in_array($etudiant->id, old('etudiants', [])) ? 'checked' : '' }}
                                    class="h-4 w-4 text-iris-yellow focus:ring-iris-yellow border-gray-300 rounded etudiant-checkbox"
                                >
                                <div class="ml-4 flex-1">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $etudiant->nomComplet() }}</p>
                                            <p class="text-xs text-gray-600">{{ $etudiant->matricule }} - {{ $etudiant->email }}</p>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <!-- Compteur de sélection -->
                    <div class="mt-4 pt-4 border-t">
                        <p class="text-sm text-gray-600">
                            <span id="selectedCount" class="font-semibold text-iris-black-900">0</span> étudiant(s) sélectionné(s) 
                            sur 
                            <span class="font-semibold">{{ $etudiantsDisponibles->count() }}</span> disponible(s)
                            <span class="text-iris-yellow-700">({{ $classe->placesRestantes() }} place(s) restante(s))</span>
                        </p>
                    </div>
                </div>

                <!-- Boutons -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('admin.classes.show', $classe) }}" 
                       class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition-all">
                        Annuler
                    </a>
                    <button 
                        type="submit"
                        class="px-6 py-3 bg-iris-yellow text-iris-black-900 rounded-lg font-semibold hover:bg-iris-yellow-600 transition-all shadow-sm">
                        Affecter les étudiants sélectionnés
                    </button>
                </div>
            </form>
        @endif
    </div>

    <!-- Scripts -->
    <script>
        // Compteur de sélection
        function updateCounter() {
            const checkboxes = document.querySelectorAll('.etudiant-checkbox:checked');
            document.getElementById('selectedCount').textContent = checkboxes.length;
        }

        // Tout sélectionner
        function selectAll() {
            const checkboxes = document.querySelectorAll('.etudiant-checkbox');
            const placesRestantes = {{ $classe->placesRestantes() }};
            let count = 0;
            
            checkboxes.forEach(checkbox => {
                const item = checkbox.closest('.etudiant-item');
                if (item.style.display !== 'none' && count < placesRestantes) {
                    checkbox.checked = true;
                    count++;
                }
            });
            updateCounter();
        }

        // Tout désélectionner
        function deselectAll() {
            const checkboxes = document.querySelectorAll('.etudiant-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            updateCounter();
        }

        // Filtrer les étudiants
        function filterEtudiants() {
            const searchValue = document.getElementById('searchEtudiant').value.toLowerCase();
            const items = document.querySelectorAll('.etudiant-item');
            
            items.forEach(item => {
                const nom = item.getAttribute('data-nom');
                const matricule = item.getAttribute('data-matricule');
                const email = item.getAttribute('data-email');
                
                if (nom.includes(searchValue) || matricule.includes(searchValue) || email.includes(searchValue)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        // Événement de changement sur les checkboxes
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.etudiant-checkbox');
            const placesRestantes = {{ $classe->placesRestantes() }};
            
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const checkedCount = document.querySelectorAll('.etudiant-checkbox:checked').length;
                    
                    // Limite de sélection
                    if (checkedCount > placesRestantes) {
                        this.checked = false;
                        alert(`Vous ne pouvez sélectionner que ${placesRestantes} étudiant(s) maximum.`);
                    }
                    
                    updateCounter();
                });
            });
            
            updateCounter();
        });
    </script>
@endsection