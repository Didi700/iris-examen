@extends('layouts.app')

@section('title', 'Mes examens')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">📝 Mes examens</h1>
            <p class="text-gray-600 mt-1">Gérez vos examens et consultez les statistiques</p>
        </div>
        <a href="{{ route('enseignant.examens.create') }}" 
           class="px-6 py-3 bg-iris-yellow text-gray-900 rounded-xl font-bold hover:bg-yellow-600 transition-all shadow-lg flex items-center">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Créer un examen
        </a>
    </div>

    <!-- Stats rapides -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-4 border-2 border-gray-200">
            <p class="text-sm text-gray-600">Total</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 border-2 border-green-200">
            <p class="text-sm text-gray-600">Publiés</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['publies'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 border-2 border-gray-200">
            <p class="text-sm text-gray-600">Brouillons</p>
            <p class="text-2xl font-bold text-gray-600">{{ $stats['brouillons'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 border-2 border-blue-200">
            <p class="text-sm text-gray-600">Terminés</p>
            <p class="text-2xl font-bold text-blue-600">{{ $stats['termines'] }}</p>
        </div>
    </div>

    <!-- Barre de recherche et filtres -->
    <div class="bg-white rounded-2xl shadow-lg p-6 border-2 border-gray-100">
        <form method="GET" action="{{ route('enseignant.examens.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Recherche -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">🔍 Rechercher</label>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Titre ou description..."
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-iris-blue focus:ring-2 focus:ring-iris-blue focus:ring-opacity-20 transition-all">
                </div>

                <!-- Filtre Matière -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">📚 Matière</label>
                    <select name="matiere_id" 
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-iris-blue focus:ring-2 focus:ring-iris-blue focus:ring-opacity-20 transition-all">
                        <option value="">Toutes les matières</option>
                        @foreach($matieres as $matiere)
                            <option value="{{ $matiere->id }}" {{ request('matiere_id') == $matiere->id ? 'selected' : '' }}>
                                {{ $matiere->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtre Classe -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">🎓 Classe</label>
                    <select name="classe_id" 
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-iris-blue focus:ring-2 focus:ring-iris-blue focus:ring-opacity-20 transition-all">
                        <option value="">Toutes les classes</option>
                        @foreach($classes as $classe)
                            <option value="{{ $classe->id }}" {{ request('classe_id') == $classe->id ? 'selected' : '' }}>
                                {{ $classe->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Filtre Statut -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">📊 Statut</label>
                    <select name="statut" 
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-iris-blue focus:ring-2 focus:ring-iris-blue focus:ring-opacity-20 transition-all">
                        <option value="">Tous les statuts</option>
                        <option value="brouillon" {{ request('statut') == 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                        <option value="publie" {{ request('statut') == 'publie' ? 'selected' : '' }}>Publié</option>
                        <option value="termine" {{ request('statut') == 'termine' ? 'selected' : '' }}>Terminé</option>
                        <option value="archive" {{ request('statut') == 'archive' ? 'selected' : '' }}>Archivé</option>
                    </select>
                </div>

                <!-- Tri -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">🔄 Trier par</label>
                    <select name="sort_by" 
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-iris-blue focus:ring-2 focus:ring-iris-blue focus:ring-opacity-20 transition-all">
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date de création</option>
                        <option value="titre" {{ request('sort_by') == 'titre' ? 'selected' : '' }}>Titre</option>
                        <option value="date_debut" {{ request('sort_by') == 'date_debut' ? 'selected' : '' }}>Date de début</option>
                        <option value="date_fin" {{ request('sort_by') == 'date_fin' ? 'selected' : '' }}>Date de fin</option>
                    </select>
                </div>

                <!-- Ordre -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">↕️ Ordre</label>
                    <select name="sort_order" 
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-iris-blue focus:ring-2 focus:ring-iris-blue focus:ring-opacity-20 transition-all">
                        <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Décroissant</option>
                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Croissant</option>
                    </select>
                </div>

                <!-- Boutons -->
                <div class="flex items-end space-x-2">
                    <button type="submit" 
                            class="flex-1 px-6 py-3 bg-iris-blue text-white rounded-xl font-bold hover:bg-blue-700 transition-all">
                        Filtrer
                    </button>
                    <a href="{{ route('enseignant.examens.index') }}" 
                       class="px-4 py-3 bg-gray-200 text-gray-700 rounded-xl font-bold hover:bg-gray-300 transition-all">
                        ✕
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Liste des examens -->
    <div class="bg-white rounded-2xl shadow-lg p-8 border-2 border-gray-100">
        @if($examens->count() > 0)
            <div class="space-y-4">
                @foreach($examens as $examen)
                    <div class="border-2 border-gray-200 rounded-xl p-6 hover:border-iris-yellow hover:shadow-lg transition-all">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <h3 class="text-xl font-bold text-gray-900">{{ $examen->titre }}</h3>
                                    
                                    @if($examen->statut === 'brouillon')
                                        <span class="px-3 py-1 text-xs font-bold rounded-full bg-gray-200 text-gray-800">📝 Brouillon</span>
                                    @elseif($examen->statut === 'publie')
                                        <span class="px-3 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800">✅ Publié</span>
                                    @elseif($examen->statut === 'termine')
                                        <span class="px-3 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-800">🏁 Terminé</span>
                                    @else
                                        <span class="px-3 py-1 text-xs font-bold rounded-full bg-gray-400 text-white">📦 Archivé</span>
                                    @endif

                                    @if($examen->type_examen === 'document')
                                        <span class="px-3 py-1 text-xs font-bold rounded-full bg-purple-100 text-purple-800">📄 PDF</span>
                                    @else
                                        <span class="px-3 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-800">💻 En ligne</span>
                                    @endif
                                </div>

                                @if($examen->description)
                                    <p class="text-gray-600 mb-3">{{ Str::limit($examen->description, 150) }}</p>
                                @endif

                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                    <div>
                                        <p class="text-gray-500">📚 Matière</p>
                                        <p class="font-semibold text-gray-900">{{ $examen->matiere->nom }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500">👥 Classe</p>
                                        <p class="font-semibold text-gray-900">{{ $examen->classe->nom }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500">⏱️ Durée</p>
                                        <p class="font-semibold text-gray-900">{{ $examen->duree_minutes }} min</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500">❓ Questions</p>
                                        <p class="font-semibold text-gray-900">{{ $examen->questions_count }}</p>
                                    </div>
                                </div>

                                <div class="mt-4 flex items-center space-x-4 text-xs text-gray-600">
                                    <span>📅 Début : {{ $examen->date_debut->format('d/m/Y H:i') }}</span>
                                    <span>•</span>
                                    <span>🏁 Fin : {{ $examen->date_fin->format('d/m/Y H:i') }}</span>
                                    <span>•</span>
                                    <span>✏️ {{ $examen->sessions_count }} session(s)</span>
                                </div>
                            </div>

                            <div class="ml-6 flex flex-col space-y-2">
                                <a href="{{ route('enseignant.examens.show', $examen->id) }}" 
                                   class="px-4 py-2 bg-iris-blue text-white rounded-lg font-semibold hover:bg-blue-700 transition-all text-center">
                                    Voir
                                </a>

                                @if($examen->statut === 'brouillon')
                                    <a href="{{ route('enseignant.examens.edit', $examen->id) }}" 
                                       class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition-all text-center">
                                        Modifier
                                    </a>
                                    
                                    <form action="{{ route('enseignant.examens.publier', $examen->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                onclick="return confirm('Voulez-vous vraiment publier cet examen ?')"
                                                class="w-full px-4 py-2 bg-green-500 text-white rounded-lg font-semibold hover:bg-green-600 transition-all">
                                            Publier
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('enseignant.examens.duplicate', $examen->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full px-4 py-2 bg-iris-yellow text-gray-900 rounded-lg font-semibold hover:bg-yellow-600 transition-all">
                                        Dupliquer
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $examens->links() }}
            </div>
        @else
            <div class="text-center py-16">
                <svg class="mx-auto h-24 w-24 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Aucun examen trouvé</h3>
                <p class="text-gray-600 mb-6">
                    @if(request()->hasAny(['search', 'matiere_id', 'classe_id', 'statut']))
                        Aucun examen ne correspond à vos critères de recherche.
                    @else
                        Commencez par créer votre premier examen.
                    @endif
                </p>
                <a href="{{ route('enseignant.examens.create') }}" 
                   class="inline-block px-8 py-4 bg-iris-yellow text-gray-900 rounded-xl font-bold hover:bg-yellow-600 transition-all shadow-lg">
                    + Créer un examen
                </a>
            </div>
        @endif
    </div>
</div>
@endsection