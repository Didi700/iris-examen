@extends('layouts.app')

@section('title', 'Alertes Anti-Triche')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">🚨 Alertes Anti-Triche</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Gérez les comportements suspects détectés</p>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total alertes -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total alertes</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['total'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Sans décision -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border-l-4 border-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Sans décision</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['sans_decision'] }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Gravité élevée -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Gravité élevée</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['gravite_elevee'] }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.618 5.984A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <form method="GET" action="{{ route('enseignant.alertes.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Gravité -->
            <div>
                <label for="gravite" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Gravité</label>
                <select name="gravite" id="gravite" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-iris-yellow focus:border-iris-yellow">
                    <option value="">Toutes</option>
                    <option value="1" {{ request('gravite') == '1' ? 'selected' : '' }}>⚠️ Faible</option>
                    <option value="2" {{ request('gravite') == '2' ? 'selected' : '' }}>🟠 Modérée</option>
                    <option value="3" {{ request('gravite') == '3' ? 'selected' : '' }}>🔴 Élevée</option>
                </select>
            </div>

            <!-- Décision -->
            <div>
                <label for="decision" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Décision</label>
                <select name="decision" id="decision" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-iris-yellow focus:border-iris-yellow">
                    <option value="">Toutes</option>
                    <option value="aucune" {{ request('decision') == 'aucune' ? 'selected' : '' }}>Sans décision</option>
                    <option value="ignore" {{ request('decision') == 'ignore' ? 'selected' : '' }}>Ignoré</option>
                    <option value="avertissement" {{ request('decision') == 'avertissement' ? 'selected' : '' }}>Avertissement</option>
                    <option value="annulation" {{ request('decision') == 'annulation' ? 'selected' : '' }}>Annulé</option>
                    <option value="sanction" {{ request('decision') == 'sanction' ? 'selected' : '' }}>Sanctionné</option>
                </select>
            </div>

            <!-- Examen -->
            <div>
                <label for="examen_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Examen</label>
                <select name="examen_id" id="examen_id" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-iris-yellow focus:border-iris-yellow">
                    <option value="">Tous</option>
                    @foreach($examens as $examen)
                        <option value="{{ $examen->id }}" {{ request('examen_id') == $examen->id ? 'selected' : '' }}>
                            {{ $examen->titre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Bouton -->
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-iris-blue text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                    🔍 Filtrer
                </button>
            </div>
        </form>
    </div>

    <!-- Liste des alertes -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
        @if($sessions->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Étudiant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Examen</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Alertes</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Gravité</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Décision</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($sessions as $session)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <!-- Étudiant -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold">
                                            {{ substr($session->utilisateur->prenom, 0, 1) }}{{ substr($session->utilisateur->nom, 0, 1) }}
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $session->utilisateur->nomComplet() }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $session->utilisateur->matricule }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Examen -->
                                <td class="px-6 py-4">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $session->examen->titre }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $session->examen->matiere->nom }}</p>
                                </td>

                                <!-- Nombre alertes -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                        ⚠️ {{ $session->nombre_alertes }}
                                    </span>
                                </td>

                                <!-- Gravité -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $badges = [
                                            0 => ['text' => 'Aucune', 'class' => 'bg-green-100 text-green-800'],
                                            1 => ['text' => 'Faible', 'class' => 'bg-yellow-100 text-yellow-800'],
                                            2 => ['text' => 'Modérée', 'class' => 'bg-orange-100 text-orange-800'],
                                            3 => ['text' => 'Élevée', 'class' => 'bg-red-100 text-red-800'],
                                        ];
                                        $badge = $badges[$session->niveau_gravite] ?? $badges[0];
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $badge['class'] }}">
                                        {{ $badge['text'] }}
                                    </span>
                                </td>

                                <!-- Décision -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($session->decision_enseignant === 'aucune')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                            ⏳ En attente
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                            {{ $session->decision_libelle }}
                                        </span>
                                    @endif
                                </td>

                                <!-- Date -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $session->date_soumission->format('d/m/Y H:i') }}
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('enseignant.alertes.show', $session) }}" 
                                       class="text-iris-blue hover:text-blue-700 font-semibold">
                                        👁️ Voir
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $sessions->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <p class="text-gray-500 dark:text-gray-400 text-lg">Aucune alerte trouvée</p>
                <p class="text-gray-400 dark:text-gray-500 text-sm mt-2">Bravo ! Aucun comportement suspect détecté</p>
            </div>
        @endif
    </div>
</div>
@endsection