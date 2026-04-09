@extends('layouts.app')

@section('title', 'Import/Export de Questions')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div>
        <h1 class="text-3xl font-bold text-iris-black-900">📥 Import/Export de Questions</h1>
        <p class="text-gray-600 mt-1">Importez ou exportez vos questions en masse via Excel ou PDF</p>
    </div>

    <!-- Messages d'erreur détaillés -->
    @if(session('errors_detail'))
        <div class="bg-orange-50 border-l-4 border-orange-400 p-4 rounded-lg">
            <div class="flex items-start">
                <svg class="h-5 w-5 text-orange-400 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div class="flex-1">
                    <p class="text-orange-800 font-semibold mb-2">⚠️ Certaines lignes n'ont pas pu être importées :</p>
                    <div class="text-orange-700 text-sm space-y-1">
                        {!! session('errors_detail') !!}
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- SECTION EXPORT -->
        <div class="bg-white rounded-2xl shadow-sm p-8">
            <div class="flex items-center space-x-3 mb-6">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">📤 Exporter</h2>
                    <p class="text-sm text-gray-600">Téléchargez vos questions</p>
                </div>
            </div>

            <div class="space-y-4">
                <!-- Filtre par matière -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Matière (optionnel)
                    </label>
                    <select id="matiere-select" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-iris-yellow focus:ring-2 focus:ring-iris-yellow focus:ring-opacity-20 transition-all">
                        <option value="">Toutes les matières</option>
                        @foreach($matieres as $matiere)
                            <option value="{{ $matiere->id }}">{{ $matiere->nom }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Laissez vide pour exporter toutes vos questions</p>
                </div>

                <!-- Bouton export Excel -->
                <form action="{{ route('enseignant.import-export.export') }}" method="GET">
                    <input type="hidden" name="matiere_id" id="matiere-excel" value="">
                    <button type="submit" 
                            onclick="document.getElementById('matiere-excel').value = document.getElementById('matiere-select').value"
                            class="w-full px-6 py-4 bg-green-600 text-white rounded-xl font-bold hover:bg-green-700 transition-all flex items-center justify-center shadow-lg">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Exporter en Excel
                    </button>
                </form>

                <!-- Bouton export PDF -->
                <form action="{{ route('enseignant.import-export.export-pdf') }}" method="GET">
                    <input type="hidden" name="matiere_id" id="matiere-pdf" value="">
                    <button type="submit" 
                            onclick="document.getElementById('matiere-pdf').value = document.getElementById('matiere-select').value"
                            class="w-full px-6 py-4 bg-red-600 text-white rounded-xl font-bold hover:bg-red-700 transition-all flex items-center justify-center shadow-lg">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        Exporter en PDF
                    </button>
                </form>
            </div>

            <!-- Info export -->
            <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                <p class="text-sm text-blue-800">
                    <strong>💡 Les fichiers contiendront :</strong><br>
                    • Toutes vos questions avec leurs détails<br>
                    • Les propositions de réponses pour les QCM<br>
                    • Les explications et tags<br>
                    • Excel : Format prêt pour réimport<br>
                    • PDF : Format lisible pour consultation
                </p>
            </div>
        </div>

        <!-- SECTION IMPORT -->
        <div class="bg-white rounded-2xl shadow-sm p-8">
            <div class="flex items-center space-x-3 mb-6">
                <div class="w-12 h-12 bg-iris-blue bg-opacity-10 rounded-xl flex items-center justify-center">
                    <svg class="h-6 w-6 text-iris-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">📥 Importer</h2>
                    <p class="text-sm text-gray-600">Ajoutez des questions depuis Excel</p>
                </div>
            </div>

            <form action="{{ route('enseignant.import-export.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <!-- Zone de fichier -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Fichier Excel (.xlsx, .xls, .csv)
                    </label>
                    <div class="relative">
                        <input type="file" 
                               name="file" 
                               accept=".xlsx,.xls,.csv"
                               required
                               class="w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-xl focus:border-iris-yellow focus:ring-2 focus:ring-iris-yellow focus:ring-opacity-20 transition-all cursor-pointer hover:border-iris-yellow"
                               onchange="updateFileName(this)">
                    </div>
                    <p class="text-xs text-gray-500 mt-1" id="file-name">Aucun fichier sélectionné</p>
                    @error('file')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Bouton import -->
                <button type="submit" 
                        class="w-full px-6 py-4 bg-iris-blue text-white rounded-xl font-bold hover:bg-blue-700 transition-all flex items-center justify-center shadow-lg">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    Importer les questions
                </button>
            </form>

            <!-- Info import -->
            <div class="mt-6 p-4 bg-yellow-50 rounded-lg">
                <p class="text-sm text-yellow-800">
                    <strong>⚠️ Important :</strong><br>
                    • Utilisez le template fourni ci-dessous<br>
                    • Les matières doivent exister dans la base<br>
                    • Format : Type, Matière, Énoncé, etc.<br>
                    • Max : 2 Mo
                </p>
            </div>
        </div>
    </div>

    <!-- SECTION TEMPLATE -->
    <div class="bg-gradient-to-r from-purple-600 to-purple-700 rounded-2xl shadow-lg p-8 text-white">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <div class="flex items-center space-x-3 mb-3">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="text-2xl font-bold">📋 Template Excel</h3>
                </div>
                <p class="text-purple-100 mb-4">
                    Téléchargez le fichier template avec des exemples de questions déjà remplies.<br>
                    Modifiez-le avec vos propres questions et réimportez-le facilement.
                </p>
                <ul class="space-y-2 text-purple-100">
                    <li class="flex items-center">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Format pré-configuré avec colonnes correctes
                    </li>
                    <li class="flex items-center">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        3 exemples de questions (QCM, QCM Multiple, Vrai/Faux)
                    </li>
                    <li class="flex items-center">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Prêt à être rempli et importé
                    </li>
                </ul>
            </div>
            <div class="ml-8">
                <a href="{{ route('enseignant.import-export.template') }}" 
                   class="inline-flex items-center px-8 py-4 bg-white text-purple-600 rounded-xl font-bold hover:bg-purple-50 transition-all shadow-lg">
                    <svg class="h-6 w-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Télécharger le Template
                </a>
            </div>
        </div>
    </div>

    <!-- GUIDE D'UTILISATION -->
    <div class="bg-white rounded-2xl shadow-sm p-8">
        <h3 class="text-2xl font-bold text-gray-900 mb-6">📖 Guide d'utilisation</h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Étape 1 -->
            <div class="text-center">
                <div class="w-16 h-16 bg-iris-blue bg-opacity-10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl font-bold text-iris-blue">1</span>
                </div>
                <h4 class="font-bold text-gray-900 mb-2">Télécharger le template</h4>
                <p class="text-sm text-gray-600">Cliquez sur "Télécharger le Template" pour obtenir le fichier Excel pré-formaté</p>
            </div>

            <!-- Étape 2 -->
            <div class="text-center">
                <div class="w-16 h-16 bg-iris-blue bg-opacity-10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl font-bold text-iris-blue">2</span>
                </div>
                <h4 class="font-bold text-gray-900 mb-2">Remplir vos questions</h4>
                <p class="text-sm text-gray-600">Ouvrez le fichier dans Excel, supprimez les exemples et ajoutez vos questions</p>
            </div>

            <!-- Étape 3 -->
            <div class="text-center">
                <div class="w-16 h-16 bg-iris-blue bg-opacity-10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl font-bold text-iris-blue">3</span>
                </div>
                <h4 class="font-bold text-gray-900 mb-2">Importer le fichier</h4>
                <p class="text-sm text-gray-600">Utilisez le formulaire d'import ci-dessus pour charger vos questions</p>
            </div>
        </div>

        <!-- Types de questions supportés -->
        <div class="mt-8 p-6 bg-gray-50 rounded-xl">
            <h4 class="font-bold text-gray-900 mb-4">✅ Types de questions supportés :</h4>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div class="text-center p-3 bg-white rounded-lg">
                    <p class="font-semibold text-sm">QCM Unique</p>
                </div>
                <div class="text-center p-3 bg-white rounded-lg">
                    <p class="font-semibold text-sm">QCM Multiple</p>
                </div>
                <div class="text-center p-3 bg-white rounded-lg">
                    <p class="font-semibold text-sm">Vrai/Faux</p>
                </div>
                <div class="text-center p-3 bg-white rounded-lg">
                    <p class="font-semibold text-sm">Réponse Courte</p>
                </div>
                <div class="text-center p-3 bg-white rounded-lg">
                    <p class="font-semibold text-sm">Texte Libre</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function updateFileName(input) {
    const fileName = input.files[0]?.name || 'Aucun fichier sélectionné';
    document.getElementById('file-name').textContent = fileName;
}
</script>
@endpush

@endsection