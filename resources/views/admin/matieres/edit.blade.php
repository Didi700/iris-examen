@extends('layouts.app')

@section('title', 'Modifier une matière')

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
                <h1 class="text-3xl font-bold text-iris-black-900">Modifier une matière</h1>
                <p class="text-gray-600 mt-1">{{ $matiere->nom }} - {{ $matiere->code }}</p>
            </div>
        </div>

        <!-- Formulaire -->
        <form method="POST" action="{{ route('admin.matieres.update', $matiere) }}" class="bg-white rounded-2xl shadow-sm p-8 space-y-6">
            @csrf
            @method('PUT')

            <!-- Nom -->
            <div>
                <label for="nom" class="block text-sm font-medium text-gray-700">
                    Nom de la matière <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="nom" 
                    id="nom" 
                    value="{{ old('nom', $matiere->nom) }}"
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
                    value="{{ old('code', $matiere->code) }}"
                    required
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow @error('code') border-red-500 @enderror"
                >
                @error('code')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
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
                    value="{{ old('coefficient', $matiere->coefficient) }}"
                    min="1"
                    max="10"
                    required
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow @error('coefficient') border-red-500 @enderror"
                >
                @error('coefficient')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
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
                    <option value="active" {{ old('statut', $matiere->statut) === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('statut', $matiere->statut) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
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
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow"
                >{{ old('description', $matiere->description) }}</textarea>
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
                    Enregistrer les modifications
                </button>
            </div>
        </form>

        <!-- Danger Zone -->
        <div class="bg-red-50 rounded-2xl shadow-sm p-8 border border-red-200">
            <h3 class="text-lg font-semibold text-red-900 mb-2">Zone dangereuse</h3>
            <p class="text-sm text-red-700 mb-4">
                La suppression de cette matière est irréversible. Toutes les questions et examens associés seront également supprimés.
            </p>
            <form method="POST" action="{{ route('admin.matieres.destroy', $matiere) }}" 
                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette matière ? Cette action est irréversible.')">
                @csrf
                @method('DELETE')
                <button 
                    type="submit"
                    class="px-6 py-3 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition-all">
                    Supprimer cette matière
                </button>
            </form>
        </div>
    </div>
@endsection