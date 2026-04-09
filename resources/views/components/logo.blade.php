@props(['size' => 'md', 'variant' => 'default'])

@php
    $sizes = [
        'sm' => 'w-8 h-8',
        'md' => 'w-12 h-12',
        'lg' => 'w-16 h-16',
        'xl' => 'w-20 h-20',
    ];
    
    $sizeClass = $sizes[$size] ?? $sizes['md'];
@endphp

@if($variant === 'icon')
    {{-- Version icône uniquement --}}
    <div class="{{ $sizeClass }} bg-gradient-to-br from-iris-yellow to-yellow-500 rounded-2xl flex items-center justify-center shadow-lg">
        <div class="w-3/5 h-3/5 bg-iris-black-900 rounded-lg flex items-center justify-center">
            <span class="text-white font-bold" style="font-size: 65%;">I</span>
        </div>
    </div>
@else
    {{-- Version complète avec texte --}}
    <div class="flex items-center space-x-3">
        <div class="{{ $sizeClass }} bg-gradient-to-br from-iris-yellow to-yellow-500 rounded-2xl flex flex-col shadow-lg overflow-hidden">
            <div class="flex-1 flex items-center justify-center bg-iris-black-900">
                <span class="text-white font-bold text-lg">IRIS</span>
            </div>
            <div class="h-1/3 bg-iris-yellow flex items-center justify-center">
                <span class="text-iris-black-900 font-bold text-xs">EXAM</span>
            </div>
        </div>
        @if($variant === 'full')
            <div class="flex flex-col">
                <span class="text-2xl font-bold text-iris-black-900">IRIS <span class="text-iris-yellow">EXAM</span></span>
                <span class="text-xs text-gray-500 uppercase tracking-wider">Plateforme d'évaluation</span>
            </div>
        @endif
    </div>
@endif