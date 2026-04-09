@extends('layouts.app')

@section('title', 'Gestion des matières')

@section('content')
    <div class="space-y-6">
        <!-- En-tête -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-iris-black-900">Gestion des matières</h1>
                <p class="text-gray-600 mt-1">Gérez toutes les matières enseignées</p>
            </div>
            <a href="{{ route('admin.matieres.create') }}" 
               class="bg-iris-yellow text-iris-black-900 hover:bg-iris-yellow-600 px-6 py-3 rounded-lg font-semibold transition-all shadow-sm flex items-center">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nouvelle matière
            </a>
        </div>

        <!-- Filtres -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <form method="GET" action="{{ route('admin.matieres.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Recherche -->
                <div class="md:col-span-2">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Rechercher (nom, code...)" 
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
                        href="{{ route('admin.matieres.index') }}" 
                        class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg font-semibold hover:bg-gray-300 transition-all"
                    >
                        Réinitialiser
                    </a>
                </div>
            </form>
        </div>

        <!-- Grille des matières -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($matieres as $matiere)
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                    <!-- En-tête de la carte -->
                    <div class="bg-gradient-to-r from-iris-blue to-blue-600 p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-bold">{{ $matiere->nom }}</h3>
                                <p class="text-sm opacity-90 mt-1">{{ $matiere->code }}</p>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-full px-3 py-1">
                                <span class="text-sm font-bold">Coef. {{ $matiere->coefficient }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Contenu de la carte -->
                    <div class="p-6 space-y-4">
                        @if($matiere->description)
                            <div>
                                <p class="text-sm text-gray-600">{{ Str::limit($matiere->description, 100) }}</p>
                            </div>
                        @endif

                        <!-- Statistiques -->
                        <div class="grid grid-cols-2 gap-4 pt-4 border-t">
                            <div class="text-center">
                                <p class="text-2xl font-bold text-iris-yellow-700">{{ $matiere->questions()->count() }}</p>
                                <p class="text-xs text-gray-600">Questions</p>
                            </div>
                            <div class="text-center">
                                <p class="text-2xl font-bold text-iris-blue">{{ $matiere->examens()->count() }}</p>
                                <p class="text-xs text-gray-600">Examens</p>
                            </div>
                        </div>

                        <!-- Statut -->
                        <div class="pt-4 border-t">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full
                                @if($matiere->statut === 'active') bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($matiere->statut) }}
                            </span>
                        </div>

                        <!-- Actions -->
                        <div class="flex space-x-2 pt-4 border-t">
                            <a href="{{ route('admin.matieres.show', $matiere) }}" 
                               class="flex-1 text-center px-4 py-2 bg-iris-blue text-white rounded-lg hover:bg-blue-700 text-sm font-semibold transition-all">
                                Voir
                            </a>
                            <a href="{{ route('admin.matieres.edit', $matiere) }}" 
                               class="flex-1 text-center px-4 py-2 bg-iris-yellow text-iris-black-900 rounded-lg hover:bg-iris-yellow-600 text-sm font-semibold transition-all">
                                Modifier
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="md:col-span-3 text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <p class="mt-4 text-gray-600">Aucune matière trouvée.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $matieres->links() }}
        </div>
    </div>
@endsection