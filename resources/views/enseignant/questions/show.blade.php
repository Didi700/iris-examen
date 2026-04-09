@extends('layouts.app')

@section('title', 'Question - ' . Str::limit($question->enonce, 50))

@section('content')
    <div class="space-y-6">
        <!-- En-tête -->
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('enseignant.questions.index') }}" 
                   class="text-gray-600 hover:text-iris-yellow transition-colors">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-iris-black-900">Détail de la question</h1>
                    <p class="text-gray-600 mt-1">{{ $question->matiere->nom }}</p>
                </div>
            </div>

            <div class="flex items-center space-x-2">
                <a href="{{ route('enseignant.questions.edit', $question->id) }}" 
                   class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition-all">
                    Modifier
                </a>
                <form action="{{ route('enseignant.questions.duplicate', $question->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" 
                            class="px-6 py-3 bg-iris-blue text-white rounded-lg font-semibold hover:bg-blue-700 transition-all">
                        Dupliquer
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Question -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm p-8">
                    <div class="flex items-center space-x-3 mb-6">
                        <span class="px-3 py-1 text-sm font-semibold rounded-full
                            @if($question->type === 'choix_unique') bg-blue-100 text-blue-800
                            @elseif($question->type === 'choix_multiple') bg-purple-100 text-purple-800
                            @elseif($question->type === 'vrai_faux') bg-green-100 text-green-800
                            @else bg-orange-100 text-orange-800
                            @endif">
                            {{ ucfirst(str_replace('_', ' ', $question->type)) }}
                        </span>

                        <span class="px-3 py-1 text-sm font-semibold rounded-full
                            @if($question->difficulte === 'facile') bg-green-100 text-green-800
                            @elseif($question->difficulte === 'moyen') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst($question->difficulte) }}
                        </span>

                        @if($question->est_active)
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                Active
                            </span>
                        @else
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">
                                Désactivée
                            </span>
                        @endif
                    </div>

                    <h2 class="text-xl font-bold text-gray-900 mb-6">Énoncé</h2>
                    <p class="text-lg text-gray-900 leading-relaxed mb-8">{{ $question->enonce }}</p>

                    <!-- Réponses -->
                    @if($question->type !== 'reponse_courte')
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Réponses</h3>
                        <div class="space-y-3">
                            @foreach($question->reponses as $reponse)
                                <div class="flex items-center space-x-3 p-4 rounded-lg {{ $reponse->est_correcte ? 'bg-green-50 border-2 border-green-500' : 'bg-gray-50 border border-gray-200' }}">
                                    @if($reponse->est_correcte)
                                        <svg class="h-6 w-6 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    @else
                                        <svg class="h-6 w-6 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="10" />
                                        </svg>
                                    @endif
                                    <span class="{{ $reponse->est_correcte ? 'font-semibold text-green-800' : 'text-gray-700' }}">
                                        {{ $reponse->texte }}
                                        @if($reponse->est_correcte)
                                            <span class="text-sm ml-2">(Bonne réponse)</span>
                                        @endif
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Explication -->
                    @if($question->explication)
                        <div class="mt-8 p-6 bg-blue-50 border-l-4 border-iris-blue rounded-r-lg">
                            <p class="text-sm font-semibold text-blue-900 mb-2">💡 Explication</p>
                            <p class="text-blue-800">{{ $question->explication }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Informations -->
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <h3 class="text-lg font-bold text-iris-black-900 mb-4">Informations</h3>
                    
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-gray-600 mb-1">Matière</p>
                            <p class="font-semibold text-gray-900">{{ $question->matiere->nom }}</p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-600 mb-1">Type</p>
                            <p class="font-semibold text-gray-900">{{ ucfirst(str_replace('_', ' ', $question->type)) }}</p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-600 mb-1">Difficulté</p>
                            <p class="font-semibold text-gray-900">{{ ucfirst($question->difficulte) }}</p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-600 mb-1">Nombre de réponses</p>
                            <p class="font-semibold text-gray-900">{{ $question->reponses->count() }}</p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-600 mb-1">Créée le</p>
                            <p class="font-semibold text-gray-900">{{ $question->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Utilisation -->
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <h3 class="text-lg font-bold text-iris-black-900 mb-4">Utilisation</h3>
                    
                    <div class="text-center py-4">
                        <p class="text-4xl font-bold text-iris-blue">{{ $nbExamens }}</p>
                        <p class="text-sm text-gray-600 mt-1">Examen(s)</p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <h3 class="text-lg font-bold text-iris-black-900 mb-4">Actions</h3>
                    
                    <div class="space-y-2">
                        <form action="{{ route('enseignant.questions.toggle-active', $question->id) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="w-full px-4 py-2 {{ $question->est_active ? 'bg-gray-100 text-gray-700' : 'bg-green-100 text-green-700' }} rounded-lg font-semibold hover:opacity-80 transition-all">
                                {{ $question->est_active ? 'Désactiver' : 'Activer' }}
                            </button>
                        </form>

                        @if($nbExamens === 0)
                            <form action="{{ route('enseignant.questions.destroy', $question->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('Supprimer définitivement cette question ?')"
                                        class="w-full px-4 py-2 bg-red-100 text-red-700 rounded-lg font-semibold hover:bg-red-200 transition-all">
                                    Supprimer
                                </button>
                            </form>
                        @else
                            <div class="p-3 bg-yellow-50 rounded-lg">
                                <p class="text-xs text-yellow-800">
                                    Cette question est utilisée dans {{ $nbExamens }} examen(s) et ne peut pas être supprimée.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection