@extends('layouts.app')

@section('title', 'Calendrier des examens')

@push('styles')
<style>
    .calendar-day {
        min-height: 100px;
        border: 1px solid #e5e7eb;
    }
    
    .calendar-day:hover {
        background-color: #f9fafb;
    }
    
    .event-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
        margin-bottom: 0.25rem;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .event-badge:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .event-green { background-color: #D1FAE5; color: #065F46; }
    .event-orange { background-color: #FED7AA; color: #9A3412; }
    .event-blue { background-color: #DBEAFE; color: #1E40AF; }
    .event-gray { background-color: #E5E7EB; color: #374151; }
    .event-red { background-color: #FEE2E2; color: #991B1B; }
</style>
@endpush

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                    📅 Calendrier des examens
                </h1>
                <p class="text-gray-600 dark:text-gray-400">
                    {{ $dateDebut->locale('fr')->isoFormat('MMMM YYYY') }}
                </p>
            </div>

            <!-- Navigation -->
            <div class="flex items-center space-x-4">
                <a href="{{ route('etudiant.calendrier', ['mois' => $moisPrecedent->month, 'annee' => $moisPrecedent->year]) }}" 
                   class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                    ← Précédent
                </a>
                
                <a href="{{ route('etudiant.calendrier') }}" 
                   class="px-4 py-2 bg-iris-blue text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Aujourd'hui
                </a>
                
                <a href="{{ route('etudiant.calendrier', ['mois' => $moisSuivant->month, 'annee' => $moisSuivant->year]) }}" 
                   class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                    Suivant →
                </a>
            </div>
        </div>
    </div>

    <!-- Légende -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4">
        <div class="flex items-center justify-center space-x-6 text-sm">
            <div class="flex items-center space-x-2">
                <div class="w-4 h-4 rounded bg-green-200"></div>
                <span class="text-gray-700 dark:text-gray-300">À venir</span>
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-4 h-4 rounded bg-orange-200"></div>
                <span class="text-gray-700 dark:text-gray-300">En cours</span>
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-4 h-4 rounded bg-blue-200"></div>
                <span class="text-gray-700 dark:text-gray-300">Soumis</span>
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-4 h-4 rounded bg-gray-200"></div>
                <span class="text-gray-700 dark:text-gray-300">Terminé</span>
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-4 h-4 rounded bg-red-200"></div>
                <span class="text-gray-700 dark:text-gray-300">Manqué</span>
            </div>
        </div>
    </div>

    <!-- Calendrier -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
        <!-- Jours de la semaine -->
        <div class="grid grid-cols-7 bg-gray-50 dark:bg-gray-700">
            @foreach(['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'] as $jour)
                <div class="p-3 text-center font-semibold text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-600">
                    {{ $jour }}
                </div>
            @endforeach
        </div>

        <!-- Jours du mois -->
        <div class="grid grid-cols-7">
            @php
                $premierJour = $dateDebut->copy()->startOfMonth();
                $jourSemaine = $premierJour->dayOfWeekIso; // 1=Lundi, 7=Dimanche
                
                // Jours du mois précédent
                $joursAvant = $jourSemaine - 1;
                $dateActuelle = $premierJour->copy()->subDays($joursAvant);
                
                // Afficher 6 semaines (42 jours)
                for ($i = 0; $i < 42; $i++) {
                    $estMoisActuel = $dateActuelle->month == $dateDebut->month;
                    $estAujourdhui = $dateActuelle->isToday();
                    
                    // Filtrer les examens pour ce jour
                    $examensDuJour = collect($evenements)->filter(function($event) use ($dateActuelle) {
                        return \Carbon\Carbon::parse($event['date_debut'])->isSameDay($dateActuelle);
                    });
            @endphp
                    <div class="calendar-day p-2 {{ $estMoisActuel ? '' : 'bg-gray-50 dark:bg-gray-900 opacity-50' }} {{ $estAujourdhui ? 'ring-2 ring-iris-blue' : '' }}">
                        <div class="font-semibold text-sm mb-1 {{ $estAujourdhui ? 'text-iris-blue' : 'text-gray-700 dark:text-gray-300' }}">
                            {{ $dateActuelle->day }}
                        </div>
                        
                        @foreach($examensDuJour as $event)
                            <a href="{{ $event['url'] }}" 
                               class="event-badge event-{{ $event['couleur'] }} block"
                               title="{{ $event['titre'] }} - {{ $event['matiere'] }}">
                                <div class="font-medium truncate">{{ $event['matiere'] }}</div>
                                <div class="text-xs truncate">{{ \Carbon\Carbon::parse($event['date_debut'])->format('H:i') }}</div>
                            </a>
                        @endforeach
                    </div>
            @php
                    $dateActuelle->addDay();
                }
            @endphp
        </div>
    </div>

    <!-- Liste des examens du mois -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
            📋 Examens de {{ $dateDebut->locale('fr')->isoFormat('MMMM YYYY') }}
        </h2>

        @if(count($evenements) > 0)
            <div class="space-y-3">
                @foreach(collect($evenements)->sortBy('date_debut') as $event)
                    <a href="{{ $event['url'] }}" 
                       class="block p-4 rounded-lg border-l-4 border-{{ $event['couleur'] }}-500 bg-{{ $event['couleur'] }}-50 dark:bg-{{ $event['couleur'] }}-900 dark:bg-opacity-20 hover:shadow-md transition-all">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 dark:text-white">
                                    {{ $event['titre'] }}
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    📚 {{ $event['matiere'] }} • ⏱️ {{ $event['duree'] }} min
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    📅 {{ \Carbon\Carbon::parse($event['date_debut'])->locale('fr')->isoFormat('dddd D MMMM YYYY [à] HH:mm') }}
                                </p>
                            </div>
                            <div>
                                @if($event['statut'] === 'a_venir')
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">À venir</span>
                                @elseif($event['statut'] === 'en_cours')
                                    <span class="px-3 py-1 bg-orange-100 text-orange-800 rounded-full text-xs font-semibold">En cours</span>
                                @elseif($event['statut'] === 'soumis')
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">Soumis</span>
                                @elseif($event['statut'] === 'termine')
                                    <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-semibold">Terminé</span>
                                @elseif($event['statut'] === 'manque')
                                    <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">Manqué</span>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-6xl mb-4">📅</div>
                <p class="text-gray-500 dark:text-gray-400 text-lg">
                    Aucun examen prévu ce mois-ci
                </p>
            </div>
        @endif
    </div>
</div>
@endsection