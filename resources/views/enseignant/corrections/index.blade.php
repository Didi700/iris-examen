@extends('layouts.app')

@section('title', 'Corrections')

@push('styles')
<style>
    .filter-card {
        transition: all 0.3s ease;
    }
    
    .session-card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .session-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .badge-statut {
        padding: 0.375rem 0.75rem;
        border-radius: 0.5rem;
        font-weight: 600;
        font-size: 0.75rem;
    }
    
    .stat-card {
        transition: transform 0.2s;
    }
    
    .stat-card:hover {
        transform: translateY(-4px);
    }

    .progress-bar {
        transition: width 0.5s ease;
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
                    ✏️ Corrections
                </h1>
                <p class="text-gray-600 dark:text-gray-400">
                    Gérez les corrections des examens de vos étudiants
                </p>
            </div>
            
            <div class="flex items-center space-x-3">
                <a href="{{ route('enseignant.examens.index') }}" 
                   class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                    📚 Mes examens
                </a>
            </div>
        </div>
    </div>

    <!-- 📊 Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- En attente de correction -->
        <div class="stat-card bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90 mb-1">En attente</p>
                    <p class="text-4xl font-bold">{{ $stats['en_attente'] }}</p>
                    <p class="text-xs opacity-75 mt-1">À corriger</p>
                </div>
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <span class="text-3xl">⏳</span>
                </div>
            </div>
        </div>

        <!-- Corrigées (non publiées) -->
        <div class="stat-card bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90 mb-1">Corrigées</p>
                    <p class="text-4xl font-bold">{{ $stats['corrigees'] }}</p>
                    <p class="text-xs opacity-75 mt-1">Non publiées</p>
                </div>
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <span class="text-3xl">✏️</span>
                </div>
            </div>
        </div>

        <!-- Publiées -->
        <div class="stat-card bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90 mb-1">Publiées</p>
                    <p class="text-4xl font-bold">{{ $stats['publiees'] }}</p>
                    <p class="text-xs opacity-75 mt-1">Visibles étudiants</p>
                </div>
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <span class="text-3xl">✅</span>
                </div>
            </div>
        </div>

        <!-- Total -->
        <div class="stat-card bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90 mb-1">Total</p>
                    <p class="text-4xl font-bold">{{ $stats['total'] }}</p>
                    <p class="text-xs opacity-75 mt-1">Sessions</p>
                </div>
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <span class="text-3xl">📊</span>
                </div>
            </div>
        </div>
    </div>

    <!-- 🔍 Filtres -->
    <div class="filter-card bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <form method="GET" action="{{ route('enseignant.corrections.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                
                <!-- Filtre statut -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Statut de correction
                    </label>
                    <select name="statut_correction" 
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-iris-blue focus:ring-iris-blue">
                        <option value="">Tous les statuts</option>
                        <option value="en_attente" {{ request('statut_correction') === 'en_attente' ? 'selected' : '' }}>
                            ⏳ En attente
                        </option>
                        <option value="corrige" {{ request('statut_correction') === 'corrige' ? 'selected' : '' }}>
                            ✏️ Corrigé (non publié)
                        </option>
                        <option value="publie" {{ request('statut_correction') === 'publie' ? 'selected' : '' }}>
                            ✅ Publié
                        </option>
                    </select>
                </div>

                <!-- Filtre examen -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Examen
                    </label>
                    <select name="examen_id" 
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-iris-blue focus:ring-iris-blue">
                        <option value="">Tous les examens</option>
                        @foreach($examens as $examen)
                            <option value="{{ $examen->id }}" {{ request('examen_id') == $examen->id ? 'selected' : '' }}>
                                {{ $examen->titre }} - {{ $examen->matiere->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Recherche -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Recherche étudiant
                    </label>
                    <input type="text" 
                           name="recherche" 
                           value="{{ request('recherche') }}"
                           placeholder="Nom, prénom, matricule..."
                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-iris-blue focus:ring-iris-blue">
                </div>

                <!-- Boutons -->
                <div class="flex items-end space-x-2">
                    <button type="submit" 
                            class="flex-1 px-4 py-2 bg-iris-blue text-white rounded-lg hover:bg-blue-700 transition-colors">
                        🔍 Filtrer
                    </button>
                    <a href="{{ route('enseignant.corrections.index') }}" 
                       class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                        ✕
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- 📋 Liste des sessions -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
        
        @if($sessions->isEmpty())
            <div class="text-center py-16">
                <div class="text-6xl mb-4">📝</div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                    Aucune session trouvée
                </h3>
                <p class="text-gray-600 dark:text-gray-400">
                    @if(request('statut_correction'))
                        Aucune session avec ce statut.
                    @else
                        Aucune copie à corriger pour le moment.
                    @endif
                </p>
            </div>
        @else
            <!-- Tableau desktop -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Étudiant
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Examen
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Type correction
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Date soumission
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Note
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Statut
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($sessions as $session)
                            @php
                                // Calcul des questions ouvertes
                                $nbQuestionsOuvertes = $session->examen->questions()->where('type', 'ouverte')->count();
                                $nbReponsesCorrigees = $session->reponsesEtudiants()
                                    ->whereHas('question', function($q) {
                                        $q->where('type', 'ouverte');
                                    })
                                    ->whereNotNull('points_obtenus')
                                    ->count();
                                
                                $pourcentageCorrection = $nbQuestionsOuvertes > 0 
                                    ? ($nbReponsesCorrigees / $nbQuestionsOuvertes) * 100 
                                    : 100;
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <!-- Étudiant -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full bg-iris-blue text-white flex items-center justify-center font-bold mr-3">
                                            {{ strtoupper(substr($session->etudiant->utilisateur->prenom ?? 'E', 0, 1)) }}{{ strtoupper(substr($session->etudiant->utilisateur->nom ?? 'T', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $session->etudiant->utilisateur->prenom ?? 'N/A' }} {{ $session->etudiant->utilisateur->nom ?? 'N/A' }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $session->etudiant->matricule ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Examen -->
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $session->examen->titre }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        📚 {{ $session->examen->matiere->nom }} • 🎓 {{ $session->examen->classe->nom }}
                                    </div>
                                </td>

                                <!-- ✅ CORRIGÉ : Type de correction avec progression -->
                                <td class="px-6 py-4">
                                    @if($nbQuestionsOuvertes > 0)
                                        <div class="text-center mb-2">
                                            <span class="text-sm font-bold {{ $nbReponsesCorrigees === $nbQuestionsOuvertes ? 'text-green-600' : 'text-orange-600' }}">
                                                {{ $nbReponsesCorrigees }}
                                            </span>
                                            <span class="text-sm text-gray-500">/ {{ $nbQuestionsOuvertes }}</span>
                                            <span class="text-xs block text-gray-500 dark:text-gray-400 mt-1">questions ouvertes</span>
                                        </div>
                                        <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                            <div class="progress-bar {{ $pourcentageCorrection === 100 ? 'bg-green-500' : 'bg-orange-500' }} h-2 rounded-full transition-all" 
                                                 style="width: {{ $pourcentageCorrection }}%"></div>
                                        </div>
                                    @else
                                        <div class="text-center">
                                            <span class="px-2 py-1 bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 rounded text-xs font-semibold">
                                                ✅ Auto-corrigé
                                            </span>
                                        </div>
                                    @endif
                                </td>

                                <!-- Date soumission -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $session->date_fin ? $session->date_fin->format('d/m/Y H:i') : 'N/A' }}
                                </td>

                                <!-- Note -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900 dark:text-white">
                                        {{ number_format($session->note_obtenue, 1) }}/{{ $session->examen->note_totale }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ number_format($session->pourcentage, 1) }}%
                                    </div>
                                </td>

                                <!-- Statut -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($session->statut_correction === 'publie')
                                        <span class="badge-statut bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            ✅ Publié
                                        </span>
                                    @elseif($session->statut_correction === 'corrige')
                                        <span class="badge-statut bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            ✏️ Corrigé
                                        </span>
                                    @elseif($pourcentageCorrection > 0 && $pourcentageCorrection < 100)
                                        <span class="badge-statut bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                            ⏳ En cours ({{ round($pourcentageCorrection) }}%)
                                        </span>
                                    @else
                                        <span class="badge-statut bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200">
                                            ⏰ En attente
                                        </span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                    <div class="flex items-center justify-end space-x-2">
                                        @if($session->statut_correction === 'en_attente' || $session->statut_correction === 'corrige')
                                            <a href="{{ route('enseignant.corrections.show', $session) }}" 
                                               class="px-3 py-1.5 bg-iris-blue text-white rounded-lg hover:bg-blue-700 transition-colors">
                                                ✏️ Corriger
                                            </a>
                                        @else
                                            <a href="{{ route('enseignant.corrections.show', $session) }}" 
                                               class="px-3 py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                                👁️ Voir
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Cards mobile -->
            <div class="md:hidden p-4 space-y-4">
                @foreach($sessions as $session)
                    @php
                        $nbQuestionsOuvertes = $session->examen->questions()->where('type', 'ouverte')->count();
                        $nbReponsesCorrigees = $session->reponsesEtudiants()
                            ->whereHas('question', function($q) {
                                $q->where('type', 'ouverte');
                            })
                            ->whereNotNull('points_obtenus')
                            ->count();
                        
                        $pourcentageCorrection = $nbQuestionsOuvertes > 0 
                            ? ($nbReponsesCorrigees / $nbQuestionsOuvertes) * 100 
                            : 100;
                    @endphp

                    <div class="session-card bg-white dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 p-4">
                        <!-- Étudiant + Statut -->
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-iris-blue text-white flex items-center justify-center font-bold mr-3">
                                    {{ strtoupper(substr($session->etudiant->utilisateur->prenom ?? 'E', 0, 1)) }}{{ strtoupper(substr($session->etudiant->utilisateur->nom ?? 'T', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $session->etudiant->utilisateur->prenom ?? 'N/A' }} {{ $session->etudiant->utilisateur->nom ?? 'N/A' }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $session->etudiant->matricule ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                            @if($session->statut_correction === 'publie')
                                <span class="badge-statut bg-green-100 text-green-800">✅</span>
                            @elseif($session->statut_correction === 'corrige')
                                <span class="badge-statut bg-blue-100 text-blue-800">✏️</span>
                            @else
                                <span class="badge-statut bg-orange-100 text-orange-800">⏳</span>
                            @endif
                        </div>

                        <!-- Examen -->
                        <div class="mb-3">
                            <div class="text-sm font-medium text-gray-900 dark:text-white mb-1">
                                {{ $session->examen->titre }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                📚 {{ $session->examen->matiere->nom }} • 🎓 {{ $session->examen->classe->nom }}
                            </div>
                        </div>

                        <!-- ✅ CORRIGÉ : Progression si questions ouvertes -->
                        @if($nbQuestionsOuvertes > 0)
                            <div class="mb-3">
                                <div class="flex justify-between text-xs text-gray-600 dark:text-gray-400 mb-1">
                                    <span>Questions ouvertes</span>
                                    <span class="font-semibold {{ $nbReponsesCorrigees === $nbQuestionsOuvertes ? 'text-green-600' : 'text-orange-600' }}">
                                        {{ $nbReponsesCorrigees }}/{{ $nbQuestionsOuvertes }} corrigées
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                    <div class="progress-bar {{ $pourcentageCorrection === 100 ? 'bg-green-500' : 'bg-orange-500' }} h-2 rounded-full transition-all" 
                                         style="width: {{ $pourcentageCorrection }}%"></div>
                                </div>
                            </div>
                        @else
                            <div class="mb-3 text-center">
                                <span class="px-2 py-1 bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 rounded text-xs font-semibold">
                                    ✅ Auto-corrigé
                                </span>
                            </div>
                        @endif

                        <!-- Date et note -->
                        <div class="flex items-center justify-between mb-3 text-xs">
                            <span class="text-gray-500 dark:text-gray-400">
                                📅 {{ $session->date_fin ? $session->date_fin->format('d/m/Y H:i') : 'N/A' }}
                            </span>
                            <span class="font-bold text-gray-900 dark:text-white">
                                {{ number_format($session->note_obtenue, 1) }}/{{ $session->examen->note_totale }}
                            </span>
                        </div>

                        <!-- Action -->
                        @if($session->statut_correction === 'en_attente' || $session->statut_correction === 'corrige')
                            <a href="{{ route('enseignant.corrections.show', $session) }}" 
                               class="block w-full text-center px-4 py-2 bg-iris-blue text-white rounded-lg hover:bg-blue-700 transition-colors">
                                ✏️ Corriger
                            </a>
                        @else
                            <a href="{{ route('enseignant.corrections.show', $session) }}" 
                               class="block w-full text-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                👁️ Voir la correction
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $sessions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection