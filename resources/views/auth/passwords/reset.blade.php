@extends('layouts.guest')

@section('title', 'Nouveau mot de passe')

@section('heading', 'Réinitialiser le mot de passe')

@section('content')
    <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">

        <!-- Mot de passe -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">
                Nouveau mot de passe
            </label>
            <input 
                id="password" 
                name="password" 
                type="password" 
                required
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-iris-yellow focus:border-iris-yellow @error('password') border-red-500 @enderror"
            >
            @error('password')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirmation -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                Confirmer le mot de passe
            </label>
            <input 
                id="password_confirmation" 
                name="password_confirmation" 
                type="password" 
                required
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-iris-yellow focus:border-iris-yellow"
            >
        </div>

        <!-- Bouton -->
        <div>
            <button 
                type="submit"
                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-iris-black-900 bg-iris-yellow hover:bg-iris-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-iris-yellow transition-all"
            >
                Réinitialiser
            </button>
        </div>
    </form>
@endsection