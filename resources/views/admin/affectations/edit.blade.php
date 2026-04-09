@extends('layouts.app')

@section('title', 'Modifier une affectation')

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
                <h1 class="text-3xl font-bold text-iris-black-900">Modifier l'affectation</h1>
                <p class="text-gray-600 mt-1">
                    {{ $etudiant->nomComplet() }} - {{ $classe->nom }}
                </p>
            </div>
        </div>

        <!-- Formulaire -->
        <form method="POST" action="{{ route('admin.affectations.update', [$classe, $etudiant]) }}" class="bg-white rounded-2xl shadow-sm p-8 space-y-6">
            @csrf
            @method('PUT')

            <!-- Informations étudiant (lecture seule) -->
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-2">Étudiant</p>
                <p class="text-lg font-medium text-iris-black-900">{{ $etudiant->nomComplet() }}</p>
                <p class="text-sm text-gray-600">{{ $etudiant->matricule }} - {{ $etudiant->email }}</p>
            </div>

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
                                {{ old('regime', $affectation->regime) === 'initial' ? 'checked' : '' }}
                                class="h-4 w-4 text-iris-blue focus:ring-iris-blue"
                                required
                            >
                            <div class="ml-3">
                                <span class="block text-sm font-medium text-gray-900">Formation initiale</span>
                            </div>
                        </label>
                    @endif

                    @if($classe->accepte_alternance)
                        <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors border-gray-300 has-[:checked]:border-iris-yellow has-[:checked]:bg-iris-yellow has-[:checked]:bg-opacity-10">
                            <input 
                                type="radio" 
                                name="regime" 
                                value="alternance" 
                                {{ old('regime', $affectation->regime) === 'alternance' ? 'checked' : '' }}
                                class="h-4 w-4 text-iris-yellow focus:ring-iris-yellow"
                                id="regime_alternance"
                            >
                            <div class="ml-3">
                                <span class="block text-sm font-medium text-gray-900">Alternance</span>
                            </div>
                        </label>
                    @endif

                    @if($classe->accepte_formation_continue)
                        <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors border-gray-300 has-[:checked]:border-iris-green has-[:checked]:bg-iris-green has-[:checked]:bg-opacity-10">
                            <input 
                                type="radio" 
                                name="regime" 
                                value="formation_continue" 
                                {{ old('regime', $affectation->regime) === 'formation_continue' ? 'checked' : '' }}
                                class="h-4 w-4 text-iris-green focus:ring-iris-green"
                            >
                            <div class="ml-3">
                                <span class="block text-sm font-medium text-gray-900">Formation continue</span>
                            </div>
                        </label>
                    @endif
                </div>
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
                    value="{{ old('date_inscription', $affectation->date_inscription) }}"
                    required
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow"
                >
            </div>

            <!-- Statut -->
            <div>
                <label for="statut" class="block text-sm font-medium text-gray-700">
                    Statut <span class="text-red-500">*</span>
                </label>
                <select 
                    name="statut" 
                    id="statut" 
                    required
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow"
                >
                    <option value="inscrit" {{ old('statut', $affectation->statut) === 'inscrit' ? 'selected' : '' }}>Inscrit</option>
                    <option value="en_attente" {{ old('statut', $affectation->statut) === 'en_attente' ? 'selected' : '' }}>En attente</option>
                    <option value="desinscrit" {{ old('statut', $affectation->statut) === 'desinscrit' ? 'selected' : '' }}>Désinscrit</option>
                    <option value="diplome" {{ old('statut', $affectation->statut) === 'diplome' ? 'selected' : '' }}>Diplômé</option>
                    <option value="abandonne" {{ old('statut', $affectation->statut) === 'abandonne' ? 'selected' : '' }}>Abandonné</option>
                </select>
            </div>

            <!-- Informations alternance -->
            <div id="infos_alternance" class="space-y-6 border-t pt-6" style="display: {{ old('regime', $affectation->regime) === 'alternance' ? 'block' : 'none' }};">
                <h3 class="text-lg font-semibold text-iris-black-900">Informations sur l'alternance</h3>

                <div>
                    <label for="entreprise" class="block text-sm font-medium text-gray-700">
                        Nom de l'entreprise <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="entreprise" 
                        id="entreprise" 
                        value="{{ old('entreprise', $affectation->entreprise) }}"
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow"
                    >
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-3">
                        <label for="adresse_entreprise" class="block text-sm font-medium text-gray-700">
                            Adresse de l'entreprise
                        </label>
                        <input 
                            type="text" 
                            name="adresse_entreprise" 
                            id="adresse_entreprise" 
                            value="{{ old('adresse_entreprise', $affectation->adresse_entreprise) }}"
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
                            value="{{ old('ville_entreprise', $affectation->ville_entreprise) }}"
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
                            value="{{ old('code_postal_entreprise', $affectation->code_postal_entreprise) }}"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow"
                        >
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="tuteur_entreprise" class="block text-sm font-medium text-gray-700">
                            Nom du tuteur
                        </label>
                        <input 
                            type="text" 
                            name="tuteur_entreprise" 
                            id="tuteur_entreprise" 
                            value="{{ old('tuteur_entreprise', $affectation->tuteur_entreprise) }}"
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
                            value="{{ old('poste_tuteur', $affectation->poste_tuteur) }}"
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
                            value="{{ old('email_tuteur', $affectation->email_tuteur) }}"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow"
                        >
                    </div>

                    <div>
                        <label for="telephone_tuteur" class="block text-sm font-medium text-gray-700">
                            Téléphone du tuteur
                        </label>
                        <input 
                            type="tel" 
                            name="telephone_tuteur" 
                            id="telephone_tuteur" 
                            value="{{ old('telephone_tuteur', $affectation->telephone_tuteur) }}"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow"
                        >
                    </div>
                </div>

                <div>
                    <label for="rythme_alternance" class="block text-sm font-medium text-gray-700">
                        Rythme d'alternance
                    </label>
                    <input 
                        type="text" 
                        name="rythme_alternance" 
                        id="rythme_alternance" 
                        value="{{ old('rythme_alternance', $affectation->rythme_alternance) }}"
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
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow"
                >{{ old('commentaire', $affectation->commentaire) }}</textarea>
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
                    Enregistrer les modifications
                </button>
            </div>
        </form>
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

            toggleAlternanceFields();
        });
    </script>
@endsection