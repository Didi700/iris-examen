@extends('layouts.app')

@section('title', 'Banque de questions')

@section('content')
    <div class="space-y-6">
        <!-- En-tête -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-iris-black-900">Banque de questions</h1>
                <p class="text-gray-600 mt-1">Gérez toutes les questions d'examens</p>
            </div>
            <a href="{{ route('admin.questions.create') }}" 
               class="bg-iris-yellow text-iris-black-900 hover:bg-iris-yellow-600 px-6 py-3 rounded-lg font-semibold transition-all shadow-sm flex items-center">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nouvelle question
            </a>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <p class="text-sm font-medium text-gray-600">Total</p>
                <p class="text-3xl font-bold text-iris-black-900 mt-2">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <p class="text-sm font-medium text-gray-600">Actives</p>
                <p class="text-3xl font-bold text-iris-green mt-2">{{ $stats['actives'] }}</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <p class="text-sm font-medium text-gray-600">QCM</p>
                <p class="text-3xl font-bold text-iris-blue mt-2">{{ $stats['qcm'] }}</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <p class="text-sm font-medium text-gray-600">Texte libre</p>
                <p class="text-3xl font-bold text-iris-yellow-700 mt-2">{{ $stats['texte_libre'] }}</p>
            </div>
        </div>

        <!-- Filtres -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <form method="GET" action="{{ route('admin.questions.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <!-- Recherche -->
                    <div class="md:col-span-2">
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}"
                            placeholder="Rechercher dans les questions..." 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow"
                        >
                    </div>

                    <!-- Matière -->
                    <div>
                        <select 
                            name="matiere_id" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow"
                        >
                            <option value="">Toutes les matières</option>
                            @foreach($matieres as $matiere)
                                <option value="{{ $matiere->id }}" {{ request('matiere_id') == $matiere->id ? 'selected' : '' }}>
                                    {{ $matiere->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Type -->
                    <div>
                        <select 
                            name="type" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow"
                        >
                            <option value="">Tous les types</option>
                            <option value="qcm_simple" {{ request('type') === 'qcm_simple' ? 'selected' : '' }}>QCM simple</option>
                            <option value="qcm_multiple" {{ request('type') === 'qcm_multiple' ? 'selected' : '' }}>QCM multiple</option>
                            <option value="vrai_faux" {{ request('type') === 'vrai_faux' ? 'selected' : '' }}>Vrai/Faux</option>
                            <option value="texte_libre" {{ request('type') === 'texte_libre' ? 'selected' : '' }}>Texte libre</option>
                        </select>
                    </div>

                    <!-- Difficulté -->
                    <div>
                        <select 
                            name="difficulte" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow"
                        >
                            <option value="">Toutes les difficultés</option>
                            <option value="facile" {{ request('difficulte') === 'facile' ? 'selected' : '' }}>Facile</option>
                            <option value="moyen" {{ request('difficulte') === 'moyen' ? 'selected' : '' }}>Moyen</option>
                            <option value="difficile" {{ request('difficulte') === 'difficile' ? 'selected' : '' }}>Difficile</option>
                        </select>
                    </div>
                </div>

                <!-- Boutons -->
                <div class="flex space-x-2">
                    <button 
                        type="submit" 
                        class="bg-iris-yellow text-iris-black-900 px-6 py-2 rounded-lg font-semibold hover:bg-iris-yellow-600 transition-all"
                    >
                        Filtrer
                    </button>
                    <a 
                        href="{{ route('admin.questions.index') }}" 
                        class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg font-semibold hover:bg-gray-300 transition-all"
                    >
                        Réinitialiser
                    </a>
                </div>
            </form>
        </div>

        <!-- Liste des questions -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            @forelse($questions as $question)
                <div class="p-6 border-b hover:bg-gray-50 transition-colors">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <!-- En-tête de la question -->
                            <div class="flex items-center space-x-3 mb-3">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-iris-blue bg-opacity-20 text-iris-blue">
                                    {{ $question->matiere->nom }}
                                </span>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full
                                    @if($question->difficulte === 'facile') bg-green-100 text-green-800
                                    @elseif($question->difficulte === 'moyen') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ $question->difficulte_libelle }}
                                </span>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                    {{ $question->type_libelle }}
                                </span>
                                <span class="text-sm font-medium text-iris-yellow-700">
                                    {{ $question->points }} pt(s)
                                </span>
                                @if(!$question->est_active)
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        Inactive
                                    </span>
                                @endif
                            </div>

                            <!-- Énoncé -->
                            <p class="text-base font-medium text-iris-black-900 mb-2">
                                {{ Str::limit($question->enonce, 150) }}
                            </p>

                            <!-- Métadonnées -->
                            <div class="flex items-center space-x-4 text-sm text-gray-600">
                                <span>
                                    <svg class="inline h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                    {{ $question->reponses->count() }} réponse(s)
                                </span>
                                <span>
                                    <svg class="inline h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    Utilisée {{ $question->nb_utilisations }} fois
                                </span>
                                <span>Créée le {{ $question->created_at->format('d/m/Y') }}</span>
                            </div>

                            <!-- Tags -->
                            @if($question->tags)
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @foreach($question->tags_array as $tag)
                                        <span class="px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded">
                                            #{{ $tag }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center space-x-2 ml-4">
                            <a href="{{ route('admin.questions.show', $question->id) }}" 
                               class="p-2 text-iris-blue hover:bg-iris-blue hover:bg-opacity-10 rounded-lg transition-all"
                               title="Voir">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                            <a href="{{ route('admin.questions.edit', $question->id) }}" 
                               class="p-2 text-iris-yellow-700 hover:bg-iris-yellow hover:bg-opacity-10 rounded-lg transition-all"
                               title="Modifier">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('admin.questions.duplicate', $question->id) }}" class="inline">
                                @csrf
                                <button type="submit" 
                                        class="p-2 text-iris-green hover:bg-iris-green hover:bg-opacity-10 rounded-lg transition-all"
                                        title="Dupliquer">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                </button>
                            </form>
                            <form method="POST" 
                                  action="{{ route('admin.questions.destroy', $question->id) }}" 
                                  class="inline"
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette question ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-all"
                                        title="Supprimer">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="mt-4 text-gray-600">Aucune question trouvée.</p>
                    <a href="{{ route('admin.questions.create') }}" 
                       class="mt-4 inline-block bg-iris-yellow text-iris-black-900 px-6 py-3 rounded-lg font-semibold hover:bg-iris-yellow-600 transition-all">
                        Créer la première question
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $questions->links() }}
        </div>
    </div>
@endsection