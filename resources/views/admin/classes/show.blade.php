@extends('layouts.app')

@section('title', 'Détails de la classe')

@section('content')
    <div class="max-w-6xl mx-auto space-y-6">
        <!-- En-tête -->
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.classes.index') }}" 
                   class="text-gray-600 hover:text-iris-yellow">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-iris-black-900">{{ $classe->nom }}</h1>
                    <p class="text-gray-600 mt-1">{{ $classe->code }} - {{ $classe->niveau }}</p>
                </div>
            </div>
            <a href="{{ route('admin.classes.edit', $classe->id) }}" 
               class="px-6 py-3 bg-iris-yellow text-iris-black-900 rounded-lg font-semibold hover:bg-iris-yellow-600 transition-all">
                Modifier
            </a>
        </div>

        <!-- Informations générales -->
        <div class="bg-white rounded-2xl shadow-sm p-8">
            <h3 class="text-lg font-semibold text-iris-black-900 mb-4">Informations générales</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <p class="text-sm text-gray-600">Année scolaire</p>
                    <p class="text-base font-medium text-iris-black-900">{{ $classe->annee_scolaire }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-600">Effectif</p>
                    <p class="text-base font-medium text-iris-black-900">
                        {{ $classe->effectif_actuel }} / {{ $classe->effectif_max }} étudiants
                        <span class="text-sm text-gray-500">({{ $classe->pourcentageRemplissage() }}%)</span>
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-600">Statut</p>
                    <p class="text-base font-medium">
                        <span class="px-3 py-1 text-xs font-semibold rounded-full
                            @if($classe->statut === 'active') bg-green-100 text-green-800
                            @elseif($classe->statut === 'inactive') bg-gray-100 text-gray-800
                            @else bg-yellow-100 text-yellow-800
                            @endif">
                            {{ ucfirst($classe->statut) }}
                        </span>
                    </p>
                </div>

                @if($classe->date_debut)
                    <div>
                        <p class="text-sm text-gray-600">Date de début</p>
                        <p class="text-base font-medium text-iris-black-900">{{ \Illuminate\Support\Carbon::parse($classe->date_debut)->format('d/m/Y') }}</p>
                    </div>
                @endif

                @if($classe->date_fin)
                    <div>
                        <p class="text-sm text-gray-600">Date de fin</p>
                        <p class="text-base font-medium text-iris-black-900">{{ \Illuminate\Support\Carbon::parse($classe->date_fin)->format('d/m/Y') }}</p>
                    </div>
                @endif

                <div>
                    <p class="text-sm text-gray-600">Régimes acceptés</p>
                    <div class="flex flex-wrap gap-2 mt-1">
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
                </div>
            </div>

            @if($classe->description)
                <div class="mt-6 pt-6 border-t">
                    <p class="text-sm text-gray-600 mb-2">Description</p>
                    <p class="text-base text-gray-900">{{ $classe->description }}</p>
                </div>
            @endif
        </div>

        <!-- Statistiques des étudiants -->
        <div class="bg-white rounded-2xl shadow-sm p-8">
            <h3 class="text-lg font-semibold text-iris-black-900 mb-4">Répartition des étudiants</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="p-4 bg-iris-blue bg-opacity-10 rounded-lg">
                    <p class="text-sm text-gray-600">Initial</p>
                    <p class="text-3xl font-bold text-iris-blue">{{ $classe->nb_etudiants_initial }}</p>
                </div>

                <div class="p-4 bg-iris-yellow bg-opacity-10 rounded-lg">
                    <p class="text-sm text-gray-600">Alternance</p>
                    <p class="text-3xl font-bold text-iris-yellow-700">{{ $classe->nb_etudiants_alternance }}</p>
                </div>

                <div class="p-4 bg-iris-green bg-opacity-10 rounded-lg">
                    <p class="text-sm text-gray-600">Formation continue</p>
                    <p class="text-3xl font-bold text-iris-green">{{ $classe->nb_etudiants_formation_continue }}</p>
                </div>
            </div>
        </div>

        <!-- Liste des étudiants -->
        @if($classe->etudiants->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm p-8">
                <div class="mb-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-iris-black-900">
                            Étudiants inscrits ({{ $classe->etudiants->count() }})
                        </h3>
                        <div class="flex gap-3">
                            <a href="{{ route('admin.affectations.create', $classe->id) }}" 
                               class="px-6 py-2.5 bg-yellow-500 text-white text-sm rounded-lg font-bold hover:bg-yellow-600 transition-all shadow">
                                + Affecter un étudiant
                            </a>
                            <a href="{{ route('admin.affectations.masse', $classe->id) }}" 
                               class="px-6 py-2.5 bg-blue-600 text-white text-sm rounded-lg font-bold hover:bg-blue-700 transition-all shadow">
                                👥 Affectation en masse
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-2">
                    @foreach($classe->etudiants as $etudiant)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-iris-yellow bg-opacity-20 rounded-full flex items-center justify-center flex-shrink-0">
                                    <span class="text-iris-yellow-700 font-semibold text-sm">
                                        {{ substr($etudiant->prenom, 0, 1) }}{{ substr($etudiant->nom, 0, 1) }}
                                    </span>
                                </div>
                                <div>
                                    <p class="font-medium text-iris-black-900">{{ $etudiant->prenom }} {{ $etudiant->nom }}</p>
                                    <p class="text-sm text-gray-600">
                                        {{ $etudiant->matricule }} - {{ $etudiant->email }}
                                        @if($etudiant->regime === 'alternance' && $etudiant->entreprise)
                                            <br>
                                            <span class="text-xs text-gray-500">{{ $etudiant->entreprise }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full
                                    @if($etudiant->regime === 'initial') bg-iris-blue bg-opacity-20 text-iris-blue
                                    @elseif($etudiant->regime === 'alternance') bg-iris-yellow bg-opacity-20 text-iris-yellow-700
                                    @else bg-iris-green bg-opacity-20 text-iris-green
                                    @endif">
                                    {{ ucfirst($etudiant->regime) }}
                                </span>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full
                                    @if($etudiant->statut === 'inscrit') bg-green-100 text-green-800
                                    @elseif($etudiant->statut === 'en_attente') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($etudiant->statut) }}
                                </span>
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.affectations.edit', [$classe->id, $etudiant->id]) }}" 
                                       class="text-iris-yellow-700 hover:text-iris-yellow-800 text-sm font-medium"
                                       title="Modifier">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form method="POST" 
                                          action="{{ route('admin.affectations.destroy', [$classe->id, $etudiant->id]) }}" 
                                          class="inline"
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir retirer cet étudiant de la classe ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-iris-red hover:text-red-700 text-sm font-medium"
                                                title="Retirer">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <p class="mt-4 text-gray-600 mb-6">Aucun étudiant inscrit dans cette classe.</p>
                <div class="flex justify-center gap-4">
                    <a href="{{ route('admin.affectations.create', $classe->id) }}" 
                       class="px-8 py-3 bg-yellow-500 text-white rounded-lg font-bold hover:bg-yellow-600 transition-all shadow-lg">
                        + Affecter un étudiant
                    </a>
                    <a href="{{ route('admin.affectations.masse', $classe->id) }}" 
                       class="px-8 py-3 bg-blue-600 text-white rounded-lg font-bold hover:bg-blue-700 transition-all shadow-lg">
                        👥 Affectation en masse
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection