@props(['type' => 'info'])

@php
    $styles = [
        'success' => 'bg-green-50 border-green-500 text-green-800 dark:bg-green-900 dark:text-green-200',
        'error' => 'bg-red-50 border-red-500 text-red-800 dark:bg-red-900 dark:text-red-200',
        'warning' => 'bg-orange-50 border-orange-500 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
        'info' => 'bg-blue-50 border-blue-500 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
    ];
    
    $icons = [
        'success' => '✅',
        'error' => '❌',
        'warning' => '⚠️',
        'info' => 'ℹ️',
    ];
@endphp

<div x-data="{ show: true }" 
     x-show="show"
     x-transition
     class="rounded-xl border-l-4 p-4 {{ $styles[$type] }} shadow-sm mb-4">
    <div class="flex items-start">
        <span class="text-2xl mr-3">{{ $icons[$type] }}</span>
        <div class="flex-1">
            <p class="font-semibold">{{ $slot }}</p>
        </div>
        <button @click="show = false" class="ml-3 opacity-50 hover:opacity-100">
            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
        </button>
    </div>
</div>