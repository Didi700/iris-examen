@extends('layouts.app')

@section('title', 'Tableau de bord Enseignant')

@section('content')
    <div class="space-y-6">
        <!-- En-tête -->
        <div class="bg-gradient-to-r from-iris-blue to-blue-600 rounded-2xl shadow-lg p-8 text-white">
            <h1 class="text-3xl font-bold mb-2">Bienvenue, {{ auth()->user()->prenom }} ! 👋</h1>
            <p class="text-blue-100 text-lg">Tableau de bord enseignant</p>
        </div>

        <!-- Statistiques principales -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Mes classes -->
            <div class="bg-gradient-to-br from-iris-blue to-blue-600 rounded-2xl shadow-lg p-6 text-white hover:shadow-xl transition-all transform hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-100">Mes classes</p>
                        <p class="text-4xl font-bold mt-2">{{ $stats['nb_classes'] }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Examens créés -->
            <div class="bg-gradient-to-br from-iris-yellow to-yellow-500 rounded-2xl shadow-lg p-6 text-white hover:shadow-xl transition-all transform hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-yellow-100">Examens créés</p>
                        <p class="text-4xl font-bold mt-2">{{ $stats['total_examens'] }}</p>
                        <p class="text-xs text-yellow-100 mt-1">{{ $stats['examens_publies'] }} publié(s)</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Questions créées -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-lg p-6 text-white hover:shadow-xl transition-all transform hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-100">Questions créées</p>
                        <p class="text-4xl font-bold mt-2">{{ $stats['total_questions'] }}</p>
                        <p class="text-xs text-green-100 mt-1">{{ $stats['questions_actives'] }} active(s)</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- À corriger -->
            <div class="bg-gradient-to-br from-red-500 to-red-700 rounded-2xl shadow-lg p-6 text-white hover:shadow-xl transition-all transform hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-red-100">À corriger</p>
                        <p class="text-4xl font-bold mt-2">{{ $stats['sessions_a_corriger'] }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques secondaires -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow-sm p-6 border-2 border-gray-100 hover:border-green-300 transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Examens publiés</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['examens_publies'] }}</p>
                    </div>
                    <div class="bg-green-100 rounded-full p-3">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 border-2 border-gray-100 hover:border-gray-300 transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Brouillons</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['examens_brouillons'] }}</p>
                    </div>
                    <div class="bg-gray-100 rounded-full p-3">
                        <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 border-2 border-gray-100 hover:border-blue-300 transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Sessions terminées</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['sessions_terminees'] }}</p>
                    </div>
                    <div class="bg-blue-100 rounded-full p-3">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- 📊 GRAPHIQUES -->
        <div class="bg-white rounded-2xl shadow-lg p-8 border-2 border-gray-100">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                <span class="text-3xl mr-3">📊</span>
                Statistiques visuelles
            </h2>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Graphique 1 : Répartition des examens -->
                <div>
                    <h3 class="font-bold text-gray-700 mb-4 text-center">Répartition des examens par statut</h3>
                    <canvas id="examensChart" class="max-h-64"></canvas>
                </div>

                <!-- Graphique 2 : Sessions -->
                <div>
                    <h3 class="font-bold text-gray-700 mb-4 text-center">État des sessions</h3>
                    <canvas id="sessionsChart" class="max-h-64"></canvas>
                </div>

                <!-- Graphique 3 : Questions -->
                <div>
                    <h3 class="font-bold text-gray-700 mb-4 text-center">Questions actives vs inactives</h3>
                    <canvas id="questionsChart" class="max-h-64"></canvas>
                </div>

                <!-- Graphique 4 : Activité -->
                <div>
                    <h3 class="font-bold text-gray-700 mb-4 text-center">Vue d'ensemble</h3>
                    <canvas id="activiteChart" class="max-h-64"></canvas>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Examens récents -->
            <div class="bg-white rounded-2xl shadow-sm p-8 border-2 border-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-iris-black-900">📝 Examens récents</h2>
                    <a href="{{ route('enseignant.examens.index') }}" 
                       class="text-iris-blue hover:text-blue-700 text-sm font-semibold">
                        Voir tout →
                    </a>
                </div>

                @if($examensRecents->count() > 0)
                    <div class="space-y-3">
                        @foreach($examensRecents as $examen)
                            <a href="{{ route('enseignant.examens.show', $examen->id) }}" 
                               class="block p-4 border-2 border-gray-200 rounded-xl hover:border-iris-yellow hover:shadow-md transition-all">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <h3 class="font-bold text-gray-900">{{ $examen->titre }}</h3>
                                        <p class="text-sm text-gray-600">📚 {{ $examen->matiere->nom }} • 👥 {{ $examen->classe->nom }}</p>
                                        <div class="flex items-center space-x-3 mt-2 text-xs text-gray-500">
                                            <span>❓ {{ $examen->questions_count }} questions</span>
                                            <span>•</span>
                                            <span>✏️ {{ $examen->sessions_count }} sessions</span>
                                        </div>
                                    </div>
                                    <span class="px-3 py-1 text-xs font-bold rounded-full ml-4
                                        @if($examen->statut === 'brouillon') bg-gray-200 text-gray-800
                                        @elseif($examen->statut === 'publie') bg-green-100 text-green-800
                                        @else bg-blue-100 text-blue-800
                                        @endif">
                                        {{ $examen->statut === 'brouillon' ? '📝 Brouillon' : '✅ Publié' }}
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="text-gray-500 mb-4">Aucun examen créé</p>
                        <a href="{{ route('enseignant.examens.create') }}" 
                           class="inline-block px-6 py-3 bg-iris-yellow text-iris-black-900 rounded-xl font-bold hover:bg-iris-yellow-600 transition-all shadow-lg">
                            + Créer un examen
                        </a>
                    </div>
                @endif
            </div>

            <!-- Sessions à corriger -->
            <div class="bg-white rounded-2xl shadow-sm p-8 border-2 border-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-iris-black-900">✏️ À corriger</h2>
                    <a href="{{ route('enseignant.corrections.index') }}" 
                       class="text-iris-blue hover:text-blue-700 text-sm font-semibold">
                        Voir tout →
                    </a>
                </div>

                @if($sessionsACorriger->count() > 0)
                    <div class="space-y-3">
                        @foreach($sessionsACorriger->take(5) as $session)
                            <a href="{{ route('enseignant.corrections.show', $session->id) }}" 
                               class="block p-4 border-2 border-red-200 rounded-xl hover:border-red-400 hover:bg-red-50 transition-all">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <h3 class="font-bold text-gray-900">👤 {{ $session->etudiant->prenom }} {{ $session->etudiant->nom }}</h3>
                                        <p class="text-sm text-gray-600">{{ $session->examen->titre }}</p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            ⏰ Soumis le {{ $session->date_soumission ? $session->date_soumission->format('d/m/Y à H:i') : ($session->updated_at ? $session->updated_at->format('d/m/Y à H:i') : 'N/A') }}
                                        </p>
                                    </div>
                                    <span class="px-3 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800 ml-4">
                                        À corriger
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-gray-500">Aucune copie à corriger</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Examens en cours et à venir -->
        @if($examensEnCours->count() > 0 || $examensAVenir->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @if($examensEnCours->count() > 0)
                    <div class="bg-white rounded-2xl shadow-sm p-8 border-2 border-green-200">
                        <h2 class="text-xl font-bold text-iris-black-900 mb-6">🟢 Examens en cours</h2>
                        <div class="space-y-3">
                            @foreach($examensEnCours as $examen)
                                <div class="p-4 bg-green-50 border-2 border-green-200 rounded-xl">
                                    <h3 class="font-bold text-gray-900">{{ $examen->titre }}</h3>
                                    <p class="text-sm text-gray-600">📚 {{ $examen->matiere->nom }}</p>
                                    <div class="flex items-center justify-between mt-2">
                                        <p class="text-xs text-gray-500">
                                            ⏰ Se termine le {{ $examen->date_fin->format('d/m/Y à H:i') }}
                                        </p>
                                        <span class="text-xs font-bold text-green-700">
                                            {{ $examen->sessions_count }} sessions
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($examensAVenir->count() > 0)
                    <div class="bg-white rounded-2xl shadow-sm p-8 border-2 border-blue-200">
                        <h2 class="text-xl font-bold text-iris-black-900 mb-6">🔵 Examens à venir</h2>
                        <div class="space-y-3">
                            @foreach($examensAVenir as $examen)
                                <div class="p-4 bg-blue-50 border-2 border-blue-200 rounded-xl">
                                    <h3 class="font-bold text-gray-900">{{ $examen->titre }}</h3>
                                    <p class="text-sm text-gray-600">📚 {{ $examen->matiere->nom }}</p>
                                    <p class="text-xs text-gray-500 mt-2">
                                        🚀 Démarre le {{ $examen->date_debut->format('d/m/Y à H:i') }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- Mes classes et matières -->
        @if($classes->count() > 0 || $matieres->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @if($classes->count() > 0)
                    <div class="bg-white rounded-2xl shadow-sm p-8 border-2 border-gray-100">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-xl font-bold text-iris-black-900">🎓 Mes classes</h2>
                            @if(Route::has('enseignant.classes.index'))
                                <a href="{{ route('enseignant.classes.index') }}" 
                                   class="text-iris-blue hover:text-blue-700 text-sm font-semibold">
                                    Voir tout →
                                </a>
                            @endif
                        </div>
                        <div class="space-y-2">
                            @foreach($classes->take(5) as $classe)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl border-2 border-gray-100 hover:border-iris-blue transition-all">
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $classe->nom }}</p>
                                        <p class="text-xs text-gray-600">{{ $classe->code }}</p>
                                    </div>
                                    <span class="px-3 py-1 text-xs font-bold rounded-full bg-iris-blue text-white">
                                        {{ $classe->niveau }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($matieres->count() > 0)
                    <div class="bg-white rounded-2xl shadow-sm p-8 border-2 border-gray-100">
                        <h2 class="text-xl font-bold text-iris-black-900 mb-6">📚 Mes matières</h2>
                        <div class="space-y-2">
                            @foreach($matieres->take(5) as $matiere)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl border-2 border-gray-100 hover:border-iris-yellow transition-all">
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $matiere->nom }}</p>
                                        <p class="text-xs text-gray-600">{{ $matiere->code }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Configuration des couleurs
    const colors = {
        blue: '#0066CC',
        yellow: '#FDB913',
        green: '#10B981',
        red: '#EF4444',
        gray: '#6B7280',
        purple: '#7C3AED',
        orange: '#F59E0B'
    };

    // Graphique 1 : Examens par statut
    const examensCtx = document.getElementById('examensChart').getContext('2d');
    new Chart(examensCtx, {
        type: 'doughnut',
        data: {
            labels: ['Publiés', 'Brouillons'],
            datasets: [{
                data: [{{ $stats['examens_publies'] }}, {{ $stats['examens_brouillons'] }}],
                backgroundColor: [colors.green, colors.gray],
                borderWidth: 3,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        font: {
                            size: 12,
                            weight: 'bold'
                        }
                    }
                }
            }
        }
    });

    // Graphique 2 : Sessions
    const sessionsCtx = document.getElementById('sessionsChart').getContext('2d');
    new Chart(sessionsCtx, {
        type: 'bar',
        data: {
            labels: ['À corriger', 'Terminées'],
            datasets: [{
                label: 'Nombre de sessions',
                data: [{{ $stats['sessions_a_corriger'] }}, {{ $stats['sessions_terminees'] }}],
                backgroundColor: [colors.orange, colors.green],
                borderWidth: 0,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        font: {
                            weight: 'bold'
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            weight: 'bold'
                        }
                    }
                }
            }
        }
    });

    // Graphique 3 : Questions
    const questionsCtx = document.getElementById('questionsChart').getContext('2d');
    new Chart(questionsCtx, {
        type: 'pie',
        data: {
            labels: ['Actives', 'Inactives'],
            datasets: [{
                data: [
                    {{ $stats['questions_actives'] }}, 
                    {{ $stats['total_questions'] - $stats['questions_actives'] }}
                ],
                backgroundColor: [colors.green, colors.red],
                borderWidth: 3,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        font: {
                            size: 12,
                            weight: 'bold'
                        }
                    }
                }
            }
        }
    });

    // Graphique 4 : Vue d'ensemble
    const activiteCtx = document.getElementById('activiteChart').getContext('2d');
    new Chart(activiteCtx, {
        type: 'bar',
        data: {
            labels: ['Classes', 'Examens', 'Questions'],
            datasets: [{
                label: 'Total',
                data: [
                    {{ $stats['nb_classes'] }},
                    {{ $stats['total_examens'] }},
                    {{ $stats['total_questions'] }}
                ],
                backgroundColor: [colors.blue, colors.yellow, colors.green],
                borderWidth: 0,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        font: {
                            weight: 'bold'
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            weight: 'bold'
                        }
                    }
                }
            }
        }
    });
</script>
@endpush