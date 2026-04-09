<div class="relative" x-data="{ open: false }" @click.away="open = false">
    <!-- Bouton notifications -->
    <button @click="open = !open" class="relative p-2 text-gray-600 hover:text-iris-yellow transition-colors">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        
        @php
            $nbNonLues = auth()->user()->notificationsNonLues()->count();
        @endphp
        
        @if($nbNonLues > 0)
            <span class="absolute top-0 right-0 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full">
                {{ $nbNonLues > 9 ? '9+' : $nbNonLues }}
            </span>
        @endif
    </button>

    <!-- Dropdown -->
    <div x-show="open" 
         x-transition
         class="absolute right-0 mt-2 w-96 bg-white rounded-xl shadow-2xl border-2 border-gray-100 overflow-hidden z-50">
        
        <!-- En-tête -->
        <div class="bg-gradient-to-r from-iris-blue to-purple-600 px-6 py-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-white">Notifications</h3>
                @if($nbNonLues > 0)
                    <form action="{{ route('notifications.marquer-tout-lu') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-xs text-white hover:text-iris-yellow transition-colors">
                            Tout marquer comme lu
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Liste des notifications -->
        <div class="max-h-96 overflow-y-auto">
            @php
                $notifications = auth()->user()->notifications()->recentes()->limit(10)->get();
            @endphp

            @forelse($notifications as $notification)
                <a href="{{ $notification->lien ?? '#' }}" 
                   onclick="event.preventDefault(); marquerCommeLue({{ $notification->id }}, '{{ $notification->lien }}');"
                   class="block px-6 py-4 hover:bg-gray-50 transition-all border-b border-gray-100 {{ !$notification->est_lue ? 'bg-blue-50' : '' }}">
                    
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 text-2xl">
                            {{ $notification->icone ?? '📬' }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 {{ !$notification->est_lue ? 'font-bold' : '' }}">
                                {{ $notification->titre }}
                            </p>
                            <p class="text-sm text-gray-600 mt-1">
                                {{ $notification->message }}
                            </p>
                            <p class="text-xs text-gray-500 mt-2">
                                {{ $notification->created_at->diffForHumans() }}
                            </p>
                        </div>
                        @if(!$notification->est_lue)
                            <div class="w-2 h-2 bg-iris-blue rounded-full"></div>
                        @endif
                    </div>
                </a>
            @empty
                <div class="px-6 py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                    <p class="text-sm text-gray-600">Aucune notification</p>
                </div>
            @endforelse
        </div>

        <!-- Footer -->
        <div class="bg-gray-50 px-6 py-3 text-center border-t border-gray-200">
            <a href="{{ route('notifications.index') }}" class="text-sm font-semibold text-iris-blue hover:text-blue-700">
                Voir toutes les notifications →
            </a>
        </div>
    </div>
</div>

<script>
function marquerCommeLue(notificationId, lien) {
    fetch(`/notifications/${notificationId}/lue`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    }).then(() => {
        if (lien) {
            window.location.href = lien;
        }
    });
}
</script>