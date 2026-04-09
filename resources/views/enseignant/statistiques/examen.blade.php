@extends('layouts.app')

@section('title', 'Statistiques - ' . $examen->titre)

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <a href="{{ route('enseignant.statistiques.index') }}" 
               class="text-gray-600 hover:text-iris-yellow transition-colors">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-iris-black-900">📊 Statistiques détaillées</h1>
                <p class="text-gray-600 mt-1">{{ $examen->titre }}</p>
            </div>
        </div>

        <!-- Bouton export -->
        <a href="{{ route('enseignant.examens.export-pdf', $examen->id) }}" 
           class="px-6 py-3 bg-red-600 text-white rounded-xl font-bold hover:bg-red-700 transition-all flex items-center shadow-lg">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Exporter PDF
        </a>
    </div>

    <!-- Informations de l'examen -->
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div>
                <p class="text-sm text-gray-600">Matière</p>
                <p class="text-lg font-bold text-gray-900">{{ $examen->matiere->nom ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Classe</p>
                <p class="text-lg font-bold text-gray-900">{{ $examen->classe->nom ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Durée</p>
                <p class="text-lg font-bold text-gray-900">{{ $examen->duree }} min</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Note totale</p>
                <p class="text-lg font-bold text-gray-900">{{ $examen->note_totale }} pts</p>
            </div>
        </div>
    </div>

    <!-- KPIs Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Participants -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold opacity-90">Participants</h3>
                <svg class="h-8 w-8 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <p class="text-4xl font-bold">{{ $stats['nb_participants'] }}</p>
        </div>

        <!-- Moyenne -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold opacity-90">Moyenne</h3>
                <svg class="h-8 w-8 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
            <p class="text-4xl font-bold">{{ $stats['moyenne'] }}%</p>
            <p class="text-sm opacity-75 mt-1">
                Min: {{ $stats['note_min'] }}% • Max: {{ $stats['note_max'] }}%
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
                Seuil: {{ $examen->seuil_reussite ?? 50 }}%
            </p>
        </div>

        <!-- Temps moyen -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold opacity-90">Temps moyen</h3>
                <svg class="h-8 w-8 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p class="text-4xl font-bold">{{ $stats['temps_moyen'] }}</p>
            <p class="text-sm opacity-75 mt-1">minutes</p>
        </div>
    </div>

    <!-- Statistiques avancées -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">📈 Statistiques avancées</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Médiane</span>
                    <span class="text-xl font-bold text-iris-blue">{{ $stats['mediane'] }}%</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Écart-type</span>
                    <span class="text-xl font-bold text-iris-blue">{{ $stats['ecart_type'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Note min</span>
                    <span class="text-xl font-bold text-red-600">{{ $stats['note_min'] }}%</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Note max</span>
                    <span class="text-xl font-bold text-green-600">{{ $stats['note_max'] }}%</span>
                </div>
            </div>
        </div>

        <!-- Répartition des notes -->
        <div class="bg-white rounded-2xl shadow-sm p-6 md:col-span-2">
            <h3 class="text-lg font-bold text-gray-900 mb-4">📊 Répartition des notes</h3>
            <div class="space-y-3">
                @foreach($repartitionNotes as $tranche => $nombre)
                    @php
                        $pourcentage = $stats['nb_participants'] > 0 ? ($nombre / $stats['nb_participants']) * 100 : 0;
                        $couleur = match(true) {
                            str_contains($tranche, '90-100') => 'bg-green-500',
                            str_contains($tranche, '80-90') => 'bg-green-400',
                            str_contains($tranche, '70-80') => 'bg-blue-500',
                            str_contains($tranche, '60-70') => 'bg-blue-400',
                            str_contains($tranche, '50-60') => 'bg-yellow-400',
                            str_contains($tranche, '40-50') => 'bg-orange-400',
                            str_contains($tranche, '20-40') => 'bg-orange-500',
                            default => 'bg-red-500',
                        };
                    @endphp
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="font-semibold text-gray-700">{{ $tranche }}%</span>
                            <span class="text-gray-600">{{ $nombre }} étudiant(s) ({{ round($pourcentage, 1) }}%)</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="{{ $couleur }} h-2 rounded-full transition-all duration-500" 
                                 style="width: {{ $pourcentage }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Performance par type de question -->
    @if($performanceParType->isNotEmpty())
        <div class="bg-white rounded-2xl shadow-sm p-8">
            <h2 class="text-2xl font-bold text-iris-black-900 mb-6">📝 Performance par type de question</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($performanceParType as $perf)
                    <div class="border-2 border-gray-200 rounded-xl p-4 hover:border-iris-blue transition-all">
                        <div class="text-center">
                            <p class="text-sm text-gray-600 mb-2">{{ ucfirst(str_replace('_', ' ', $perf['type'])) }}</p>
                            <p class="text-3xl font-bold mb-2" 
                               style="color: {{ $perf['taux_reussite'] >= 70 ? '#10B981' : ($perf['taux_reussite'] >= 50 ? '#F59E0B' : '#EF4444') }}">
                                {{ $perf['taux_reussite'] }}%
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ $perf['nb_questions'] }} question(s) • {{ $perf['nb_reponses'] }} réponse(s)
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Questions les plus difficiles -->
    @if($questionsDifficiles->isNotEmpty())
        <div class="bg-white rounded-2xl shadow-sm p-8">
            <h2 class="text-2xl font-bold text-iris-black-900 mb-6">⚠️ Questions les plus difficiles</h2>
            <div class="space-y-4">
                @foreach($questionsDifficiles as $question)
                    <div class="border-2 border-red-200 rounded-xl p-6 hover:shadow-md transition-all">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        {{ $question['taux_reussite'] }}% de réussite
                                    </span>
                                    <span class="text-sm text-gray-600">
                                        {{ $question['type'] }}
                                    </span>
                                </div>
                                <p class="text-gray-900 font-medium mb-2">{{ $question['enonce'] }}</p>
                                <div class="flex items-center space-x-4 text-sm text-gray-600">
                                    <span>{{ $question['points'] }} points</span>
                                    <span>{{ $question['nb_reponses'] }} réponse(s)</span>
                                </div>
                            </div>
                            <a href="{{ route('enseignant.statistiques.question', $question['id']) }}" 
                               class="px-4 py-2 bg-iris-yellow text-iris-black-900 rounded-lg font-semibold hover:bg-yellow-500 transition-all">
                                Analyser
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Top 10 étudiants -->
    @if($topEtudiants->isNotEmpty())
        <div class="bg-white rounded-2xl shadow-sm p-8">
            <h2 class="text-2xl font-bold text-iris-black-900 mb-6">🏆 Top 10 des meilleurs résultats</h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rang</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Étudiant</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Note</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Pourcentage</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Temps</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($topEtudiants as $index => $session)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <span class="text-2xl font-bold {{ $index < 3 ? 'text-iris-yellow' : 'text-gray-400' }}">
                                        #{{ $index + 1 }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-iris-blue bg-opacity-10 rounded-full flex items-center justify-center">
                                            <span class="text-iris-blue font-bold">
                                                {{ substr($session->etudiant->utilisateur->prenom ?? 'E', 0, 1) }}{{ substr($session->etudiant->utilisateur->nom ?? 'T', 0, 1) }}
                                            </span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $session->etudiant->utilisateur->prenom ?? 'N/A' }} 
                                                {{ $session->etudiant->utilisateur->nom ?? 'N/A' }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $session->etudiant->matricule }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-xl font-bold text-green-600">
                                        {{ number_format($session->note_obtenue, 2) }}/{{ $session->note_maximale ?? $examen->note_totale }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ round($session->pourcentage) }}%
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center text-sm text-gray-600">
                                    {{ round($session->temps_passe_secondes / 60) }} min
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('enseignant.corrections.show', $session->id) }}" 
                                       class="text-iris-blue hover:text-iris-yellow font-semibold">
                                        Voir copie
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Étudiants en difficulté -->
    @if($etudiantsDifficulte->isNotEmpty())
        <div class="bg-white rounded-2xl shadow-sm p-8">
            <h2 class="text-2xl font-bold text-iris-black-900 mb-6">⚠️ Étudiants en difficulté (< {{ $examen->seuil_reussite ?? 50 }}%)</h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Étudiant</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Note</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Pourcentage</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($etudiantsDifficulte as $session)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-red-100 rounded-full flex items-center justify-center">
                                            <span class="text-red-600 font-bold">
                                                {{ substr($session->etudiant->utilisateur->prenom ?? 'E', 0, 1) }}{{ substr($session->etudiant->utilisateur->nom ?? 'T', 0, 1) }}
                                            </span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $session->etudiant->utilisateur->prenom ?? 'N/A' }} 
                                                {{ $session->etudiant->utilisateur->nom ?? 'N/A' }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $session->etudiant->matricule }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-xl font-bold text-red-600">
                                        {{ number_format($session->note_obtenue, 2) }}/{{ $session->note_maximale ?? $examen->note_totale }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                        {{ round($session->pourcentage) }}%
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('enseignant.corrections.show', $session->id) }}" 
                                       class="text-iris-blue hover:text-iris-yellow font-semibold">
                                        Voir copie
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection