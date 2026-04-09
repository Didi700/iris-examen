@extends('layouts.app')

@section('title', 'Affecter un enseignant')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- En-tête -->
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.utilisateurs.index') }}" 
                   class="text-gray-600 hover:text-iris-yellow">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-iris-black-900">Affecter un enseignant</h1>
                    <p class="text-gray-600 mt-1">{{ $enseignant->nomComplet() }}</p>
                </div>
            </div>
        </div>

        <!-- Formulaire d'affectation -->
        <div class="bg-white rounded-2xl shadow-sm p-8">
            <h3 class="text-lg font-semibold text-iris-black-900 mb-6">Nouvelle affectation</h3>
            
            <form method="POST" action="{{ route('admin.enseignants.affecter.store', $enseignant->id) }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Classe -->
                    <div>
                        <label for="classe_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Classe <span class="text-red-500">*</span>
                        </label>
                        <select 
                            id="classe_id" 
                            name="classe_id" 
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow @error('classe_id') border-red-500 @enderror"
                        >
                            <option value="">Sélectionnez une classe</option>
                            @foreach($classes as $classe)
                                <option value="{{ $classe->id }}" {{ old('classe_id') == $classe->id ? 'selected' : '' }}>
                                    {{ $classe->nom }} ({{ $classe->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('classe_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Matière -->
                    <div>
                        <label for="matiere_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Matière <span class="text-red-500">*</span>
                        </label>
                        <select 
                            id="matiere_id" 
                            name="matiere_id" 
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow @error('matiere_id') border-red-500 @enderror"
                        >
                            <option value="">Sélectionnez une matière</option>
                            @foreach($matieres as $matiere)
                                <option value="{{ $matiere->id }}" {{ old('matiere_id') == $matiere->id ? 'selected' : '' }}>
                                    {{ $matiere->nom }} ({{ $matiere->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('matiere_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Boutons -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('admin.utilisateurs.index') }}" 
                       class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition-all">
                        Annuler
                    </a>
                    <button 
                        type="submit" 
                        class="px-6 py-3 bg-iris-yellow text-iris-black-900 rounded-lg font-semibold hover:bg-iris-yellow-600 transition-all">
                        Affecter
                    </button>
                </div>
            </form>
        </div>

        <!-- Liste des affectations existantes -->
        @if($affectations->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm p-8">
                <h3 class="text-lg font-semibold text-iris-black-900 mb-6">Affectations actuelles</h3>
                
                <div class="space-y-3">
                    @foreach($affectations as $affectation)
                        @php
                            $classe = \App\Models\Classe::find($affectation->classe_id);
                            $matiere = \App\Models\Matiere::find($affectation->matiere_id);
                        @endphp
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-iris-black-900">{{ $classe->nom }}</p>
                                <p class="text-sm text-gray-600">{{ $matiere->nom }}</p>
                            </div>
                            <form method="POST" 
                                  action="{{ route('admin.enseignants.affecter.destroy', [$enseignant->id, $affectation->id]) }}"
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette affectation ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-800 font-medium text-sm">
                                    Retirer
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="mt-4 text-gray-600">Aucune affectation pour le moment.</p>
            </div>
        @endif
    </div>
@endsection