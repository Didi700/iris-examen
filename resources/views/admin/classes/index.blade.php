@extends('layouts.app')

@section('title', 'Gestion des classes')

@section('content')
    <div class="space-y-6">
        <!-- En-tête -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-iris-black-900">Gestion des classes</h1>
                <p class="text-gray-600 mt-1">Gérez toutes les classes de l'établissement</p>
            </div>
            <a href="{{ route('admin.classes.create') }}" 
               class="bg-iris-yellow text-iris-black-900 hover:bg-iris-yellow-600 px-6 py-3 rounded-lg font-semibold transition-all shadow-sm flex items-center">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nouvelle classe
            </a>
        </div>

        <!-- Filtres -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <form method="GET" action="{{ route('admin.classes.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Recherche -->
                <div class="md:col-span-2">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Rechercher (nom, code, niveau...)" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow"
                    >
                </div>

                <!-- Filtre par statut -->
                <div>
                    <select 
                        name="statut" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow"
                    >
                        <option value="">Tous les statuts</option>
                        <option value="active" {{ request('statut') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('statut') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="archivee" {{ request('statut') === 'archivee' ? 'selected' : '' }}>Archivée</option>
                    </select>
                </div>

                <!-- Bouton de filtrage -->
                <div class="md:col-span-3 flex space-x-2">
                    <button 
                        type="submit" 
                        class="bg-iris-yellow text-iris-black-900 px-6 py-2 rounded-lg font-semibold hover:bg-iris-yellow-600 transition-all"
                    >
                        Filtrer
                    </button>
                    <a 
                        href="{{ route('admin.classes.index') }}" 
                        class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg font-semibold hover:bg-gray-300 transition-all"
                    >
                        Réinitialiser
                    </a>
                </div>
            </form>
        </div>

        <!-- Grille des classes -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($classes as $classe)
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                    <!-- En-tête de la carte -->
                    <div class="bg-gradient-to-r from-iris-yellow to-yellow-500 p-6 text-iris-black-900">
                        <h3 class="text-xl font-bold">{{ $classe->nom }}</h3>
                        <p class="text-sm opacity-90 mt-1">{{ $classe->code }}</p>
                    </div>

                    <!-- Contenu de la carte -->
                    <div class="p-6 space-y-4">
                        <!-- Informations -->
                        <div class="space-y-2">
                            <div class="flex items-center text-sm">
                                <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                </svg>
                                <span class="text-gray-700">{{ $classe->niveau }}</span>
                            </div>

                            <div class="flex items-center text-sm">
                                <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="text-gray-700">{{ $classe->annee_scolaire }}</span>
                            </div>

                            <div class="flex items-center text-sm">
                                <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <span class="text-gray-700">{{ $classe->effectif_actuel }} / {{ $classe->effectif_max }} étudiants</span>
                            </div>
                        </div>

                        <!-- Barre de progression AVEC COULEURS -->
                        <div>
                            <div class="flex justify-between text-xs text-gray-600 mb-1">
                                <span>Remplissage</span>
                                <span>{{ $classe->pourcentageRemplissage() }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                @php
                                    $pourcentage = $classe->pourcentageRemplissage();
                                    if ($pourcentage >= 90) {
                                        $couleur = '#ef4444'; // Rouge
                                    } elseif ($pourcentage >= 75) {
                                        $couleur = '#eab308'; // Jaune
                                    } else {
                                        $couleur = '#22c55e'; // Vert
                                    }
                                @endphp
                                <div class="h-2 rounded-full transition-all" 
                                     style="width: {{ $pourcentage }}%; background-color: {{ $couleur }};"></div>
                            </div>
                        </div>

                        <!-- Régimes acceptés -->
                        <div class="flex flex-wrap gap-2">
                            @if($classe->accepte_initial)
                                <span class="px-2 py-1 text-xs font-semibold bg-iris-blue bg-opacity-20 text-iris-blue rounded-full">
                                    Initial
                                </span>
                            @endif
                            @if($classe->accepte_alternance)
                                <span class="px-2 py-1 text-xs font-semibold bg-iris-yellow bg-opacity-20 text-iris-yellow-700 rounded-full">
                                    Alternance
                                </span>
                            @endif
                            @if($classe->accepte_formation_continue)
                                <span class="px-2 py-1 text-xs font-semibold bg-iris-green bg-opacity-20 text-iris-green rounded-full">
                                    Formation continue
                                </span>
                            @endif
                        </div>

                        <!-- Statut -->
                        <div>
                            @php
                                if ($classe->statut === 'active') {
                                    $statutClasse = 'bg-green-100 text-green-800';
                                } elseif ($classe->statut === 'inactive') {
                                    $statutClasse = 'bg-gray-100 text-gray-800';
                                } else {
                                    $statutClasse = 'bg-yellow-100 text-yellow-800';
                                }
                            @endphp
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $statutClasse }}">
                                {{ ucfirst($classe->statut) }}
                            </span>
                        </div>

    <!-- Actions AVEC BORDURES -->
                        <div class="grid grid-cols-2 gap-3 pt-4 border-t">
                            <a href="{{ route('admin.classes.show', $classe->id) }}" 
                               class="text-center px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-semibold transition-all shadow-sm">
                                👁️ Voir
                            </a>
                            <a href="{{ route('admin.classes.edit', $classe->id) }}" 
                               class="text-center px-4 py-2.5 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 text-sm font-semibold transition-all shadow-sm">
                                ✏️ Modifier
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="md:col-span-3 text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <p class="mt-4 text-gray-600">Aucune classe trouvée.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $classes->links() }}
        </div>
    </div>
@endsection