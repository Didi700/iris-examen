@extends('layouts.app')

@section('title', 'Détail de l\'alerte')

@section('content')
<div class="space-y-6">
    <!-- Retour -->
    <div>
        <a href="{{ route('enseignant.alertes.index') }}" 
           class="text-iris-blue hover:text-blue-700 font-semibold">
            ← Retour aux alertes
        </a>
    </div>

    <!-- En-tête -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">🚨 Détail de l'alerte</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">
                    Examen : <strong>{{ $session->examen->titre }}</strong>
                </p>
            </div>

            <!-- Badge gravité -->
            @php
                $badges = [
                    0 => ['text' => 'Aucune', 'class' => 'bg-green-100 text-green-800'],
                    1 => ['text' => 'Gravité faible', 'class' => 'bg-yellow-100 text-yellow-800'],
                    2 => ['text' => 'Gravité modérée', 'class' => 'bg-orange-100 text-orange-800'],
                    3 => ['text' => 'Gravité élevée', 'class' => 'bg-red-100 text-red-800'],
                ];
                $badge = $badges[$session->niveau_gravite] ?? $badges[0];
            @endphp
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold {{ $badge['class'] }}">
                {{ $badge['text'] }} ({{ $session->nombre_alertes }} alertes)
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Colonne gauche : Informations -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Étudiant -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">👤 Étudiant</h2>
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold text-xl">
                        {{ substr($session->utilisateur->prenom, 0, 1) }}{{ substr($session->utilisateur->nom, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $session->utilisateur->nomComplet() }}
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $session->utilisateur->email }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-500">Matricule: {{ $session->utilisateur->matricule }}</p>
                    </div>
                </div>
            </div>

            <!-- Liste des alertes -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">⚠️ Alertes détectées ({{ count($session->alertes_triche ?? []) }})</h2>
                
                @if($session->alertes_triche && count($session->alertes_triche) > 0)
                    <div class="space-y-3">
                        @foreach($session->alertes_triche as $index => $alerte)
                            <div class="border-l-4 border-red-500 bg-red-50 dark:bg-red-900 dark:bg-opacity-20 p-4 rounded-r-lg">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <p class="font-semibold text-red-800 dark:text-red-200">
                                            {{ $index + 1 }}. 
                                            @if($alerte['type'] === 'changement_onglet')
                                                🔄 Changement d'onglet
                                            @elseif($alerte['type'] === 'tentative_copier')
                                                📋 Tentative de copier
                                            @else
                                                {{ $alerte['type'] }}
                                            @endif
                                        </p>
                                        @if(isset($alerte['details']['message']))
                                            <p class="text-sm text-red-700 dark:text-red-300 mt-1">
                                                {{ $alerte['details']['message'] }}
                                            </p>
                                        @endif
                                    </div>
                                    <span class="text-xs text-red-600 dark:text-red-400">
                                        {{ \Carbon\Carbon::parse($alerte['timestamp'])->format('H:i:s') }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400">Aucune alerte enregistrée</p>
                @endif
            </div>

            <!-- Informations session -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">📊 Informations de la session</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Date de passage</p>
                        <p class="text-base font-semibold text-gray-900 dark:text-white">
                            {{ $session->date_soumission->format('d/m/Y à H:i') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Temps passé</p>
                        <p class="text-base font-semibold text-gray-900 dark:text-white">
                            {{ $session->getTempsPasseFormate() }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Note obtenue</p>
                        <p class="text-base font-semibold text-gray-900 dark:text-white">
                            {{ $session->note_formatee }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Adresse IP</p>
                        <p class="text-base font-mono text-gray-900 dark:text-white">
                            {{ $session->ip_address ?? 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne droite : Décision -->
        <div class="space-y-6">
            <!-- Décision actuelle -->
            @if($session->decision_enseignant !== 'aucune')
                <div class="bg-blue-50 dark:bg-blue-900 dark:bg-opacity-20 border-2 border-blue-300 dark:border-blue-700 rounded-xl p-6">
                    <h3 class="text-lg font-bold text-blue-900 dark:text-blue-200 mb-3">✅ Décision prise</h3>
                    
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-blue-700 dark:text-blue-300">Décision</p>
                            <p class="text-base font-semibold text-blue-900 dark:text-blue-100">
                                {{ $session->decision_libelle }}
                            </p>
                        </div>

                        @if($session->commentaire_enseignant)
                            <div>
                                <p class="text-sm text-blue-700 dark:text-blue-300">Commentaire</p>
                                <p class="text-sm text-blue-900 dark:text-blue-100 italic">
                                    "{{ $session->commentaire_enseignant }}"
                                </p>
                            </div>
                        @endif

                        <div>
                            <p class="text-sm text-blue-700 dark:text-blue-300">Par</p>
                            <p class="text-sm font-semibold text-blue-900 dark:text-blue-100">
                                {{ $session->decisionPar?->nomComplet() ?? 'Inconnu' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-blue-700 dark:text-blue-300">Date</p>
                            <p class="text-sm text-blue-900 dark:text-blue-100">
                                {{ $session->date_decision?->format('d/m/Y à H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Formulaire de décision -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">
                    {{ $session->decision_enseignant === 'aucune' ? '⚖️ Prendre une décision' : '🔄 Modifier la décision' }}
                </h3>

                <form action="{{ route('enseignant.alertes.decider', $session) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('POST')

                    <!-- Choix de la décision -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Décision <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-2">
                            <label class="flex items-center p-3 border-2 border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <input type="radio" name="decision" value="ignore" 
                                       {{ $session->decision_enseignant === 'ignore' ? 'checked' : '' }}
                                       class="h-4 w-4 text-iris-yellow focus:ring-iris-yellow" required>
                                <span class="ml-3 text-sm font-medium text-gray-900 dark:text-white">
                                    ✅ Ignorer (pas de triche)
                                </span>
                            </label>

                            <label class="flex items-center p-3 border-2 border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <input type="radio" name="decision" value="avertissement"
                                       {{ $session->decision_enseignant === 'avertissement' ? 'checked' : '' }}
                                       class="h-4 w-4 text-iris-yellow focus:ring-iris-yellow">
                                <span class="ml-3 text-sm font-medium text-gray-900 dark:text-white">
                                    ⚠️ Avertissement (notifier l'étudiant)
                                </span>
                            </label>

                            <label class="flex items-center p-3 border-2 border-red-200 dark:border-red-700 rounded-lg cursor-pointer hover:bg-red-50 dark:hover:bg-red-900 transition-colors">
                                <input type="radio" name="decision" value="annulation"
                                       {{ $session->decision_enseignant === 'annulation' ? 'checked' : '' }}
                                       class="h-4 w-4 text-red-600 focus:ring-red-600">
                                <span class="ml-3 text-sm font-medium text-red-900 dark:text-red-200">
                                    ❌ Annuler l'examen (note = 0)
                                </span>
                            </label>

                            <label class="flex items-center p-3 border-2 border-red-200 dark:border-red-700 rounded-lg cursor-pointer hover:bg-red-50 dark:hover:bg-red-900 transition-colors">
                                <input type="radio" name="decision" value="sanction"
                                       {{ $session->decision_enseignant === 'sanction' ? 'checked' : '' }}
                                       class="h-4 w-4 text-red-600 focus:ring-red-600">
                                <span class="ml-3 text-sm font-medium text-red-900 dark:text-red-200">
                                    🚫 Sanctionner (mesure disciplinaire)
                                </span>
                            </label>
                        </div>
                        @error('decision')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Commentaire -->
                    <div>
                        <label for="commentaire" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Commentaire (optionnel)
                        </label>
                        <textarea name="commentaire" 
                                  id="commentaire" 
                                  rows="4"
                                  placeholder="Ajoutez un commentaire pour justifier votre décision..."
                                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-iris-yellow focus:border-iris-yellow">{{ old('commentaire', $session->commentaire_enseignant) }}</textarea>
                        @error('commentaire')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Bouton -->
                    <button type="submit" 
                            class="w-full px-6 py-3 bg-iris-yellow text-gray-900 rounded-lg font-bold hover:bg-yellow-600 transition-all shadow-lg">
                        💾 Enregistrer la décision
                    </button>
                </form>
            </div>

            <!-- ✅ LIEN CORRIGÉ VERS LA COPIE -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-4">
                <a href="{{ route('enseignant.corrections.show', $session) }}" 
                   class="block text-center px-4 py-2 bg-iris-blue text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                    📝 Voir la copie
                </a>
            </div>
        </div>
    </div>
</div>
@endsection