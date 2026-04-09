@extends('layouts.app')

@section('title', 'Affecter un étudiant')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- En-tête -->
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.classes.show', $classe) }}" 
               class="text-gray-600 hover:text-iris-yellow">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-iris-black-900">Affecter un étudiant</h1>
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
                    <p class="text-sm opacity-80">Régimes acceptés</p>
                    <div class="flex flex-wrap gap-1 mt-1">
                        @if($classe->accepte_initial)
                            <span class="px-2 py-1 text-xs font-semibold bg-white bg-opacity-30 rounded-full">Initial</span>
                        @endif
                        @if($classe->accepte_alternance)
                            <span class="px-2 py-1 text-xs font-semibold bg-white bg-opacity-30 rounded-full">Alternance</span>
                        @endif
                        @if($classe->accepte_formation_continue)
                            <span class="px-2 py-1 text-xs font-semibold bg-white bg-opacity-30 rounded-full">Formation continue</span>
                        @endif
                    </div>
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
            <form method="POST" action="{{ route('admin.affectations.store', $classe) }}" class="bg-white rounded-2xl shadow-sm p-8 space-y-6">
                @csrf

                <!-- Sélection étudiant -->
                <div>
                    <label for="etudiant_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Sélectionner un étudiant <span class="text-red-500">*</span>
                    </label>
                    <select 
                        name="etudiant_id" 
                        id="etudiant_id" 
                        required
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow @error('etudiant_id') border-red-500 @enderror"
                    >
                        <option value="">-- Choisir un étudiant --</option>
                        @foreach($etudiantsDisponibles as $etudiant)
                            <option value="{{ $etudiant->id }}" {{ old('etudiant_id') == $etudiant->id ? 'selected' : '' }}>
                                {{ $etudiant->nomComplet() }} - {{ $etudiant->matricule }}
                            </option>
                        @endforeach
                    </select>
                    @error('etudiant_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">{{ $etudiantsDisponibles->count() }} étudiant(s) disponible(s)</p>
                </div>

                <!-- Régime -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Régime de formation <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @if($classe->accepte_initial)
                            <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors @error('regime') border-red-500 @else border-gray-300 @enderror has-[:checked]:border-iris-blue has-[:checked]:bg-iris-blue has-[:checked]:bg-opacity-10">
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
                                    id="regime_alternance"
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

                <!-- Informations alternance (affichées conditionnellement) -->
                <div id="infos_alternance" class="space-y-6 border-t pt-6" style="display: none;">
                    <h3 class="text-lg font-semibold text-iris-black-900">Informations sur l'alternance</h3>

                    <!-- Entreprise -->
                    <div>
                        <label for="entreprise" class="block text-sm font-medium text-gray-700">
                            Nom de l'entreprise <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="entreprise" 
                            id="entreprise" 
                            value="{{ old('entreprise') }}"
                            placeholder="Ex: Orange, Capgemini..."
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow @error('entreprise') border-red-500 @enderror"
                        >
                        @error('entreprise')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Adresse entreprise -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-3">
                            <label for="adresse_entreprise" class="block text-sm font-medium text-gray-700">
                                Adresse de l'entreprise
                            </label>
                            <input 
                                type="text" 
                                name="adresse_entreprise" 
                                id="adresse_entreprise" 
                                value="{{ old('adresse_entreprise') }}"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow"
                            >
                        </div>

                        <div class="md:col-span-2">
                            <label for="ville_entreprise" class="block text-sm font-medium text-gray-700">
                                Ville
                            </label>
                            <input 
                                type="text" 
                                name="ville_entreprise" 
                                id="ville_entreprise" 
                                value="{{ old('ville_entreprise') }}"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow"
                            >
                        </div>

                        <div>
                            <label for="code_postal_entreprise" class="block text-sm font-medium text-gray-700">
                                Code postal
                            </label>
                            <input 
                                type="text" 
                                name="code_postal_entreprise" 
                                id="code_postal_entreprise" 
                                value="{{ old('code_postal_entreprise') }}"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow"
                            >
                        </div>
                    </div>

                    <!-- Tuteur -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="tuteur_entreprise" class="block text-sm font-medium text-gray-700">
                                Nom du tuteur
                            </label>
                            <input 
                                type="text" 
                                name="tuteur_entreprise" 
                                id="tuteur_entreprise" 
                                value="{{ old('tuteur_entreprise') }}"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow"
                            >
                        </div>

                        <div>
                            <label for="poste_tuteur" class="block text-sm font-medium text-gray-700">
                                Poste du tuteur
                            </label>
                            <input 
                                type="text" 
                                name="poste_tuteur" 
                                id="poste_tuteur" 
                                value="{{ old('poste_tuteur') }}"
                                placeholder="Ex: Responsable développement"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow"
                            >
                        </div>

                        <div>
                            <label for="email_tuteur" class="block text-sm font-medium text-gray-700">
                                Email du tuteur
                            </label>
                            <input 
                                type="email" 
                                name="email_tuteur" 
                                id="email_tuteur" 
                                value="{{ old('email_tuteur') }}"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow @error('email_tuteur') border-red-500 @enderror"
                            >
                            @error('email_tuteur')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="telephone_tuteur" class="block text-sm font-medium text-gray-700">
                                Téléphone du tuteur
                            </label>
                            <input 
                                type="tel" 
                                name="telephone_tuteur" 
                                id="telephone_tuteur" 
                                value="{{ old('telephone_tuteur') }}"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow"
                            >
                        </div>
                    </div>

                    <!-- Rythme alternance -->
                    <div>
                        <label for="rythme_alternance" class="block text-sm font-medium text-gray-700">
                            Rythme d'alternance
                        </label>
                        <input 
                            type="text" 
                            name="rythme_alternance" 
                            id="rythme_alternance" 
                            value="{{ old('rythme_alternance') }}"
                            placeholder="Ex: 3 semaines / 1 semaine, 2 jours / 3 jours"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow"
                        >
                    </div>
                </div>

                <!-- Commentaire -->
                <div class="border-t pt-6">
                    <label for="commentaire" class="block text-sm font-medium text-gray-700">
                        Commentaire (optionnel)
                    </label>
                    <textarea 
                        name="commentaire" 
                        id="commentaire" 
                        rows="3"
                        placeholder="Remarques particulières..."
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow"
                    >{{ old('commentaire') }}</textarea>
                </div>

                <!-- Boutons -->
                <div class="flex justify-end space-x-4 pt-6 border-t">
                    <a href="{{ route('admin.classes.show', $classe) }}" 
                       class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition-all">
                        Annuler
                    </a>
                    <button 
                        type="submit"
                        class="px-6 py-3 bg-iris-yellow text-iris-black-900 rounded-lg font-semibold hover:bg-iris-yellow-600 transition-all shadow-sm">
                        Affecter l'étudiant
                    </button>
                </div>
            </form>
        @endif
    </div>

    <!-- Script pour afficher/masquer les infos alternance -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const regimeRadios = document.querySelectorAll('input[name="regime"]');
            const infosAlternance = document.getElementById('infos_alternance');
            const entrepriseInput = document.getElementById('entreprise');

            function toggleAlternanceFields() {
                const regimeAlternance = document.getElementById('regime_alternance');
                if (regimeAlternance && regimeAlternance.checked) {
                    infosAlternance.style.display = 'block';
                    entrepriseInput.required = true;
                } else {
                    infosAlternance.style.display = 'none';
                    entrepriseInput.required = false;
                }
            }

            regimeRadios.forEach(radio => {
                radio.addEventListener('change', toggleAlternanceFields);
            });

            // Vérifier au chargement de la page
            toggleAlternanceFields();
        });
    </script>
@endsection