@props(['id', 'title' => 'Confirmation', 'message' => 'Êtes-vous sûr ?', 'confirmText' => 'Confirmer', 'cancelText' => 'Annuler'])

<div x-data="{ open: false }" 
     @open-modal.window="if ($event.detail.id === '{{ $id }}') open = true"
     x-show="open"
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto">
    
    <!-- Overlay -->
    <div x-show="open" 
         x-transition
         class="fixed inset-0 bg-gray-500 bg-opacity-75" 
         @click="open = false">
    </div>

    <!-- Modal -->
    <div class="flex items-center justify-center min-h-screen px-4">
        <div x-show="open"
             x-transition
             class="relative bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-lg w-full p-6">
            
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 rounded-full bg-orange-100 dark:bg-orange-900 flex items-center justify-center">
                        <svg class="h-6 w-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                </div>
                
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">
                        {{ $title }}
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ $message }}
                    </p>
                </div>
            </div>
            
            <div class="mt-6 flex space-x-3 justify-end">
                <button type="button" 
                        @click="open = false"
                        class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 font-medium">
                    {{ $cancelText }}
                </button>
                <button type="button" 
                        @click="$dispatch('confirm-action', { id: '{{ $id }}' }); open = false"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium">
                    {{ $confirmText }}
                </button>
            </div>
        </div>
    </div>
</div>
```

---

## ✅ RÉSULTAT FINAL

Tu dois avoir cette structure :
```
resources/
└── views/
    └── components/
        ├── alert.blade.php           ← Créé ✅
        ├── loading.blade.php         ← Créé ✅
        └── confirm-modal.blade.php   ← Créé ✅