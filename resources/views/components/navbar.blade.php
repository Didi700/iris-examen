<nav class="bg-white shadow-sm border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo et nom -->
            <div class="flex items-center">
                <a href="/" class="flex items-center space-x-3">
                    <x-logo size="md" variant="icon" />
                    <div class="flex flex-col">
                        <span class="text-xl font-bold text-iris-black-900">
                            IRIS <span class="text-iris-yellow">EXAM</span>
                        </span>
                        <span class="text-xs text-gray-500 hidden sm:block">Plateforme d'évaluation</span>
                    </div>
                </a>

                <!-- Navigation selon le rôle -->
                @auth
                    <div class="hidden md:ml-10 md:flex md:space-x-8">
                        @if(auth()->user()->estSuperAdmin() || auth()->user()->estAdmin())
                            <a href="{{ route('admin.dashboard') }}" 
                               class="text-gray-700 hover:text-iris-yellow px-3 py-2 rounded-md text-sm font-medium transition-colors">
                                Dashboard
                            </a>
                        @elseif(auth()->user()->estEnseignant())
                            <a href="{{ route('enseignant.dashboard') }}" 
                               class="text-gray-700 hover:text-iris-yellow px-3 py-2 rounded-md text-sm font-medium transition-colors">
                                Dashboard
                            </a>
                        @elseif(auth()->user()->estEtudiant())
                            <a href="{{ route('etudiant.dashboard') }}" 
                               class="text-gray-700 hover:text-iris-yellow px-3 py-2 rounded-md text-sm font-medium transition-colors">
                                Dashboard
                            </a>
                        @endif
                    </div>
                @endauth
            </div>

            <!-- Menu utilisateur -->
            <div class="flex items-center">
                @auth
                    <div class="ml-4 flex items-center md:ml-6 space-x-4">
                        <span class="text-sm text-gray-700 font-medium">
                            {{ auth()->user()->nomComplet() }}
                        </span>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full 
                            @if(auth()->user()->estSuperAdmin()) bg-purple-100 text-purple-800
                            @elseif(auth()->user()->estAdmin()) bg-iris-yellow bg-opacity-20 text-iris-yellow-700
                            @elseif(auth()->user()->estEnseignant()) bg-green-100 text-green-800
                            @else bg-iris-blue bg-opacity-20 text-iris-blue
                            @endif">
                            {{ ucfirst(str_replace('_', ' ', auth()->user()->role->nom)) }}
                        </span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" 
                                    class="text-gray-700 hover:text-iris-red px-3 py-2 rounded-md text-sm font-medium transition-colors">
                                Déconnexion
                            </button>
                        </form>
                    </div>
                @else
                    <div class="flex space-x-4">
                        <a href="{{ route('login') }}" 
                           class="text-gray-700 hover:text-iris-yellow px-4 py-2 rounded-md text-sm font-medium transition-colors">
                            Connexion
                        </a>
                        <a href="{{ route('register') }}" 
                           class="bg-iris-yellow text-iris-black-900 hover:bg-iris-yellow-600 px-4 py-2 rounded-lg text-sm font-semibold shadow-sm transition-all">
                            Inscription
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</nav>