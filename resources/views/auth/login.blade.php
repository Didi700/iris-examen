@extends('layouts.guest')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-iris-blue via-purple-600 to-purple-800 flex items-center justify-center px-4 py-12">
    <div class="max-w-md w-full">
        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <!-- Logo -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-iris-yellow to-yellow-600 rounded-xl mb-4">
                    <span class="text-3xl">📚</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">Connexion</h1>
                <p class="text-gray-600 mt-2">Connectez-vous à votre compte IRIS EXAM</p>
            </div>

            <!-- Erreurs -->
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">{{ $errors->first() }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Formulaire -->
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

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
                           autofocus
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-iris-yellow focus:border-transparent transition-all text-lg @error('email') border-red-500 @enderror"
                           placeholder="votre@email.com">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Mot de passe -->
                <div class="relative">
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                        Mot de passe
                    </label>
                    <input type="password" 
                           name="password" 
                           id="password" 
                           required
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-iris-yellow focus:border-transparent transition-all text-lg @error('password') border-red-500 @enderror"
                           placeholder="••••••••">
                    <!-- Bouton œil -->  
                    <button type="button" id="togglePassword" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                        👁️   
                    </button>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember me & Forgot password -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="remember" 
                               class="h-5 w-5 text-iris-yellow rounded focus:ring-iris-yellow border-gray-300">
                        <span class="ml-2 text-sm text-gray-700">Se souvenir de moi</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" 
                           class="text-sm font-semibold text-iris-yellow hover:text-yellow-600 transition-colors">
                            Mot de passe oublié ?
                        </a>
                    @endif
                </div>

                <!-- Submit -->
                <button type="submit" 
                        class="w-full py-4 bg-iris-yellow text-gray-900 rounded-xl font-bold text-lg hover:bg-yellow-600 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    Se connecter
                </button>
            </form>

            <!-- Inscription -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Vous n'avez pas de compte ?
                    <a href="{{ route('register') }}" 
                       class="font-semibold text-iris-yellow hover:text-yellow-600 transition-colors">
                        Inscrivez-vous gratuitement
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
<script>
    const togglePassword = document.querySelector('#togglePassword');
    const passwordInput = document.querySelector('#password');

    togglePassword.addEventListener('click', () => {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        // Changer l’icône
        togglePassword.textContent = type === 'password' ? '👁️' : '🙈';
    });
</script>
@endsection