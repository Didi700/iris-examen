@extends('layouts.app')

@section('title', 'Banque de questions')

@section('content')
    <div class="space-y-6">
        <!-- En-tête -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-iris-black-900">Banque de questions</h1>
                <p class="text-gray-600 mt-1">Gérez vos questions pour les examens</p>
            </div>
            <a href="{{ route('enseignant.questions.create') }}" 
               class="px-6 py-3 bg-iris-yellow text-iris-black-900 rounded-lg font-bold hover:bg-iris-yellow-600 transition-all shadow-lg">
                <svg class="inline h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Créer une question
            </a>
        </div>

        <!-- Filtres -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <form method="GET" action="{{ route('enseignant.questions.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Recherche -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Recherche</label>
                        <input type="text" 
                               name="search" 
                               id="search" 
                               value="{{ request('search') }}"
                               placeholder="Énoncé..."
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iris-yellow focus:border-transparent">
                    </div>

                    <!-- Type -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                        <select name="type" id="type" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iris-yellow focus:border-transparent">
                            <option value="">Tous les types</option>
                            <option value="choix_unique" {{ request('type') === 'choix_unique' ? 'selected' : '' }}>Choix unique</option>
                            <option value="choix_multiple" {{ request('type') === 'choix_multiple' ? 'selected' : '' }}>Choix multiple</option>
                            <option value="vrai_faux" {{ request('type') === 'vrai_faux' ? 'selected' : '' }}>Vrai/Faux</option>
                            <option value="reponse_courte" {{ request('type') === 'reponse_courte' ? 'selected' : '' }}>Réponse courte</option>
                        </select>
                    </div>

                    <!-- Difficulté -->
                    <div>
                        <label for="difficulte" class="block text-sm font-medium text-gray-700 mb-2">Difficulté</label>
                        <select name="difficulte" id="difficulte" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iris-yellow focus:border-transparent">
                            <option value="">Toutes</option>
                            <option value="facile" {{ request('difficulte') === 'facile' ? 'selected' : '' }}>Facile</option>
                            <option value="moyen" {{ request('difficulte') === 'moyen' ? 'selected' : '' }}>Moyen</option>
                            <option value="difficile" {{ request('difficulte') === 'difficile' ? 'selected' : '' }}>Difficile</option>
                        </select>
                    </div>

                    <!-- Matière -->
                    <div>
                        <label for="matiere_id" class="block text-sm font-medium text-gray-700 mb-2">Matière</label>
                        <select name="matiere_id" id="matiere_id" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iris-yellow focus:border-transparent">
                            <option value="">Toutes les matières</option>
                            @php
                                $matieres = DB::table('enseignant_classe')
                                    ->where('enseignant_id', auth()->id())
                                    ->join('matieres', 'enseignant_classe.matiere_id', '=', 'matieres.id')
                                    ->select('matieres.*')
                                    ->distinct()
                                    ->orderBy('matieres.nom')
                                    ->get();
                            @endphp
                            @foreach($matieres as $matiere)
                                <option value="{{ $matiere->id }}" {{ request('matiere_id') == $matiere->id ? 'selected' : '' }}>
                                    {{ $matiere->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex items-center space-x-2">
                    <button type="submit" 
                            class="px-6 py-2 bg-iris-blue text-white rounded-lg font-semibold hover:bg-blue-700 transition-all">
                        Filtrer
                    </button>
                    <a href="{{ route('enseignant.questions.index') }}" 
                       class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition-all">
                        Réinitialiser
                    </a>
                </div>
            </form>
        </div>

        <!-- Liste des questions -->
        <div class="bg-white rounded-2xl shadow-sm p-8">
            @if($questions->count() > 0)
                <div class="space-y-4">
                    @foreach($questions as $question)
                        <div class="border border-gray-200 rounded-xl p-6 hover:border-iris-yellow hover:shadow-md transition-all">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-3">
                                        <h3 class="text-lg font-bold text-gray-900">{{ Str::limit($question->enonce, 120) }}</h3>
                                        
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full
                                            @if($question->type === 'choix_unique') bg-blue-100 text-blue-800
                                            @elseif($question->type === 'choix_multiple') bg-purple-100 text-purple-800
                                            @elseif($question->type === 'vrai_faux') bg-green-100 text-green-800
                                            @else bg-orange-100 text-orange-800
                                            @endif">
                                            {{ ucfirst(str_replace('_', ' ', $question->type)) }}
                                        </span>

                                        <span class="px-3 py-1 text-xs font-semibold rounded-full
                                            @if($question->difficulte === 'facile') bg-green-100 text-green-800
                                            @elseif($question->difficulte === 'moyen') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst($question->difficulte) }}
                                        </span>

                                        @if($question->est_active)
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                Active
                                            </span>
                                        @else
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                Désactivée
                                            </span>
                                        @endif
                                    </div>

                                    <p class="text-sm text-gray-600 mb-3">{{ $question->matiere->nom }}</p>

                                    <!-- Aperçu des réponses -->
                                    @if($question->type !== 'reponse_courte')
                                        <div class="space-y-2">
                                            @foreach($question->reponses->take(3) as $reponse)
                                                <div class="flex items-center space-x-2 text-sm">
                                                    @if($reponse->est_correcte)
                                                        <svg class="h-4 w-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    @else
                                                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <circle cx="12" cy="12" r="10" />
                                                        </svg>
                                                    @endif
                                                    <span class="{{ $reponse->est_correcte ? 'text-green-700 font-semibold' : 'text-gray-600' }}">
                                                        {{ Str::limit($reponse->texte, 80) }}
                                                    </span>
                                                </div>
                                            @endforeach
                                            @if($question->reponses->count() > 3)
                                                <p class="text-xs text-gray-500 ml-6">+ {{ $question->reponses->count() - 3 }} autre(s) réponse(s)</p>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                <div class="ml-6 flex flex-col space-y-2">
                                    <a href="{{ route('enseignant.questions.show', $question->id) }}" 
                                       class="px-6 py-2 bg-iris-blue text-white rounded-lg font-semibold hover:bg-blue-700 transition-all text-center">
                                        Voir
                                    </a>

                                    <a href="{{ route('enseignant.questions.edit', $question->id) }}" 
                                       class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition-all text-center">
                                        Modifier
                                    </a>

                                    <!-- Menu déroulant -->
                                    <div class="relative" x-data="{ open: false }">
                                        <button @click="open = !open" 
                                                class="w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all">
                                            <svg class="h-5 w-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                            </svg>
                                        </button>

                                        <div x-show="open" 
                                             @click.away="open = false"
                                             class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-10">
                                            <form action="{{ route('enseignant.questions.duplicate', $question->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" 
                                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                    Dupliquer
                                                </button>
                                            </form>

                                            <form action="{{ route('enseignant.questions.toggle-active', $question->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" 
                                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                    {{ $question->est_active ? 'Désactiver' : 'Activer' }}
                                                </button>
                                            </form>

                                            <form action="{{ route('enseignant.questions.destroy', $question->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        onclick="return confirm('Supprimer cette question ?')"
                                                        class="block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-gray-100">
                                                    Supprimer
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $questions->links() }}
                </div>
            @else
                <div class="text-center py-16">
                    <svg class="mx-auto h-20 w-20 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Aucune question trouvée</h3>
                    <p class="text-gray-600 mb-4">Commencez par créer votre première question</p>
                    <a href="{{ route('enseignant.questions.create') }}" 
                       class="inline-block px-6 py-3 bg-iris-yellow text-iris-black-900 rounded-lg font-bold hover:bg-iris-yellow-600 transition-all">
                        Créer une question
                    </a>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    @endpush
@endsection