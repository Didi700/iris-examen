@extends('layouts.app')

@section('title', 'Créer une classe')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- En-tête -->
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.classes.index') }}" 
               class="text-gray-600 hover:text-iris-yellow">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-iris-black-900">Créer une classe</h1>
                <p class="text-gray-600 mt-1">Ajoutez une nouvelle classe à l'établissement</p>
            </div>
        </div>

        <!-- Formulaire -->
        <form method="POST" action="{{ route('admin.classes.store') }}" class="bg-white rounded-2xl shadow-sm p-8 space-y-6">
            @csrf

            <!-- Informations de base -->
            <div>
                <h3 class="text-lg font-semibold text-iris-black-900 mb-4">Informations de base</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nom -->
                    <div>
                        <label for="nom" class="block text-sm font-medium text-gray-700">
                            Nom de la classe <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="nom" 
                            id="nom" 
                            value="{{ old('nom') }}"
                            placeholder="Ex: BTS SIO SLAM 1"
                            required
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow @error('nom') border-red-500 @enderror"
                        >
                        @error('nom')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Code -->
                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700">
                            Code de la classe <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="code" 
                            id="code" 
                            value="{{ old('code') }}"
                            placeholder="Ex: BTS-SIO1-2025"
                            required
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow @error('code') border-red-500 @enderror"
                        >
                        @error('code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Niveau -->
                    <div>
                        <label for="niveau" class="block text-sm font-medium text-gray-700">
                            Niveau <span class="text-red-500">*</span>
                        </label>
                        <select 
                            name="niveau" 
                            id="niveau" 
                            required
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow @error('niveau') border-red-500 @enderror"
                        >
                            <option value="">Sélectionnez un niveau</option>
                            <option value="Bac+2" {{ old('niveau') === 'Bac+2' ? 'selected' : '' }}>Bac+2</option>
                            <option value="Bac+3" {{ old('niveau') === 'Bac+3' ? 'selected' : '' }}>Bac+3</option>
                            <option value="Bac+4" {{ old('niveau') === 'Bac+4' ? 'selected' : '' }}>Bac+4</option>
                            <option value="Bac+5" {{ old('niveau') === 'Bac+5' ? 'selected' : '' }}>Bac+5</option>
                        </select>
                        @error('niveau')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Année scolaire -->
                    <div>
                        <label for="annee_scolaire" class="block text-sm font-medium text-gray-700">
                            Année scolaire <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="annee_scolaire" 
                            id="annee_scolaire" 
                            value="{{ old('annee_scolaire', date('Y') . '-' . (date('Y') + 1)) }}"
                            placeholder="Ex: 2024-2025"
                            required
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow @error('annee_scolaire') border-red-500 @enderror"
                        >
                        @error('annee_scolaire')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Effectif maximum -->
                    <div>
                        <label for="effectif_max" class="block text-sm font-medium text-gray-700">
                            Effectif maximum <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            name="effectif_max" 
                            id="effectif_max" 
                            value="{{ old('effectif_max', 30) }}"
                            min="1"
                            required
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow @error('effectif_max') border-red-500 @enderror"
                        >
                        @error('effectif_max')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700">
                            Description
                        </label>
                        <textarea 
                            name="description" 
                            id="description" 
                            rows="3"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow"
                        >{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Dates -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-semibold text-iris-black-900 mb-4">Période</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Date de début -->
                    <div>
                        <label for="date_debut" class="block text-sm font-medium text-gray-700">
                            Date de début
                        </label>
                        <input 
                            type="date" 
                            name="date_debut" 
                            id="date_debut" 
                            value="{{ old('date_debut') }}"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow"
                        >
                    </div>

                    <!-- Date de fin -->
                    <div>
                        <label for="date_fin" class="block text-sm font-medium text-gray-700">
                            Date de fin
                        </label>
                        <input 
                            type="date" 
                            name="date_fin" 
                            id="date_fin" 
                            value="{{ old('date_fin') }}"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow"
                        >
                    </div>
                </div>
            </div>

            <!-- Régimes acceptés -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-semibold text-iris-black-900 mb-4">Régimes de formation acceptés</h3>
                <div class="space-y-3">
                    <!-- Initial -->
                    <div class="flex items-center">
                        <input 
                            type="checkbox" 
                            name="accepte_initial" 
                            id="accepte_initial" 
                            value="1"
                            {{ old('accepte_initial', true) ? 'checked' : '' }}
                            class="h-4 w-4 text-iris-yellow focus:ring-iris-yellow border-gray-300 rounded"
                        >
                        <label for="accepte_initial" class="ml-3 text-sm text-gray-700">
                            Formation initiale
                        </label>
                    </div>

                    <!-- Alternance -->
                    <div class="flex items-center">
                        <input 
                            type="checkbox" 
                            name="accepte_alternance" 
                            id="accepte_alternance" 
                            value="1"
                            {{ old('accepte_alternance', true) ? 'checked' : '' }}
                            class="h-4 w-4 text-iris-yellow focus:ring-iris-yellow border-gray-300 rounded"
                        >
                        <label for="accepte_alternance" class="ml-3 text-sm text-gray-700">
                            Alternance
                        </label>
                    </div>

                    <!-- Formation continue -->
                    <div class="flex items-center">
                        <input 
                            type="checkbox" 
                            name="accepte_formation_continue" 
                            id="accepte_formation_continue" 
                            value="1"
                            {{ old('accepte_formation_continue') ? 'checked' : '' }}
                            class="h-4 w-4 text-iris-yellow focus:ring-iris-yellow border-gray-300 rounded"
                        >
                        <label for="accepte_formation_continue" class="ml-3 text-sm text-gray-700">
                            Formation continue
                        </label>
                    </div>
                </div>
            </div>

            <!-- Boutons -->
            <div class="flex justify-end space-x-4 pt-6 border-t">
                <a href="{{ route('admin.classes.index') }}" 
                   class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition-all">
                    Annuler
                </a>
                <button 
                    type="submit"
                    class="px-6 py-3 bg-iris-yellow text-iris-black-900 rounded-lg font-semibold hover:bg-iris-yellow-600 transition-all shadow-sm">
                    Créer la classe
                </button>
            </div>
        </form>
    </div>
@endsection