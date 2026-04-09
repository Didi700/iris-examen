@extends('layouts.app')

@section('title', 'Détails utilisateur')

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
                    <h1 class="text-3xl font-bold text-iris-black-900">{{ $utilisateur->nomComplet() }}</h1>
                    <p class="text-gray-600 mt-1">{{ $utilisateur->matricule }}</p>
                </div>
            </div>
            <a href="{{ route('admin.utilisateurs.edit', $utilisateur) }}" 
               class="px-6 py-3 bg-iris-yellow text-iris-black-900 rounded-lg font-semibold hover:bg-iris-yellow-600 transition-all">
                Modifier
            </a>
        </div>

        <!-- Informations générales -->
        <div class="bg-white rounded-2xl shadow-sm p-8 space-y-6">
            <div>
                <h3 class="text-lg font-semibold text-iris-black-900 mb-4">Informations générales</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-600">Rôle</p>
                        <p class="text-base font-medium text-iris-black-900">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full
                                @if($utilisateur->role->nom === 'super_admin') bg-purple-100 text-purple-800
                                @elseif($utilisateur->role->nom === 'admin') bg-iris-yellow bg-opacity-20 text-iris-yellow-700
                                @elseif($utilisateur->role->nom === 'enseignant') bg-green-100 text-green-800
                                @else bg-iris-blue bg-opacity-20 text-iris-blue
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $utilisateur->role->nom)) }}
                            </span>
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600">Statut</p>
                        <p class="text-base font-medium">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full
                                @if($utilisateur->statut === 'actif') bg-green-100 text-green-800
                                @elseif($utilisateur->statut === 'inactif') bg-gray-100 text-gray-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($utilisateur->statut) }}
                            </span>
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="text-base font-medium text-iris-black-900">{{ $utilisateur->email }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600">Téléphone</p>
                        <p class="text-base font-medium text-iris-black-900">{{ $utilisateur->telephone ?? 'Non renseigné' }}</p>
                    </div>

                    @if($utilisateur->date_naissance)
                        <div>
                            <p class="text-sm text-gray-600">Date de naissance</p>
                            <p class="text-base font-medium text-iris-black-900">{{ $utilisateur->date_naissance->format('d/m/Y') }}</p>
                        </div>
                    @endif

                    @if($utilisateur->genre)
                        <div>
                            <p class="text-sm text-gray-600">Genre</p>
                            <p class="text-base font-medium text-iris-black-900">{{ ucfirst($utilisateur->genre) }}</p>
                        </div>
                    @endif

                    @if($utilisateur->adresse)
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-600">Adresse complète</p>
                            <p class="text-base font-medium text-iris-black-900">
                                {{ $utilisateur->adresse }}
                                @if($utilisateur->ville || $utilisateur->code_postal)
                                    <br>{{ $utilisateur->code_postal }} {{ $utilisateur->ville }}
                                @endif
                            </p>
                        </div>
                    @endif

                    <div>
                        <p class="text-sm text-gray-600">Créé par</p>
                        <p class="text-base font-medium text-iris-black-900">
                            {{ $utilisateur->createur ? $utilisateur->createur->nomComplet() : 'Système' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600">Créé le</p>
                        <p class="text-base font-medium text-iris-black-900">{{ $utilisateur->created_at->format('d/m/Y à H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Classes (pour étudiants et enseignants) -->
        @if($utilisateur->estEtudiant() && $utilisateur->classes->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm p-8">
                <h3 class="text-lg font-semibold text-iris-black-900 mb-4">Classes inscrites</h3>
                <div class="space-y-3">
                    @foreach($utilisateur->classes as $classe)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-iris-black-900">{{ $classe->nom }}</p>
                                <p class="text-sm text-gray-600">
                                    {{ ucfirst($classe->pivot->regime) }} - 
                                    Inscrit le {{ \Carbon\Carbon::parse($classe->pivot->date_inscription)->format('d/m/Y') }}
                                </p>
                            </div>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full
                                @if($classe->pivot->statut === 'inscrit') bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($classe->pivot->statut) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if($utilisateur->estEnseignant() && $utilisateur->classesEnseignees->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm p-8">
                <h3 class="text-lg font-semibold text-iris-black-900 mb-4">Classes enseignées</h3>
                <div class="space-y-3">
                    @foreach($utilisateur->classesEnseignees as $classe)
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="font-medium text-iris-black-900">{{ $classe->nom }}</p>
                            <p class="text-sm text-gray-600">{{ $classe->niveau }} - {{ $classe->annee_scolaire }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection