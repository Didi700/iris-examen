@extends('layouts.app')

@section('title', 'Statistiques')

@push('styles')
<style>
    .kpi-card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .kpi-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
    }
    
    .stat-chart {
        position: relative;
        height: 300px;
    }
    
    .tendance-hausse {
        color: #10B981;
    }
    
    .tendance-baisse {
        color: #EF4444;
    }
    
    .tendance-stable {
        color: #6B7280;
    }
</style>
@endpush

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                    📊 Statistiques
                </h1>
                <p class="text-gray-600 dark:text-gray-400">
                    Vue d'ensemble de vos performances et activités
                </p>
            </div>
            
            <div class="flex items-center space-x-3">
                <button onclick="window.print()" 
                        class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                    🖨️ Imprimer
                </button>
                <button onclick="exportPDF()" 
                        class="px-4 py-2 bg-iris-blue text-white rounded-lg hover:bg-blue-700 transition-colors">
                    📥 Exporter PDF
                </button>
            </div>
        </div>
    </div>

    <!-- 🎯 KPI CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Taux de réussite -->
        <div class="kpi-card bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                    <span class="text-2xl">📊</span>
                </div>
                <div class="text-right">
                    <p class="text-sm opacity-90">Taux de réussite</p>
                    <p class="text-3xl font-bold">{{ $kpis['taux_reussite'] }}%</p>
                </div>
            </div>
            <div class="h-2 bg-white bg-opacity-20 rounded-full overflow-hidden">
                <div class="h-full bg-white rounded-full" style="width: {{ $kpis['taux_reussite'] }}%"></div>
            </div>
        </div>

        <!-- Temps moyen -->
        <div class="kpi-card bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                    <span class="text-2xl">⏱️</span>
                </div>
                <div class="text-right">
                    <p class="text-sm opacity-90">Temps moyen</p>
                    <p class="text-3xl font-bold">{{ $kpis['temps_moyen'] }}</p>
                    <p class="text-xs opacity-75">minutes</p>
                </div>
            </div>
            <p class="text-sm opacity-90">Durée moyenne des examens</p>
        </div>

        <!-- À corriger -->
        <div class="kpi-card bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                    <span class="text-2xl">✅</span>
                </div>
                <div class="text-right">
                    <p class="text-sm opacity-90">À corriger</p>
                    <p class="text-3xl font-bold">{{ $kpis['a_corriger'] }}</p>
                </div>
            </div>
            <a href="{{ route('enseignant.corrections.index') }}" 
               class="text-sm underline opacity-90 hover:opacity-100">
                Voir les corrections →
            </a>
        </div>

        <!-- Tendance -->
        <div class="kpi-card bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                    <span class="text-2xl">
                        @if($kpis['tendance_type'] === 'hausse')
                            ↗️
                        @elseif($kpis['tendance_type'] === 'baisse')
                            ↘️
                        @else
                            ➡️
                        @endif
                    </span>
                </div>
                <div class="text-right">
                    <p class="text-sm opacity-90">Tendance</p>
                    <p class="text-3xl font-bold">
                        @if($kpis['tendance_type'] !== 'stable')
                            {{ $kpis['tendance_type'] === 'hausse' ? '+' : '-' }}{{ $kpis['tendance'] }}%
                        @else
                            Stable
                        @endif
                    </p>
                </div>
            </div>
            <p class="text-sm opacity-90">vs mois dernier</p>
        </div>
    </div>

    <!-- 📊 GRAPHIQUES PRINCIPAUX -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Répartition des examens par statut -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
                Répartition des examens par statut
            </h2>
            <div class="stat-chart">
                <canvas id="statutExamensChart"></canvas>
            </div>
        </div>

        <!-- État des sessions -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
                État des sessions
            </h2>
            <div class="stat-chart">
                <canvas id="sessionChart"></canvas>
            </div>
        </div>
    </div>

    <!-- 📊 MOYENNES PAR MATIÈRE -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">
            📚 Moyennes par matière
        </h2>
        
        @if($moyennesParMatiere->count() > 0)
            <div class="space-y-4">
                @foreach($moyennesParMatiere as $matiere)
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 rounded-full" style="background-color: {{ $matiere['couleur'] }}"></div>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $matiere['matiere'] }}</span>
                                <span class="text-sm text-gray-500 dark:text-gray-400">({{ $matiere['nb_sessions'] }} session(s))</span>
                            </div>
                            <span class="text-lg font-bold" style="color: {{ $matiere['couleur'] }}">
                                {{ $matiere['note_sur_20'] }}/20
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-500" 
                                 style="width: {{ $matiere['pourcentage'] }}%; background-color: {{ $matiere['couleur'] }}">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-6xl mb-4">📊</div>
                <p class="text-gray-500 dark:text-gray-400">Aucune donnée disponible</p>
            </div>
        @endif
    </div>

    <!-- 📈 ÉVOLUTION DES EXAMENS -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
            📈 Évolution des sessions (12 derniers mois)
        </h2>
        <div style="height: 350px;">
            <canvas id="evolutionChart"></canvas>
        </div>
    </div>

    <!-- 📊 STATISTIQUES DÉTAILLÉES -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Questions actives vs inactives -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
                Questions actives vs inactives
            </h2>
            <div class="stat-chart">
                <canvas id="questionsChart"></canvas>
            </div>
        </div>

        <!-- Vue d'ensemble -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
                Vue d'ensemble
            </h2>
            <div class="stat-chart">
                <canvas id="vueEnsembleChart"></canvas>
            </div>
        </div>
    </div>

    <!-- 📝 LISTES -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Examens récents -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                    📝 Examens récents
                </h2>
                <a href="{{ route('enseignant.examens.index') }}" 
                   class="text-sm text-iris-blue hover:text-blue-700 font-medium">
                    Voir tout →
                </a>
            </div>
            
            @forelse($examensRecents as $examen)
                <div class="p-4 rounded-lg border border-gray-200 dark:border-gray-700 mb-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900 dark:text-white">
                                {{ $examen->titre }}
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                📚 {{ $examen->matiere->nom ?? 'N/A' }} • 🎓 {{ $examen->classe->nom ?? 'N/A' }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                📊 {{ $examen->sessions_count }} session(s)
                            </p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            {{ $examen->statut === 'publie' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($examen->statut) }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <p class="text-gray-500 dark:text-gray-400">Aucun examen</p>
                </div>
            @endforelse
        </div>

        <!-- À corriger -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                    ✏️ À corriger
                </h2>
                <a href="{{ route('enseignant.corrections.index') }}" 
                   class="text-sm text-iris-blue hover:text-blue-700 font-medium">
                    Voir tout →
                </a>
            </div>
            
            @forelse($sessionsACorreiger as $session)
                <div class="p-4 rounded-lg border border-orange-200 bg-orange-50 dark:bg-orange-900 dark:bg-opacity-20 dark:border-orange-800 mb-3">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900 dark:text-white">
                                👤 {{ $session->prenom }} {{ $session->nom }}
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                {{ $session->examen_titre }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                📅 Soumis le {{ \Carbon\Carbon::parse($session->date_soumission)->format('d/m/Y à H:i') }}
                            </p>
                        </div>
                        <a href="{{ route('enseignant.corrections.show', $session->id) }}" 
                           class="px-3 py-1 bg-orange-500 text-white rounded-lg text-xs font-semibold hover:bg-orange-600 transition-colors">
                            Corriger
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <p class="text-gray-500 dark:text-gray-400">Aucune correction en attente</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// 📊 DONNÉES PHP VERS JS
const statsBase = @json($statsBase);
const moyennesParMatiere = @json($moyennesParMatiere);
const evolutionExamens = @json($evolutionExamens);

// 🎨 COULEURS
const colors = {
    primary: '#0066CC',
    success: '#10B981',
    warning: '#F59E0B',
    danger: '#EF4444',
    gray: '#6B7280',
    purple: '#8B5CF6',
    blue: '#3B82F6',
};

// 📊 GRAPHIQUE 1 : RÉPARTITION DES EXAMENS PAR STATUT
const statutExamensCtx = document.getElementById('statutExamensChart').getContext('2d');
new Chart(statutExamensCtx, {
    type: 'doughnut',
    data: {
        labels: ['Publiés', 'Brouillons'],
        datasets: [{
            data: [statsBase.examens.publies, statsBase.examens.brouillons],
            backgroundColor: [colors.success, colors.gray],
            borderWidth: 0,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
            }
        }
    }
});

// 📊 GRAPHIQUE 2 : ÉTAT DES SESSIONS
const sessionCtx = document.getElementById('sessionChart').getContext('2d');
new Chart(sessionCtx, {
    type: 'bar',
    data: {
        labels: ['À corriger', 'Terminées'],
        datasets: [{
            label: 'Nombre de sessions',
            data: [statsBase.sessions.a_corriger, statsBase.sessions.terminees],
            backgroundColor: [colors.warning, colors.success],
            borderRadius: 8,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false,
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

// 📊 GRAPHIQUE 3 : QUESTIONS ACTIVES VS INACTIVES
const questionsCtx = document.getElementById('questionsChart').getContext('2d');
new Chart(questionsCtx, {
    type: 'doughnut',
    data: {
        labels: ['Actives', 'Inactives'],
        datasets: [{
            data: [statsBase.questions.actives, statsBase.questions.inactives],
            backgroundColor: [colors.success, colors.danger],
            borderWidth: 0,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
            }
        }
    }
});

// 📊 GRAPHIQUE 4 : VUE D'ENSEMBLE
const vueEnsembleCtx = document.getElementById('vueEnsembleChart').getContext('2d');
new Chart(vueEnsembleCtx, {
    type: 'bar',
    data: {
        labels: ['Classes', 'Examens', 'Questions'],
        datasets: [{
            label: 'Total',
            data: [statsBase.classes, statsBase.examens.total, statsBase.questions.total],
            backgroundColor: [colors.blue, colors.warning, colors.success],
            borderRadius: 8,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false,
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

// 📊 GRAPHIQUE 5 : ÉVOLUTION DES EXAMENS
const evolutionCtx = document.getElementById('evolutionChart').getContext('2d');
new Chart(evolutionCtx, {
    type: 'line',
    data: {
        labels: evolutionExamens.labels,
        datasets: [{
            label: 'Sessions passées',
            data: evolutionExamens.data,
            borderColor: colors.primary,
            backgroundColor: colors.primary + '20',
            tension: 0.4,
            fill: true,
            borderWidth: 3,
            pointRadius: 4,
            pointBackgroundColor: colors.primary,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                position: 'top',
            },
            tooltip: {
                mode: 'index',
                intersect: false,
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

// 📥 FONCTION EXPORT PDF (placeholder)
function exportPDF() {
    alert('Fonction d\'export PDF à venir !');
}
</script>
@endpush