@extends('layouts.app')

@section('title', 'Tableau de bord Étudiant')

@section('content')
<div class="space-y-6">
    <!-- En-tête avec salutation -->
    <div class="bg-gradient-to-r from-iris-blue to-blue-600 rounded-2xl shadow-lg p-8 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">
                    👋 Bonjour, {{ auth()->user()->prenom }} !
                </h1>
                <p class="text-blue-100">
                    @php
                        $heure = now()->hour;
                        if ($heure < 12) {
                            echo "Bon courage pour cette matinée d'études !";
                        } elseif ($heure < 18) {
                            echo "Bon après-midi ! Continue comme ça !";
                        } else {
                            echo "Bonsoir ! Encore un peu de révisions ?";
                        }
                    @endphp
                </p>
            </div>
            <div class="hidden md:block">
                <div class="w-24 h-24 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Examens à venir -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border-l-4 border-orange-500 hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Examens à venir</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['examens_a_venir'] }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Examens terminés -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border-l-4 border-green-500 hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Examens terminés</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['examens_termines'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Moyenne générale -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border-l-4 border-iris-blue hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Moyenne générale</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">
                        {{ number_format($stats['moyenne_generale'], 2) }}/20
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-iris-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Taux de réussite -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border-l-4 border-iris-yellow hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Taux de réussite</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">
                        {{ $stats['taux_reussite'] }}%
                    </p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-iris-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Bouton Calendrier -->
    <div class="flex justify-center">
        <a href="{{ route('etudiant.calendrier') }}" 
           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-iris-blue to-blue-600 text-white rounded-xl font-bold hover:shadow-lg transition-all">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            Voir le Calendrier Complet
        </a>
    </div>

    <!-- GRAPHIQUES SECTION -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Graphique d'évolution des notes -->
        @if(count($graphique_evolution['notes']) > 0)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2 text-iris-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
                📈 Évolution de vos notes
            </h3>
            <div class="h-64">
                <canvas id="evolutionChart"></canvas>
            </div>
        </div>
        @endif

        <!-- Graphique par matière (Radar) -->
        @if(count($graphique_matieres['notes']) > 0)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                🎯 Performance par matière
            </h3>
            <div class="h-64">
                <canvas id="matieresChart"></canvas>
            </div>
        </div>
        @endif
    </div>

    <!-- Graphique de comparaison avec la classe -->
    @if(count($graphique_comparaison['labels']) > 0)
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            👥 Comparaison avec la classe
        </h3>
        <div class="h-80">
            <canvas id="comparaisonChart"></canvas>
        </div>
    </div>
    @endif

    <!-- Progression visuelle -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">📈 Ma Progression</h3>
        <div class="space-y-4">
            @if(count($progressionMatieres) > 0)
                @foreach($progressionMatieres as $matiere => $data)
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $matiere }}</span>
                            <span class="text-sm font-bold text-iris-blue">{{ number_format($data['moyenne'], 2) }}/20</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                            <div class="bg-gradient-to-r from-iris-blue to-blue-600 h-3 rounded-full transition-all duration-500"
                                 style="width: {{ ($data['moyenne'] / 20) * 100 }}%">
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $data['nombre_examens'] }} examen(s) passé(s)</p>
                    </div>
                @endforeach
            @else
                <p class="text-gray-500 dark:text-gray-400 text-center py-4">Aucun examen passé pour le moment</p>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Prochains examens -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">📅 Prochains Examens</h3>
                <a href="{{ route('etudiant.examens.index') }}" 
                   class="text-sm text-iris-blue hover:text-blue-700 font-medium">
                    Voir tout →
                </a>
            </div>

            @if(count($prochains_examens) > 0)
                <div class="space-y-3">
                    @foreach($prochains_examens as $examen)
                        <div class="border-l-4 border-orange-500 bg-orange-50 dark:bg-orange-900 p-4 rounded-r-lg hover:shadow-md transition-shadow">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h4 class="font-bold text-gray-900 dark:text-white">{{ $examen->titre }}</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">{{ $examen->matiere->nom }}</p>
                                    <div class="flex items-center space-x-4 mt-2 text-xs text-gray-500 dark:text-gray-400">
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ $examen->date_debut->format('d/m/Y') }}
                                        </span>
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ $examen->duree }} min
                                        </span>
                                    </div>
                                </div>
                                <a href="{{ route('etudiant.examens.show', $examen) }}" 
                                   class="ml-4 px-4 py-2 bg-iris-blue text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                                    Voir
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400">Aucun examen à venir</p>
                </div>
            @endif
        </div>

        <!-- Derniers résultats -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">📊 Derniers Résultats</h3>
                <a href="{{ route('etudiant.resultats.index') }}" 
                   class="text-sm text-iris-blue hover:text-blue-700 font-medium">
                    Voir tout →
                </a>
            </div>

            @if(count($derniers_resultats) > 0)
                <div class="space-y-3">
                    @foreach($derniers_resultats as $session)
                        @php
                            $noteMax = $session->note_maximale ?? $session->examen->note_totale ?? 20;
                            $noteSur20 = $noteMax > 0 ? round(($session->note_obtenue / $noteMax) * 20, 2) : 0;
                        @endphp
                        <div class="border-l-4 {{ $noteSur20 >= 10 ? 'border-green-500 bg-green-50 dark:bg-green-900' : 'border-red-500 bg-red-50 dark:bg-red-900' }} p-4 rounded-r-lg hover:shadow-md transition-shadow">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h4 class="font-bold text-gray-900 dark:text-white">{{ $session->examen->titre }}</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">{{ $session->examen->matiere->nom }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        {{ $session->created_at->format('d/m/Y à H:i') }}
                                    </p>
                                </div>
                                <div class="text-right ml-4">
                                    <p class="text-2xl font-bold {{ $noteSur20 >= 10 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ number_format($noteSur20, 2) }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">/ 20</p>
                                    <a href="{{ route('etudiant.resultats.show', $session) }}" 
                                       class="text-xs text-iris-blue hover:underline mt-1 block">
                                        Détails
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400">Aucun résultat disponible</p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Couleurs du thème
    const isDark = document.documentElement.classList.contains('dark');
    const textColor = isDark ? '#e5e7eb' : '#374151';
    const gridColor = isDark ? '#374151' : '#e5e7eb';

    // Graphique d'évolution
    @if(count($graphique_evolution['notes']) > 0)
    const evolutionCtx = document.getElementById('evolutionChart');
    if (evolutionCtx) {
        new Chart(evolutionCtx, {
            type: 'line',
            data: {
                labels: @json($graphique_evolution['labels']),
                datasets: [
                    {
                        label: 'Note obtenue',
                        data: @json($graphique_evolution['notes']),
                        borderColor: '#0066CC',
                        backgroundColor: 'rgba(0, 102, 204, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    },
                    {
                        label: 'Moyenne cumulative',
                        data: @json($graphique_evolution['moyennes']),
                        borderColor: '#10B981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.4,
                        fill: false,
                        borderDash: [5, 5],
                        pointRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        display: true,
                        labels: { color: textColor }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 20,
                        ticks: { 
                            stepSize: 5,
                            color: textColor
                        },
                        grid: { color: gridColor }
                    },
                    x: {
                        ticks: { color: textColor },
                        grid: { color: gridColor }
                    }
                }
            }
        });
    }
    @endif

    // Graphique par matière (Radar)
    @if(count($graphique_matieres['notes']) > 0)
    const matieresCtx = document.getElementById('matieresChart');
    if (matieresCtx) {
        new Chart(matieresCtx, {
            type: 'radar',
            data: {
                labels: @json($graphique_matieres['labels']),
                datasets: [{
                    label: 'Mes moyennes',
                    data: @json($graphique_matieres['notes']),
                    backgroundColor: 'rgba(0, 102, 204, 0.2)',
                    borderColor: '#0066CC',
                    borderWidth: 2,
                    pointBackgroundColor: '#0066CC',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: '#0066CC'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        display: false
                    }
                },
                scales: {
                    r: {
                        beginAtZero: true,
                        max: 20,
                        ticks: { 
                            stepSize: 5,
                            color: textColor
                        },
                        grid: { color: gridColor },
                        pointLabels: { color: textColor }
                    }
                }
            }
        });
    }
    @endif

    // Graphique de comparaison
    @if(count($graphique_comparaison['labels']) > 0)
    const comparaisonCtx = document.getElementById('comparaisonChart');
    if (comparaisonCtx) {
        new Chart(comparaisonCtx, {
            type: 'bar',
            data: {
                labels: @json($graphique_comparaison['labels']),
                datasets: [
                    {
                        label: 'Ma moyenne',
                        data: @json($graphique_comparaison['mes_moyennes']),
                        backgroundColor: 'rgba(0, 102, 204, 0.8)',
                        borderColor: '#0066CC',
                        borderWidth: 1
                    },
                    {
                        label: 'Moyenne de la classe',
                        data: @json($graphique_comparaison['moyennes_classe']),
                        backgroundColor: 'rgba(253, 185, 19, 0.8)',
                        borderColor: '#FDB913',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        display: true,
                        labels: { color: textColor }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 20,
                        ticks: { 
                            stepSize: 5,
                            color: textColor
                        },
                        grid: { color: gridColor }
                    },
                    x: {
                        ticks: { color: textColor },
                        grid: { color: gridColor }
                    }
                }
            }
        });
    }
    @endif
});
</script>
@endpush
@endsection