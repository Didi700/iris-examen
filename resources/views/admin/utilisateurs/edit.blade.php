@extends('layouts.app')

@section('title', 'Modifier un utilisateur')

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
                <h1 class="text-3xl font-bold text-iris-black-900">Modifier un utilisateur</h1>
                <p class="text-gray-600 mt-1">{{ $utilisateur->nomComplet() }} - {{ $utilisateur->matricule }}</p>
            </div>
        </div>

        <!-- Formulaire -->
        <form method="POST" action="{{ route('admin.utilisateurs.update', $utilisateur) }}" class="bg-white rounded-2xl shadow-sm p-8 space-y-6">
            @csrf
            @method('PUT')

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
                            value="{{ old('nom', $utilisateur->nom) }}"
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
                            value="{{ old('prenom', $utilisateur->prenom) }}"
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
                            value="{{ old('email', $utilisateur->email) }}"
                            required
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow @error('email') border-red-500 @enderror"
                        >
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Matricule -->
                    <div>
                        <label for="matricule" class="block text-sm font-medium text-gray-700">
                            Matricule <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="matricule" 
                            id="matricule" 
                            value="{{ old('matricule', $utilisateur->matricule) }}"
                            required
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow @error('matricule') border-red-500 @enderror"
                        >
                        @error('matricule')
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
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id', $utilisateur->role_id) == $role->id ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $role->nom)) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Téléphone -->
                    <div>
                        <label for="telephone" class="block text-sm font-medium text-gray-700">
                            Téléphone
                        </label>
                        <input 
                            type="tel" 
                            name="telephone" 
                            id="telephone" 
                            value="{{ old('telephone', $utilisateur->telephone) }}"
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
                            <option value="actif" {{ old('statut', $utilisateur->statut) === 'actif' ? 'selected' : '' }}>Actif</option>
                            <option value="inactif" {{ old('statut', $utilisateur->statut) === 'inactif' ? 'selected' : '' }}>Inactif</option>
                            <option value="suspendu" {{ old('statut', $utilisateur->statut) === 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                        </select>
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
                            value="{{ old('date_naissance', $utilisateur->date_naissance?->format('Y-m-d')) }}"
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
                            <option value="homme" {{ old('genre', $utilisateur->genre) === 'homme' ? 'selected' : '' }}>Homme</option>
                            <option value="femme" {{ old('genre', $utilisateur->genre) === 'femme' ? 'selected' : '' }}>Femme</option>
                            <option value="autre" {{ old('genre', $utilisateur->genre) === 'autre' ? 'selected' : '' }}>Autre</option>
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
                            value="{{ old('adresse', $utilisateur->adresse) }}"
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
                            value="{{ old('ville', $utilisateur->ville) }}"
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
                            value="{{ old('code_postal', $utilisateur->code_postal) }}"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow"
                        >
                    </div>
                </div>
            </div>

            <!-- Mot de passe (optionnel) -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-semibold text-iris-black-900 mb-4">Modifier le mot de passe (optionnel)</h3>
                <p class="text-sm text-gray-600 mb-4">Laissez vide pour conserver le mot de passe actuel</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Mot de passe -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            Nouveau mot de passe
                        </label>
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow @error('password') border-red-500 @enderror"
                        >
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirmation mot de passe -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                            Confirmer le mot de passe
                        </label>
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            id="password_confirmation" 
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow"
                        >
                    </div>
                </div>
            </div>

            <!-- Boutons -->
            <div class="flex justify-end space-x-4 pt-6 border-t">
                <a href="{{ route('admin.utilisateurs.index') }}" 
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
@endsection