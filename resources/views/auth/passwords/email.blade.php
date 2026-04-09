@extends('layouts.guest')

@section('title', 'Mot de passe oublié')

@section('heading', 'Réinitialiser le mot de passe')

@section('subheading', 'Entrez votre email pour recevoir un lien de réinitialisation')

@section('content')
    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">
                Adresse email
            </label>
            <input 
                id="email" 
                name="email" 
                type="email" 
                required
                value="{{ old('email') }}"
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-iris-yellow focus:border-iris-yellow @error('email') border-red-500 @enderror"
            >
            @error('email')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Bouton -->
        <div>
            <button 
                type="submit"
                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-iris-black-900 bg-iris-yellow hover:bg-iris-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-iris-yellow transition-all"
            >
                Envoyer le lien
            </button>
        </div>

        <!-- Retour à la connexion -->
        <div class="text-center">
            <a href="{{ route('login') }}" class="text-sm text-iris-yellow hover:text-iris-yellow-600">
                Retour à la connexion
            </a>
        </div>
    </form>
@endsection