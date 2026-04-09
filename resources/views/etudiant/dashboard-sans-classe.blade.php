@extends('layouts.app')

@section('title', 'Dashboard Étudiant')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Message d'avertissement -->
    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border-l-4 border-yellow-400 p-8 rounded-xl shadow-lg">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-16 w-16 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div class="ml-6 flex-1">
                <h3 class="text-2xl font-bold text-yellow-800 mb-3">⚠️ Aucune classe assignée</h3>
                <div class="prose text-yellow-700">
                    <p class="text-lg mb-3">
                        Votre compte a été créé avec succès, mais vous n'avez pas encore été assigné à une classe.
                    </p>
                    <p class="mb-3">
                        <strong>Que faire maintenant ?</strong>
                    </p>
                    <ul class="list-disc list-inside space-y-2 mb-4">
                        <li>Contactez l'administration pour qu'elle vous affecte à une classe</li>
                        <li>Vérifiez votre email pour vos identifiants de connexion</li>
                        <li>Changez votre mot de passe lors de votre première connexion</li>
                    </ul>
                    <p class="text-sm">
                        Une fois votre classe assignée, vous pourrez consulter vos examens disponibles et vos résultats directement depuis ce tableau de bord.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Informations du compte -->
    <div class="bg-white rounded-2xl shadow-sm p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
            <svg class="h-6 w-6 mr-3 text-iris-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            Informations de votre compte
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-600 mb-1">Nom complet</p>
                <p class="font-semibold text-gray-900">{{ auth()->user()->prenom }} {{ auth()->user()->nom }}</p>
            </div>
            
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-600 mb-1">Email</p>
                <p class="font-semibold text-gray-900">{{ auth()->user()->email }}</p>
            </div>
            
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-600 mb-1">Matricule</p>
                <p class="font-semibold text-gray-900">{{ auth()->user()->matricule }}</p>
            </div>
            
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-600 mb-1">Statut</p>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                    ✓ Compte actif
                </span>
            </div>
        </div>
    </div>

    <!-- Notifications -->
    <div class="bg-white rounded-2xl shadow-sm p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
            <svg class="h-6 w-6 mr-3 text-iris-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            Vos notifications
        </h2>
        
        @php
            $notifications = auth()->user()->notifications()->recentes()->limit(5)->get();
        @endphp

        @forelse($notifications as $notification)
            <div class="border-b border-gray-200 py-4 last:border-0">
                <div class="flex items-start space-x-3">
                    <div class="text-3xl flex-shrink-0">{{ $notification->icone ?? '📬' }}</div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-gray-900 {{ !$notification->est_lue ? 'text-iris-blue' : '' }}">
                            {{ $notification->titre }}
                        </h3>
                        <p class="text-gray-600 text-sm mt-1">{{ $notification->message }}</p>
                        <p class="text-xs text-gray-500 mt-2">{{ $notification->created_at->diffForHumans() }}</p>
                    </div>
                    @if(!$notification->est_lue)
                        <div class="w-2 h-2 bg-iris-blue rounded-full flex-shrink-0 mt-2"></div>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <p class="text-gray-600">Aucune notification pour le moment</p>
            </div>
        @endforelse
        
        @if($notifications->count() > 0)
            <div class="mt-6 text-center">
                <a href="{{ route('notifications.index') }}" class="text-iris-blue hover:text-blue-700 font-semibold">
                    Voir toutes les notifications →
                </a>
            </div>
        @endif
    </div>

    <!-- Contact administration -->
    <div class="bg-gradient-to-r from-iris-blue to-purple-600 rounded-2xl shadow-lg p-8 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-2xl font-bold mb-2">Besoin d'aide ?</h3>
                <p class="opacity-90">Contactez l'administration pour être assigné à une classe</p>
            </div>
            <svg class="h-16 w-16 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
        </div>
    </div>
</div>
@endsection