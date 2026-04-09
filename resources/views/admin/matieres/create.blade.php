@extends('layouts.app')

@section('title', 'Créer une matière')

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">
        <!-- En-tête -->
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.matieres.index') }}" 
               class="text-gray-600 hover:text-iris-yellow">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-iris-black-900">Créer une matière</h1>
                <p class="text-gray-600 mt-1">Ajoutez une nouvelle matière au programme</p>
            </div>
        </div>

        <!-- Formulaire -->
        <form method="POST" action="{{ route('admin.matieres.store') }}" class="bg-white rounded-2xl shadow-sm p-8 space-y-6">
            @csrf

            <!-- Nom -->
            <div>
                <label for="nom" class="block text-sm font-medium text-gray-700">
                    Nom de la matière <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="nom" 
                    id="nom" 
                    value="{{ old('nom') }}"
                    placeholder="Ex: Programmation Orientée Objet"
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
                    Code de la matière <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="code" 
                    id="code" 
                    value="{{ old('code') }}"
                    placeholder="Ex: INFO101"
                    required
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow @error('code') border-red-500 @enderror"
                >
                @error('code')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Code unique pour identifier la matière</p>
            </div>

            <!-- Coefficient -->
            <div>
                <label for="coefficient" class="block text-sm font-medium text-gray-700">
                    Coefficient <span class="text-red-500">*</span>
                </label>
                <input 
                    type="number" 
                    name="coefficient" 
                    id="coefficient" 
                    value="{{ old('coefficient', 1) }}"
                    min="1"
                    max="10"
                    required
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow @error('coefficient') border-red-500 @enderror"
                >
                @error('coefficient')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Entre 1 et 10</p>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">
                    Description
                </label>
                <textarea 
                    name="description" 
                    id="description" 
                    rows="4"
                    placeholder="Décrivez brièvement le contenu de la matière..."
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow"
                >{{ old('description') }}</textarea>
            </div>

            <!-- Boutons -->
            <div class="flex justify-end space-x-4 pt-6 border-t">
                <a href="{{ route('admin.matieres.index') }}" 
                   class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition-all">
                    Annuler
                </a>
                <button 
                    type="submit"
                    class="px-6 py-3 bg-iris-yellow text-iris-black-900 rounded-lg font-semibold hover:bg-iris-yellow-600 transition-all shadow-sm">
                    Créer la matière
                </button>
            </div>
        </form>
    </div>
@endsection