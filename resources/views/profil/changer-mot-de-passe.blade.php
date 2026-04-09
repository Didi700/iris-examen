@extends('layouts.app')

@section('title', 'Changer mon mot de passe')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded-lg mb-6">
        <div class="flex items-start">
            <svg class="h-6 w-6 text-yellow-400 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div>
                <h3 class="text-lg font-bold text-yellow-800">⚠️ Changement de mot de passe obligatoire</h3>
                <p class="text-yellow-700 mt-2">
                    Pour des raisons de sécurité, vous devez changer votre mot de passe temporaire avant de continuer.
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">🔐 Changer mon mot de passe</h1>

        <form action="{{ route('profil.changer-mot-de-passe.post') }}" method="POST">
            @csrf

            <div class="space-y-6">
                <!-- Ancien mot de passe -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Mot de passe temporaire (reçu par email)
                    </label>
                    <input type="password" 
                           name="ancien_mot_de_passe"
                           required
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-iris-yellow focus:border-iris-yellow">
                    @error('ancien_mot_de_passe')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nouveau mot de passe -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nouveau mot de passe
                    </label>
                    <input type="password" 
                           name="nouveau_mot_de_passe"
                           required
                           minlength="8"
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-iris-yellow focus:border-iris-yellow">
                    <p class="text-sm text-gray-600 mt-1">Au moins 8 caractères</p>
                    @error('nouveau_mot_de_passe')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirmation -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Confirmer le nouveau mot de passe
                    </label>
                    <input type="password" 
                           name="nouveau_mot_de_passe_confirmation"
                           required
                           minlength="8"
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-iris-yellow focus:border-iris-yellow">
                </div>

                <!-- Bouton -->
                <button type="submit" 
                        class="w-full px-6 py-3 bg-iris-yellow text-iris-black-900 rounded-lg font-bold hover:bg-yellow-500 transition-all shadow-lg">
                    ✓ Changer mon mot de passe
                </button>
            </div>
        </form>
    </div>
</div>
@endsection