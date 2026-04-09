@extends('layouts.guest')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-iris-blue via-purple-600 to-purple-800 flex items-center justify-center px-4 py-12">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <!-- Logo -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-iris-yellow to-yellow-600 rounded-xl mb-4">
                    <span class="text-3xl">📚</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">Inscription</h1>
                <p class="text-gray-600 mt-2">Créez votre compte IRIS EXAM</p>
            </div>

            <!-- Formulaire -->
            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <!-- Prénom -->
                <div>
                    <label for="prenom" class="block text-sm font-semibold text-gray-700 mb-2">
                        Prénom
                    </label>
                    <input type="text" 
                           name="prenom" 
                           id="prenom" 
                           value="{{ old('prenom') }}"
                           required
                           autofocus
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-iris-yellow focus:border-transparent transition-all @error('prenom') border-red-500 @enderror"
                           placeholder="Votre prénom">
                    @error('prenom')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nom -->
                <div>
                    <label for="nom" class="block text-sm font-semibold text-gray-700 mb-2">
                        Nom
                    </label>
                    <input type="text" 
                           name="nom" 
                           id="nom" 
                           value="{{ old('nom') }}"
                           required
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-iris-yellow focus:border-transparent transition-all @error('nom') border-red-500 @enderror"
                           placeholder="Votre nom">
                    @error('nom')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                        Adresse email
                    </label>
                    <input type="email" 
                           name="email" 
                           id="email" 
                           value="{{ old('email') }}"
                           required
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-iris-yellow focus:border-transparent transition-all @error('email') border-red-500 @enderror"
                           placeholder="votre@email.com">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Mot de passe -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                        Mot de passe
                    </label>
                    <input type="password" 
                           name="password" 
                           id="password" 
                           required
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-iris-yellow focus:border-transparent transition-all @error('password') border-red-500 @enderror"
                           placeholder="••••••••">
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirmation mot de passe -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                        Confirmer le mot de passe
                    </label>
                    <input type="password" 
                           name="password_confirmation" 
                           id="password_confirmation" 
                           required
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-iris-yellow focus:border-transparent transition-all"
                           placeholder="••••••••">
                </div>

                <!-- Role -->
                <div>
                    <label for="role_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        Vous êtes
                    </label>
                    <select name="role_id" 
                            id="role_id" 
                            required
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-iris-yellow focus:border-transparent transition-all">
                        <option value="">Sélectionnez votre rôle</option>
                        <option value="2">Enseignant</option>
                        <option value="3">Étudiant</option>
                    </select>
                    @error('role_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit -->
                <button type="submit" 
                        class="w-full py-4 bg-iris-yellow text-gray-900 rounded-xl font-bold text-lg hover:bg-yellow-600 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    S'inscrire
                </button>
            </form>

            <!-- Connexion -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Vous avez déjà un compte ?
                    <a href="{{ route('login') }}" 
                       class="font-semibold text-iris-yellow hover:text-yellow-600 transition-colors">
                        Connectez-vous
                    </a>
                </p>
            </div>
        </div>

        <!-- Retour accueil -->
        <div class="mt-6 text-center">
            <a href="{{ route('home') }}" 
               class="inline-flex items-center text-white hover:text-iris-yellow transition-colors">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour à l'accueil
            </a>
        </div>
    </div>
</div>
@endsection