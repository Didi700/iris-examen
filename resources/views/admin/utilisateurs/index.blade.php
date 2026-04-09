@extends('layouts.app')

@section('title', 'Gestion des utilisateurs')

@section('content')
    <div class="space-y-6">
        <!-- En-tête -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-iris-black-900">Gestion des utilisateurs</h1>
                <p class="text-gray-600 mt-1">Gérez tous les utilisateurs de la plateforme</p>
            </div>
            <a href="{{ route('admin.utilisateurs.create') }}" 
               class="bg-iris-yellow text-iris-black-900 hover:bg-iris-yellow-600 px-6 py-3 rounded-lg font-semibold transition-all shadow-sm flex items-center">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nouvel utilisateur
            </a>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total</p>
                        <p class="text-3xl font-bold text-iris-black-900 mt-2">{{ $stats['total'] }}</p>
                    </div>
                    <div class="bg-iris-blue bg-opacity-10 rounded-full p-3">
                        <svg class="h-8 w-8 text-iris-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Étudiants</p>
                        <p class="text-3xl font-bold text-iris-blue mt-2">{{ $stats['etudiants'] }}</p>
                    </div>
                    <div class="bg-iris-blue bg-opacity-10 rounded-full p-3">
                        <svg class="h-8 w-8 text-iris-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Enseignants</p>
                        <p class="text-3xl font-bold text-iris-green mt-2">{{ $stats['enseignants'] }}</p>
                    </div>
                    <div class="bg-iris-green bg-opacity-10 rounded-full p-3">
                        <svg class="h-8 w-8 text-iris-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Admins</p>
                        <p class="text-3xl font-bold text-iris-yellow-700 mt-2">{{ $stats['admins'] }}</p>
                    </div>
                    <div class="bg-iris-yellow bg-opacity-20 rounded-full p-3">
                        <svg class="h-8 w-8 text-iris-yellow-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <form method="GET" action="{{ route('admin.utilisateurs.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Recherche -->
                <div class="md:col-span-2">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Rechercher (nom, email, matricule...)" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow"
                    >
                </div>

                <!-- Rôle -->
                <div>
                    <select 
                        name="role" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow"
                    >
                        <option value="">Tous les rôles</option>
                        <option value="etudiant" {{ request('role') === 'etudiant' ? 'selected' : '' }}>Étudiant</option>
                        <option value="enseignant" {{ request('role') === 'enseignant' ? 'selected' : '' }}>Enseignant</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="super_admin" {{ request('role') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                    </select>
                </div>

                <!-- Statut -->
                <div>
                    <select 
                        name="statut" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-iris-yellow focus:border-iris-yellow"
                    >
                        <option value="">Tous les statuts</option>
                        <option value="actif" {{ request('statut') === 'actif' ? 'selected' : '' }}>Actif</option>
                        <option value="inactif" {{ request('statut') === 'inactif' ? 'selected' : '' }}>Inactif</option>
                        <option value="suspendu" {{ request('statut') === 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                    </select>
                </div>

                <!-- Boutons -->
                <div class="md:col-span-4 flex space-x-2">
                    <button 
                        type="submit" 
                        class="bg-iris-yellow text-iris-black-900 px-6 py-2 rounded-lg font-semibold hover:bg-iris-yellow-600 transition-all"
                    >
                        Filtrer
                    </button>
                    <a 
                        href="{{ route('admin.utilisateurs.index') }}" 
                        class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg font-semibold hover:bg-gray-300 transition-all"
                    >
                        Réinitialiser
                    </a>
                </div>
            </form>
        </div>

        <!-- Liste des utilisateurs -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Utilisateur
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Rôle
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Matricule
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Statut
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Date création
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($utilisateurs as $utilisateur)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-iris-yellow bg-opacity-20 rounded-full flex items-center justify-center flex-shrink-0">
                                        <span class="text-iris-yellow-700 font-semibold text-sm">
                                            {{ $utilisateur->initiales() }}
                                        </span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-iris-black-900">
                                            {{ $utilisateur->nomComplet() }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $utilisateur->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($utilisateur->role->nom === 'etudiant') bg-iris-blue bg-opacity-20 text-iris-blue
                                    @elseif($utilisateur->role->nom === 'enseignant') bg-iris-green bg-opacity-20 text-iris-green
                                    @elseif($utilisateur->role->nom === 'admin') bg-iris-yellow bg-opacity-20 text-iris-yellow-700
                                    @else bg-purple-100 text-purple-800
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $utilisateur->role->nom)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $utilisateur->matricule ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($utilisateur->statut === 'actif') bg-green-100 text-green-800
                                    @elseif($utilisateur->statut === 'inactif') bg-gray-100 text-gray-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($utilisateur->statut) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $utilisateur->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-3">
                                    <!-- Voir -->
                                    <a href="{{ route('admin.utilisateurs.show', $utilisateur->id) }}" 
                                       class="text-iris-blue hover:text-blue-700"
                                       title="Voir">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>

                                    <!-- Modifier -->
                                    <a href="{{ route('admin.utilisateurs.edit', $utilisateur->id) }}" 
                                       class="text-iris-yellow-700 hover:text-iris-yellow-800"
                                       title="Modifier">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>

                                    <!-- Affecter (seulement pour les enseignants) -->
                                    @if($utilisateur->estEnseignant())
                                        <a href="{{ route('admin.enseignants.affecter', $utilisateur->id) }}" 
                                           class="text-iris-green hover:text-green-700"
                                           title="Affecter à des classes/matières">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                        </a>
                                    @endif

                                    <!-- Supprimer -->
                                    @if($utilisateur->id !== auth()->id())
                                        <form method="POST" 
                                              action="{{ route('admin.utilisateurs.destroy', $utilisateur->id) }}" 
                                              class="inline"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-800"
                                                    title="Supprimer">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <p class="mt-4 text-gray-600">Aucun utilisateur trouvé.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $utilisateurs->links() }}
        </div>
    </div>
@endsection