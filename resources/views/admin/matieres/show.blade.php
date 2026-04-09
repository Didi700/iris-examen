@extends('layouts.app')

@section('title', 'Détails de la matière')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- En-tête -->
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.matieres.index') }}" 
                   class="text-gray-600 hover:text-iris-yellow">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-iris-black-900">{{ $matiere->nom }}</h1>
                    <p class="text-gray-600 mt-1">{{ $matiere->code }}</p>
                </div>
            </div>
            <a href="{{ route('admin.matieres.edit', $matiere) }}" 
               class="px-6 py-3 bg-iris-yellow text-iris-black-900 rounded-lg font-semibold hover:bg-iris-yellow-600 transition-all">
                Modifier
            </a>
        </div>

        <!-- Informations générales -->
        <div class="bg-white rounded-2xl shadow-sm p-8">
            <h3 class="text-lg font-semibold text-iris-black-900 mb-4">Informations générales</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <p class="text-sm text-gray-600">Code</p>
                    <p class="text-base font-medium text-iris-black-900">{{ $matiere->code }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-600">Coefficient</p>
                    <p class="text-base font-medium text-iris-black-900">{{ $matiere->coefficient }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-600">Statut</p>
                    <p class="text-base font-medium">
                        <span class="px-3 py-1 text-xs font-semibold rounded-full
                            @if($matiere->statut === 'active') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($matiere->statut) }}
                        </span>
                    </p>
                </div>

                @if($matiere->description)
                    <div class="md:col-span-3 pt-4 border-t">
                        <p class="text-sm text-gray-600 mb-2">Description</p>
                        <p class="text-base text-gray-900">{{ $matiere->description }}</p>
                    </div>
                @endif

                <div class="md:col-span-3 pt-4 border-t">
                    <p class="text-sm text-gray-600 mb-2">Créée par</p>
                    <p class="text-base text-gray-900">
                        {{ $matiere->createur ? $matiere->createur->nomComplet() : 'Système' }}
                        le {{ $matiere->created_at->format('d/m/Y à H:i') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl shadow-sm p-8 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-iris-yellow bg-opacity-20 rounded-full mb-4">
                    <svg class="h-8 w-8 text-iris-yellow-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-4xl font-bold text-iris-black-900 mb-2">{{ $matiere->questions()->count() }}</p>
                <p class="text-sm text-gray-600">Questions dans la banque</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-8 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-iris-blue bg-opacity-20 rounded-full mb-4">
                    <svg class="h-8 w-8 text-iris-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <p class="text-4xl font-bold text-iris-black-900 mb-2">{{ $matiere->examens()->count() }}</p>
                <p class="text-sm text-gray-600">Examens créés</p>
            </div>
        </div>
    </div>
@endsection