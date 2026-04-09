@extends('layouts.app')

@section('title', $examen->titre)

@section('content')
    <div class="space-y-6">
        <!-- En-tête -->
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('etudiant.examens.index') }}" 
                   class="text-gray-600 hover:text-iris-yellow transition-colors">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-iris-black-900">{{ $examen->titre }}</h1>
                    <p class="text-gray-600 mt-1">{{ $examen->matiere->nom }} • {{ $examen->classe->nom }}</p>
                </div>
            </div>

            @if($peutCommencer)
                <form action="{{ route('etudiant.examens.demarrer', $examen->id) }}" 
                      method="POST" 
                      id="form-demarrer-examen">
                    @csrf
                    <button type="button" 
                            onclick="confirmerDemarrage()"
                            class="px-8 py-3 bg-iris-yellow text-iris-black-900 rounded-lg font-bold hover:bg-iris-yellow-600 transition-all shadow-lg">
                        🚀 Commencer l'examen
                    </button>
                </form>
            @elseif($sessionEnCours)
                <a href="{{ route('etudiant.examens.passer', $sessionEnCours->id) }}" 
                   class="px-8 py-3 bg-orange-500 text-white rounded-lg font-bold hover:bg-orange-600 transition-all shadow-lg">
                    ⏱️ Reprendre l'examen
                </a>
            @endif
        </div>

        <!-- Alertes -->
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-6 rounded-r-lg">
                <div class="flex items-start">
                    <svg class="h-6 w-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <p class="text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded-r-lg">
                <div class="flex items-start">
                    <svg class="h-6 w-6 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        @foreach($errors->all() as $error)
                            <p class="text-red-800">{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        @if(!$peutCommencer && !$sessionEnCours)
            @if(now()->lt($examen->date_debut))
                <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-r-lg">
                    <div class="flex items-start">
                        <svg class="h-6 w-6 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <h3 class="text-sm font-semibold text-blue-800 mb-1">Examen à venir</h3>
                            <p class="text-sm text-blue-700">
                                Cet examen sera disponible le {{ $examen->date_debut->format('d/m/Y à H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
            @elseif(now()->gt($examen->date_fin))
                <div class="bg-gray-50 border-l-4 border-gray-500 p-6 rounded-r-lg">
                    <div class="flex items-start">
                        <svg class="h-6 w-6 text-gray-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-800 mb-1">Examen terminé</h3>
                            <p class="text-sm text-gray-700">
                                La période pour passer cet examen est terminée.
                            </p>
                        </div>
                    </div>
                </div>
            @elseif($nombreTentatives >= $examen->nombre_tentatives_max)
                <div class="bg-orange-50 border-l-4 border-orange-500 p-6 rounded-r-lg">
                    <div class="flex items-start">
                        <svg class="h-6 w-6 text-orange-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div>
                            <h3 class="text-sm font-semibold text-orange-800 mb-1">Nombre maximum de tentatives atteint</h3>
                            <p class="text-sm text-orange-700">
                                Vous avez utilisé toutes vos tentatives pour cet examen ({{ $nombreTentatives }}/{{ $examen->nombre_tentatives_max }}).
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Contenu principal -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Description -->
                @if($examen->description)
                    <div class="bg-white rounded-2xl shadow-sm p-8">
                        <h2 class="text-xl font-bold text-iris-black-900 mb-4">Description</h2>
                        <p class="text-gray-700 leading-relaxed">{{ $examen->description }}</p>
                    </div>
                @endif

                <!-- Instructions -->
                @if($examen->instructions)
                    <div class="bg-white rounded-2xl shadow-sm p-8">
                        <h2 class="text-xl font-bold text-iris-black-900 mb-4">Instructions</h2>
                        <div class="bg-blue-50 border-l-4 border-iris-blue p-6 rounded-r-lg">
                            <p class="text-gray-900 whitespace-pre-line">{{ $examen->instructions }}</p>
                        </div>
                    </div>
                @endif

                <!-- Mes tentatives -->
                @if($sessions->count() > 0)
                    <div class="bg-white rounded-2xl shadow-sm p-8">
                        <h2 class="text-xl font-bold text-iris-black-900 mb-6">Mes tentatives</h2>
                        
                        <div class="space-y-3">
                            @foreach($sessions as $index => $sessionItem)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-3 mb-2">
                                                <h3 class="font-semibold text-gray-900">Tentative {{ $sessionItem->numero_tentative }}</h3>
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                                    @if($sessionItem->statut === 'en_cours') bg-orange-100 text-orange-800
                                                    @elseif($sessionItem->statut === 'soumis') bg-yellow-100 text-yellow-800
                                                    @elseif($sessionItem->statut === 'corrige') bg-green-100 text-green-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif">
                                                    {{ $sessionItem->statut_libelle }}
                                                </span>
                                            </div>
                                            <div class="text-sm text-gray-600 space-y-1">
                                                <p>📅 {{ $sessionItem->date_debut->format('d/m/Y à H:i') }}</p>
                                                @if($sessionItem->note_obtenue !== null)
                                                    <p class="font-semibold {{ $sessionItem->estReussi() ? 'text-green-600' : 'text-red-600' }}">
                                                        📊 Note : {{ number_format($sessionItem->note_obtenue, 1) }}/{{ $sessionItem->note_maximale ?? $examen->note_totale }}
                                                        ({{ $sessionItem->estReussi() ? 'Réussi ✓' : 'Échoué ✗' }})
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                        @if($sessionItem->statut === 'en_cours')
                                            <a href="{{ route('etudiant.examens.passer', $sessionItem->id) }}" 
                                               class="px-4 py-2 bg-orange-500 text-white rounded-lg font-semibold hover:bg-orange-600 transition-all">
                                                Reprendre
                                            </a>
                                        @elseif(in_array($sessionItem->statut, ['soumis', 'corrige']) && $sessionItem->note_obtenue !== null)
                                            <a href="{{ route('etudiant.examens.resultat', $sessionItem->id) }}" 
                                               class="px-4 py-2 bg-iris-blue text-white rounded-lg font-semibold hover:bg-blue-700 transition-all">
                                                Voir résultat
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Informations -->
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <h3 class="text-lg font-bold text-iris-black-900 mb-4">Informations</h3>
                    
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-gray-600 mb-1">Type</p>
                            <p class="font-semibold text-gray-900">
                                {{ $examen->type_examen === 'en_ligne' ? 'En ligne' : 'Document PDF' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-600 mb-1">Durée</p>
                            <p class="font-semibold text-gray-900">{{ $examen->duree_minutes }} minutes</p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-600 mb-1">Nombre de questions</p>
                            <p class="font-semibold text-gray-900">{{ $examen->questions->count() }} questions</p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-600 mb-1">Note totale</p>
                            <p class="font-semibold text-gray-900">{{ $examen->note_totale }} points</p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-600 mb-1">Seuil de réussite</p>
                            <p class="font-semibold text-gray-900">{{ $examen->seuil_reussite }}%</p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-600 mb-1">Tentatives autorisées</p>
                            <p class="font-semibold text-gray-900">{{ $nombreTentatives }}/{{ $examen->nombre_tentatives_max }}</p>
                        </div>
                    </div>
                </div>

                <!-- Dates -->
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <h3 class="text-lg font-bold text-iris-black-900 mb-4">Dates</h3>
                    
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-gray-600 mb-1">Début</p>
                            <p class="font-semibold text-gray-900">{{ $examen->date_debut->format('d/m/Y à H:i') }}</p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-600 mb-1">Fin</p>
                            <p class="font-semibold text-gray-900">{{ $examen->date_fin->format('d/m/Y à H:i') }}</p>
                        </div>

                        @if(now()->gte($examen->date_debut) && now()->lte($examen->date_fin))
                            <div class="pt-3 border-t border-gray-200">
                                <div class="bg-green-50 rounded-lg p-3">
                                    <p class="text-sm font-semibold text-green-800">✓ Examen en cours</p>
                                </div>
                            </div>
                        @elseif(now()->lt($examen->date_debut))
                            <div class="pt-3 border-t border-gray-200">
                                <div class="bg-blue-50 rounded-lg p-3">
                                    <p class="text-sm font-semibold text-blue-800">À venir</p>
                                </div>
                            </div>
                        @else
                            <div class="pt-3 border-t border-gray-200">
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <p class="text-sm font-semibold text-gray-800">Terminé</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmerDemarrage() {
            if (confirm('Êtes-vous prêt à commencer cet examen ? Le chronomètre démarrera immédiatement.')) {
                console.log('✅ Confirmation OK - Soumission du formulaire');
                document.getElementById('form-demarrer-examen').submit();
            } else {
                console.log('❌ Annulation');
            }
        }
    </script>
@endsection