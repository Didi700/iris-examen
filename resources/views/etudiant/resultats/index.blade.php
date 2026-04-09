@extends('layouts.app')

@section('title', 'Mes Résultats')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-iris-black-900">📊 Mes Résultats</h1>
            <p class="text-gray-600 mt-1">Consultez l'historique de vos notes et examens</p>
        </div>

        <!-- ✅ BOUTONS EXPORT PDF -->
        @if($sessions->count() > 0)
            <div class="flex gap-3">
                <!-- Relevé de notes -->
                <a href="{{ route('etudiant.pdf.releve') }}" 
                   class="px-6 py-3 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 transition-all flex items-center shadow-lg">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    📋 Relevé de notes
                </a>
                
                <!-- Bulletin complet -->
                <a href="{{ route('etudiant.pdf.bulletin') }}" 
                   class="px-6 py-3 bg-red-600 text-white rounded-xl font-bold hover:bg-red-700 transition-all flex items-center shadow-lg">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    📊 Bulletin complet
                </a>
            </div>
        @endif
    </div>

    <!-- Statistiques globales -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Total examens -->
        <div class="bg-gradient-to-br from-iris-blue to-blue-700 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold opacity-90">Examens passés</h3>
                <svg class="h-8 w-8 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <p class="text-4xl font-bold">{{ $stats['nb_examens'] }}</p>
        </div>

        <!-- Moyenne générale -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold opacity-90">Moyenne générale</h3>
                <svg class="h-8 w-8 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
            <p class="text-4xl font-bold">{{ $stats['moyenne_generale'] }}/20</p>
            <p class="text-sm opacity-75 mt-1">
                Min: {{ $stats['moins_bonne_note'] }} • Max: {{ $stats['meilleure_note'] }}
            </p>
        </div>

        <!-- Taux de réussite -->
        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold opacity-90">Taux de réussite</h3>
                <svg class="h-8 w-8 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p class="text-4xl font-bold">{{ $stats['taux_reussite'] }}%</p>
            <p class="text-sm opacity-75 mt-1">
                {{ $stats['nb_reussis'] }} réussis • {{ $stats['nb_echoues'] }} échoués
            </p>
        </div>

        <!-- Progression -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold opacity-90">Progression</h3>
                <svg class="h-8 w-8 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
            </div>
            <p class="text-4xl font-bold">
                @if($stats['moyenne_generale'] >= 16)
                    🌟
                @elseif($stats['moyenne_generale'] >= 14)
                    ⭐
                @elseif($stats['moyenne_generale'] >= 12)
                    ✨
                @else
                    💪
                @endif
            </p>
            <p class="text-sm opacity-75 mt-1">
                @if($stats['moyenne_generale'] >= 16)
                    Excellent !
                @elseif($stats['moyenne_generale'] >= 14)
                    Très bien !
                @elseif($stats['moyenne_generale'] >= 12)
                    Bien !
                @else
                    Continue !
                @endif
            </p>
        </div>
    </div>

    <!-- Liste des résultats -->
    @if($sessions->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm p-8">
            <h2 class="text-2xl font-bold text-iris-black-900 mb-6">📋 Historique de mes examens</h2>

            <div class="space-y-4">
                @foreach($sessions as $session)
                    @php
                        $noteMax = $session->note_maximale ?? $session->examen->note_totale;
                        $noteSur20 = $noteMax > 0 ? ($session->note_obtenue / $noteMax) * 20 : 0;
                        $estReussi = $session->pourcentage >= $session->examen->seuil_reussite;
                    @endphp

                    <div class="border-2 {{ $estReussi ? 'border-green-200 bg-green-50' : 'border-red-200 bg-red-50' }} rounded-xl p-6 hover:shadow-md transition-all">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <h3 class="text-lg font-bold text-gray-900">
                                        {{ $session->examen->titre }}
                                    </h3>
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $estReussi ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                                        {{ $estReussi ? '✅ Réussi' : '❌ Échoué' }}
                                    </span>
                                </div>

                                <div class="flex items-center space-x-4 text-sm text-gray-600 mb-3">
                                    <span>📚 {{ $session->examen->matiere->nom }}</span>
                                    <span>📅 {{ $session->created_at->format('d/m/Y à H:i') }}</span>
                                    @if($session->statut === 'corrige')
                                        <span class="text-green-600 font-semibold">✓ Corrigé</span>
                                    @else
                                        <span class="text-orange-600 font-semibold">⏳ En attente de correction</span>
                                    @endif
                                </div>

                                <div class="grid grid-cols-3 gap-4">
                                    <div>
                                        <p class="text-xs text-gray-600">Note obtenue</p>
                                        <p class="text-2xl font-bold {{ $estReussi ? 'text-green-600' : 'text-red-600' }}">
                                            {{ number_format($noteSur20, 2) }}/20
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-600">Pourcentage</p>
                                        <p class="text-2xl font-bold text-gray-900">{{ round($session->pourcentage) }}%</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-600">Points</p>
                                        <p class="text-2xl font-bold text-iris-blue">
                                            {{ $session->note_obtenue }}/{{ $noteMax }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="ml-6 flex flex-col space-y-2">
                                <a href="{{ route('etudiant.resultats.show', $session->id) }}" 
                                   class="px-4 py-2 bg-iris-yellow text-iris-black-900 rounded-lg font-semibold hover:bg-yellow-500 transition-all text-center">
                                    📄 Voir ma copie
                                </a>
                                @if($session->statut === 'corrige')
                                    <!-- ✅ BOUTON CORRECTION PDF -->
                                    <a href="{{ route('etudiant.pdf.correction', $session->id) }}" 
                                       class="px-4 py-2 bg-purple-600 text-white rounded-lg font-semibold hover:bg-purple-700 transition-all text-center">
                                        ✏️ Correction PDF
                                    </a>
                                    
                                    <!-- ✅ BOUTON CERTIFICAT (si réussi) -->
                                    @if($estReussi)
                                        <a href="{{ route('etudiant.pdf.certificat', $session->id) }}" 
                                           class="px-4 py-2 bg-yellow-600 text-white rounded-lg font-semibold hover:bg-yellow-700 transition-all text-center">
                                            🏆 Certificat
                                        </a>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Résultats par matière -->
        <div class="bg-white rounded-2xl shadow-sm p-8">
            <h2 class="text-2xl font-bold text-iris-black-900 mb-6">📚 Résultats par matière</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($sessionsParMatiere as $matiere => $sessionsMat)
                    @php
                        $moyenneMatiere = $sessionsMat->map(function($s) {
                            $noteMax = $s->note_maximale ?? $s->examen->note_totale;
                            return $noteMax > 0 ? ($s->note_obtenue / $noteMax) * 20 : 0;
                        })->avg();
                    @endphp

                    <div class="border-2 border-gray-200 rounded-xl p-6">
                        <h3 class="font-bold text-lg text-gray-900 mb-3">{{ $matiere }}</h3>
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Moyenne</p>
                                <p class="text-3xl font-bold text-iris-blue">{{ number_format($moyenneMatiere, 2) }}/20</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-600">Examens</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $sessionsMat->count() }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <!-- Aucun résultat -->
        <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
            <svg class="h-24 w-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Aucun résultat disponible</h3>
            <p class="text-gray-600 mb-4">Vous n'avez pas encore passé d'examen.</p>
            <a href="{{ route('etudiant.examens.index') }}" 
               class="inline-flex items-center px-6 py-3 bg-iris-yellow text-iris-black-900 rounded-lg font-bold hover:bg-yellow-500 transition-all">
                📝 Voir les examens disponibles
            </a>
        </div>
    @endif
</div>
@endsection