@extends('layouts.app')

@section('title', 'Mes examens')

@section('content')
    <div class="space-y-6">
        <!-- En-tête -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-iris-black-900">Mes examens</h1>
                <p class="text-gray-600 mt-1">Tous vos examens disponibles et passés</p>
            </div>
        </div>

        <!-- Filtres -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <form method="GET" action="{{ route('etudiant.examens.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Recherche -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Recherche</label>
                        <input type="text" 
                               name="search" 
                               id="search" 
                               value="{{ request('search') }}"
                               placeholder="Titre de l'examen..."
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iris-yellow focus:border-transparent">
                    </div>

                    <!-- Matière -->
                    <div>
                        <label for="matiere_id" class="block text-sm font-medium text-gray-700 mb-2">Matière</label>
                        <select name="matiere_id" id="matiere_id" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iris-yellow focus:border-transparent">
                            <option value="">Toutes les matières</option>
                            @foreach($matieres as $matiere)
                                <option value="{{ $matiere->id }}" {{ request('matiere_id') == $matiere->id ? 'selected' : '' }}>
                                    {{ $matiere->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Statut -->
                    <div>
                        <label for="statut" class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                        <select name="statut" id="statut" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iris-yellow focus:border-transparent">
                            <option value="">Tous les statuts</option>
                            <option value="disponible" {{ request('statut') === 'disponible' ? 'selected' : '' }}>Disponibles</option>
                            <option value="en_cours" {{ request('statut') === 'en_cours' ? 'selected' : '' }}>En cours</option>
                            <option value="termine" {{ request('statut') === 'termine' ? 'selected' : '' }}>Terminés</option>
                            <option value="a_venir" {{ request('statut') === 'a_venir' ? 'selected' : '' }}>À venir</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center space-x-2">
                    <button type="submit" 
                            class="px-6 py-2 bg-iris-blue text-white rounded-lg font-semibold hover:bg-blue-700 transition-all">
                        Filtrer
                    </button>
                    <a href="{{ route('etudiant.examens.index') }}" 
                       class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition-all">
                        Réinitialiser
                    </a>
                </div>
            </form>
        </div>

        <!-- Liste des examens -->
        <div class="bg-white rounded-2xl shadow-sm p-8">
            @if($examens->count() > 0)
                <div class="space-y-4">
                    @foreach($examens as $examen)
                        @php
                            $session = $examen->sessions->where('etudiant_id', auth()->user()->etudiant->id)->first();
                            $maintenant = now();
                            $estDisponible = $maintenant->gte($examen->date_debut) && $maintenant->lte($examen->date_fin);
                            $estAVenir = $maintenant->lt($examen->date_debut);
                            $estTermine = $maintenant->gt($examen->date_fin);
                            
                            if ($session) {
                                if ($session->statut === 'en_cours') {
                                    $statutBadge = 'en_cours';
                                    $peutPasser = true;
                                } elseif (in_array($session->statut, ['soumis', 'corrige', 'termine'])) {
                                    $statutBadge = 'termine';
                                    $peutPasser = $session->numero_tentative < $examen->nombre_tentatives_max && $estDisponible;
                                } else {
                                    $statutBadge = 'autre';
                                    $peutPasser = false;
                                }
                            } else {
                                if ($estDisponible) {
                                    $statutBadge = 'disponible';
                                    $peutPasser = true;
                                } elseif ($estAVenir) {
                                    $statutBadge = 'a_venir';
                                    $peutPasser = false;
                                } else {
                                    $statutBadge = 'expire';
                                    $peutPasser = false;
                                }
                            }
                        @endphp

                        <div class="border border-gray-200 rounded-xl p-6 hover:border-iris-yellow hover:shadow-md transition-all">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <h3 class="text-xl font-bold text-gray-900">{{ $examen->titre }}</h3>
                                        
                                        @if($examen->type_examen === 'document')
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                                PDF
                                            </span>
                                        @else
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-iris-blue bg-opacity-20 text-iris-blue">
                                                En ligne
                                            </span>
                                        @endif

                                        @if($statutBadge === 'en_cours')
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                                                En cours
                                            </span>
                                        @elseif($statutBadge === 'termine')
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                Terminé
                                            </span>
                                        @elseif($statutBadge === 'disponible')
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                Disponible
                                            </span>
                                        @elseif($statutBadge === 'a_venir')
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                À venir
                                            </span>
                                        @else
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                Expiré
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <p class="text-gray-600 mb-3">{{ $examen->matiere->nom }}</p>

                                    @if($examen->description)
                                        <p class="text-sm text-gray-700 mb-3">{{ Str::limit($examen->description, 150) }}</p>
                                    @endif

                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-3">
                                        <div class="flex items-center text-sm text-gray-600">
                                            <svg class="h-5 w-5 mr-2 text-iris-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <div>
                                                <p class="font-semibold">{{ $examen->duree_minutes }} min</p>
                                                <p class="text-xs text-gray-500">Durée</p>
                                            </div>
                                        </div>

                                        <div class="flex items-center text-sm text-gray-600">
                                            <svg class="h-5 w-5 mr-2 text-iris-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <div>
                                                <p class="font-semibold">{{ $examen->questions_count }}</p>
                                                <p class="text-xs text-gray-500">Questions</p>
                                            </div>
                                        </div>

                                        <div class="flex items-center text-sm text-gray-600">
                                            <svg class="h-5 w-5 mr-2 text-iris-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                            </svg>
                                            <div>
                                                <p class="font-semibold">{{ $examen->note_totale }} pts</p>
                                                <p class="text-xs text-gray-500">Note</p>
                                            </div>
                                        </div>

                                        <div class="flex items-center text-sm text-gray-600">
                                            <svg class="h-5 w-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <div>
                                                <p class="font-semibold">{{ $examen->date_fin->format('d/m') }}</p>
                                                <p class="text-xs text-gray-500">Date limite</p>
                                            </div>
                                        </div>
                                    </div>

                                    @if($session && $session->note_obtenue !== null)
                                        <div class="p-3 bg-gray-50 rounded-lg">
                                            <p class="text-sm text-gray-600">Votre résultat :</p>
                                            <p class="text-lg font-bold {{ $session->estReussi() ? 'text-green-600' : 'text-red-600' }}">
                                                {{ number_format($session->note_obtenue, 1) }}/{{ $session->note_maximale ?? $examen->note_totale }}
                                                <span class="text-sm">({{ $session->estReussi() ? 'Réussi ✓' : 'Échoué ✗' }})</span>
                                            </p>
                                        </div>
                                    @endif
                                </div>

                                <div class="ml-6 flex flex-col space-y-2">
                                    @if($peutPasser)
                                        @if($session && $session->statut === 'en_cours')
                                            <a href="{{ route('etudiant.examens.passer', $session->id) }}" 
                                               class="px-6 py-3 bg-orange-500 text-white rounded-lg font-semibold hover:bg-orange-600 transition-all text-center">
                                                Reprendre
                                            </a>
                                        @else
                                            <a href="{{ route('etudiant.examens.show', $examen->id) }}" 
                                               class="px-6 py-3 bg-iris-yellow text-iris-black-900 rounded-lg font-semibold hover:bg-iris-yellow-600 transition-all text-center">
                                                Commencer
                                            </a>
                                        @endif
                                    @else
                                        <a href="{{ route('etudiant.examens.show', $examen->id) }}" 
                                           class="px-6 py-3 bg-iris-blue text-white rounded-lg font-semibold hover:bg-blue-700 transition-all text-center">
                                            Voir détails
                                        </a>
                                    @endif

                                    @if($session && $session->note_obtenue !== null)
                                        <a href="{{ route('etudiant.examens.resultat', $session->id) }}" 
                                           class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition-all text-center">
                                            Voir résultat
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $examens->links() }}
                </div>
            @else
                <div class="text-center py-16">
                    <svg class="mx-auto h-20 w-20 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Aucun examen trouvé</h3>
                    <p class="text-gray-600">Les examens disponibles apparaîtront ici</p>
                </div>
            @endif
        </div>
    </div>
@endsection