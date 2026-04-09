@extends('layouts.app')

@section('title', 'Créer un utilisateur')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- En-tête -->
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.utilisateurs.index') }}" 
               class="text-gray-600 hover:text-iris-yellow">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-iris-black-900">Créer un utilisateur</h1>
                <p class="text-gray-600 mt-1">Ajoutez un nouvel utilisateur à la plateforme</p>
            </div>
        </div>

        {{-- ✅ INFO MATRICULE AUTO --}}
        <div class="bg-blue-50 border-l-4 border-blue-500 rounded-r-lg p-4">
            <div class="flex items-start">
                <svg class="h-5 w-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-blue-800">
                        ℹ️ Génération automatique du matricule
                    </p>
                    <p class="text-sm text-blue-700 mt-1">
                        Le matricule sera généré automatiquement selon le format :
                    </p>
                    <ul class="text-sm text-blue-700 mt-2 space-y-1">
                        <li>• <strong>Admin :</strong> ADM-{{ date('Y') }}-001</li>
                        <li>• <strong>Enseignant :</strong> ENS-{{ date('Y') }}-001</li>
                        <li>• <strong>Étudiant :</strong> ETU-{{ date('Y') }}-001</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Formulaire -->
        <form method="POST" action="{{ route('admin.utilisateurs.store') }}" class="bg-white rounded-2xl shadow-sm p-8 space-y-6">
            @csrf

            <!-- Informations de base -->
            <div>
                <h3 class="text-lg font-semibold text-iris-black-900 mb-4">Informations de base</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nom -->
                    <div>
                        <label for="nom" class="block text-sm font-medium text-gray-700">
                            Nom <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="nom" 
                            id="nom" 
                            value="{{ old('nom') }}"
                            required
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow @error('nom') border-red-500 @enderror"
                        >
                        @error('nom')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Prénom -->
                    <div>
                        <label for="prenom" class="block text-sm font-medium text-gray-700">
                            Prénom <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="prenom" 
                            id="prenom" 
                            value="{{ old('prenom') }}"
                            required
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow @error('prenom') border-red-500 @enderror"
                        >
                        @error('prenom')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            value="{{ old('email') }}"
                            required
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow @error('email') border-red-500 @enderror"
                        >
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Rôle -->
                    <div>
                        <label for="role_id" class="block text-sm font-medium text-gray-700">
                            Rôle <span class="text-red-500">*</span>
                        </label>
                        <select 
                            name="role_id" 
                            id="role_id" 
                            required
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow @error('role_id') border-red-500 @enderror"
                        >
                            <option value="">Sélectionnez un rôle</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $role->nom)) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- ✅ MATRICULE RETIRÉ - SERA AUTO-GÉNÉRÉ --}}

                    <!-- Téléphone -->
                    <div>
                        <label for="telephone" class="block text-sm font-medium text-gray-700">
                            Téléphone
                        </label>
                        <input 
                            type="tel" 
                            name="telephone" 
                            id="telephone" 
                            value="{{ old('telephone') }}"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow"
                        >
                    </div>
                </div>
            </div>

            <!-- Informations personnelles -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-semibold text-iris-black-900 mb-4">Informations personnelles</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Date de naissance -->
                    <div>
                        <label for="date_naissance" class="block text-sm font-medium text-gray-700">
                            Date de naissance
                        </label>
                        <input 
                            type="date" 
                            name="date_naissance" 
                            id="date_naissance" 
                            value="{{ old('date_naissance') }}"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow"
                        >
                    </div>

                    <!-- Genre -->
                    <div>
                        <label for="genre" class="block text-sm font-medium text-gray-700">
                            Genre
                        </label>
                        <select 
                            name="genre" 
                            id="genre"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow"
                        >
                            <option value="">Sélectionnez</option>
                            <option value="homme" {{ old('genre') === 'homme' ? 'selected' : '' }}>Homme</option>
                            <option value="femme" {{ old('genre') === 'femme' ? 'selected' : '' }}>Femme</option>
                            <option value="autre" {{ old('genre') === 'autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                    </div>

                    <!-- Adresse -->
                    <div class="md:col-span-2">
                        <label for="adresse" class="block text-sm font-medium text-gray-700">
                            Adresse
                        </label>
                        <input 
                            type="text" 
                            name="adresse" 
                            id="adresse" 
                            value="{{ old('adresse') }}"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow"
                        >
                    </div>

                    <!-- Ville -->
                    <div>
                        <label for="ville" class="block text-sm font-medium text-gray-700">
                            Ville
                        </label>
                        <input 
                            type="text" 
                            name="ville" 
                            id="ville" 
                            value="{{ old('ville') }}"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow"
                        >
                    </div>

                    <!-- Code postal -->
                    <div>
                        <label for="code_postal" class="block text-sm font-medium text-gray-700">
                            Code postal
                        </label>
                        <input 
                            type="text" 
                            name="code_postal" 
                            id="code_postal" 
                            value="{{ old('code_postal') }}"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow"
                        >
                    </div>
                </div>
            </div>

            <!-- Informations spécifiques au rôle -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-semibold text-iris-black-900 mb-4">Informations spécifiques</h3>
                
                <!-- Classe (si étudiant) -->
                <div id="classe-field" class="hidden">
                    <label for="classe_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Classe
                    </label>
                    <select 
                        name="classe_id" 
                        id="classe_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow"
                    >
                        <option value="">Sélectionner une classe</option>
                        @foreach($classes as $classe)                       
                            <option value="{{ $classe->id }}" {{ old('classe_id') == $classe->id ? 'selected' : '' }}>
                                {{ $classe->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Spécialité (si enseignant) -->
                <div id="specialite-field" class="hidden">
                    <label for="specialite" class="block text-sm font-medium text-gray-700 mb-2">
                        Spécialité
                    </label>
                    <input 
                        type="text" 
                        name="specialite" 
                        id="specialite" 
                        value="{{ old('specialite') }}"
                        placeholder="Ex: Mathématiques, Informatique..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow"
                    >
                </div>
            </div>

            {{-- ✅ SECTION MOT DE PASSE RETIRÉE - SERA AUTO-GÉNÉRÉ --}}

            <!-- Boutons -->
            <div class="flex justify-end space-x-4 pt-6 border-t">
                <a href="{{ route('admin.utilisateurs.index') }}" 
                   class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition-all">
                    Annuler
                </a>
                <button 
                    type="submit"
                    class="px-6 py-3 bg-iris-yellow text-iris-black-900 rounded-lg font-semibold hover:bg-iris-yellow-600 transition-all shadow-sm">
                    ✅ Créer l'utilisateur
                </button>
            </div>
        </form>
    </div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role_id');
    const classeField = document.getElementById('classe-field');
    const specialiteField = document.getElementById('specialite-field');

    function updateFieldsVisibility() {
        const selectedOption = roleSelect.options[roleSelect.selectedIndex];
        const roleNom = selectedOption.text.toLowerCase();

        // Cacher tous les champs spécifiques par défaut
        classeField.classList.add('hidden');
        specialiteField.classList.add('hidden');

        // Afficher le champ approprié selon le rôle
        if (roleNom.includes('etudiant')) {
            classeField.classList.remove('hidden');
        } else if (roleNom.includes('enseignant')) {
            specialiteField.classList.remove('hidden');
        }
    }

    // Écouter les changements de rôle
    roleSelect.addEventListener('change', updateFieldsVisibility);
    
    // Appliquer au chargement de la page
    updateFieldsVisibility();
});
</script>
@endpush
@endsection