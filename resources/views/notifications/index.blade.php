@extends('layouts.app')

@section('title', 'Notifications')

@push('styles')
<style>
    .notification-card {
        transition: all 0.3s ease;
    }
    
    .notification-card:hover {
        transform: translateX(4px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .notification-unread {
        background: #EFF6FF;
        border-left: 4px solid #3B82F6;
    }
    
    .dark .notification-unread {
        background: rgba(30, 58, 138, 0.2);
        border-left-color: #60A5FA;
    }
    
    .notification-read {
        background: white;
        border-left: 4px solid #E5E7EB;
        opacity: 0.8;
    }
    
    .dark .notification-read {
        background: #374151;
        border-left-color: #4B5563;
    }
    
    .notification-icon {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }
    
    .icon-blue { background: #DBEAFE; }
    .icon-green { background: #D1FAE5; }
    .icon-orange { background: #FED7AA; }
    .icon-red { background: #FEE2E2; }
    .icon-purple { background: #E9D5FF; }
    
    .dark .icon-blue { background: #1E3A8A; }
    .dark .icon-green { background: #065F46; }
    .dark .icon-orange { background: #92400E; }
    .dark .icon-red { background: #7F1D1D; }
    .dark .icon-purple { background: #581C87; }
</style>
@endpush

@section('content')
<div class="space-y-6">
    
    <!-- En-tête -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                    🔔 Notifications
                </h1>
                @php
                    // Compter les notifications non lues (compatible ancien et nouveau format)
                    $unreadCount = $notifications->filter(function($notif) {
                        return is_null($notif->lue_at) && !$notif->est_lue;
                    })->count();
                @endphp
                <p class="text-gray-600 dark:text-gray-400">
                    {{ $unreadCount }} notification(s) non lue(s)
                </p>
            </div>
            
            <div class="flex items-center space-x-3">
                @if($unreadCount > 0)
                    <form method="POST" action="{{ route('notifications.readAll') }}">
                        @csrf
                        <button type="submit" 
                                class="px-4 py-2 bg-iris-blue text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                            ✓ Tout marquer comme lu
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Liste des notifications -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
        
        @if($notifications->isEmpty())
            <div class="text-center py-16">
                <div class="text-6xl mb-4">🔔</div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                    Aucune notification
                </h3>
                <p class="text-gray-600 dark:text-gray-400">
                    Vous n'avez aucune notification pour le moment.
                </p>
            </div>
        @else
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($notifications as $notification)
                    @php
                        // ✅ Support ancien et nouveau format
                        $isUnread = $notification->estNonLue();
                        
                        // Nouveau format (avec data JSON)
                        if (!is_null($notification->data) && is_array($notification->data)) {
                            $titre = $notification->data['titre'] ?? 'Notification';
                            $message = $notification->data['message'] ?? '';
                            $icon = $notification->data['icon'] ?? '📬';
                            $color = $notification->data['color'] ?? 'blue';
                            $url = $notification->data['url'] ?? null;
                            $type = $notification->data['type'] ?? null;
                            $data = $notification->data;
                        } 
                        // Ancien format (colonnes séparées)
                        else {
                            $titre = $notification->titre ?? 'Notification';
                            $message = $notification->message ?? '';
                            $icon = $notification->icone ?? '📬';
                            $color = 'blue';
                            $url = $notification->lien ?? null;
                            $type = null;
                            $data = null;
                        }
                    @endphp
                    
                    <div class="notification-card {{ $isUnread ? 'notification-unread' : 'notification-read' }} p-6">
                        <div class="flex items-start space-x-4">
                            
                            <!-- Icône -->
                            <div class="notification-icon icon-{{ $color }}">
                                {{ $icon }}
                            </div>
                            
                            <!-- Contenu -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between mb-2">
                                    <div class="flex-1">
                                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                                            {{ $titre }}
                                            @if($isUnread)
                                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                    Nouveau
                                                </span>
                                            @endif
                                        </h3>
                                        
                                        <p class="text-sm text-gray-700 dark:text-gray-300 mt-1">
                                            {{ $message }}
                                        </p>
                                    </div>
                                </div>
                                
                                <!-- Détails supplémentaires selon le type (nouveau format uniquement) -->
                                @if($type && $data)
                                    <div class="mt-3">
                                        @if($type === 'resultat_disponible')
                                            <div class="flex items-center space-x-4 text-sm">
                                                <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full font-medium">
                                                    📊 Note: {{ number_format($data['note_obtenue'] ?? 0, 1) }}/{{ $data['note_maximale'] ?? 20 }}
                                                </span>
                                                <span class="px-3 py-1 bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200 rounded-full font-medium">
                                                    📈 {{ number_format($data['pourcentage'] ?? 0, 1) }}%
                                                </span>
                                            </div>
                                        @elseif($type === 'examen_dans_24h' || $type === 'nouvel_examen')
                                            <div class="flex flex-wrap gap-2 text-xs">
                                                @if(isset($data['matiere']))
                                                    <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded">
                                                        📚 {{ $data['matiere'] }}
                                                    </span>
                                                @endif
                                                @if(isset($data['duree']))
                                                    <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded">
                                                        ⏱️ {{ $data['duree'] }} min
                                                    </span>
                                                @endif
                                                @if(isset($data['date_debut']))
                                                    <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded">
                                                        📅 {{ $data['date_debut'] }}
                                                    </span>
                                                @endif
                                            </div>
                                        @elseif($type === 'examen_expire_bientot')
                                            <div class="flex items-center space-x-4 text-sm">
                                                @if(isset($data['heures_restantes']))
                                                    <span class="px-3 py-1 bg-orange-100 dark:bg-orange-900 text-orange-800 dark:text-orange-200 rounded-full font-medium">
                                                        ⚠️ Expire dans {{ $data['heures_restantes'] }}h
                                                    </span>
                                                @endif
                                                @if(isset($data['date_fin']))
                                                    <span class="text-gray-600 dark:text-gray-400">
                                                        📅 {{ $data['date_fin'] }}
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @endif
                                
                                <!-- Date et Actions -->
                                <div class="flex items-center justify-between mt-4 pt-3 border-t border-gray-200 dark:border-gray-700">
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </div>
                                    
                                    <div class="flex items-center space-x-3">
                                        <!-- Bouton Voir -->
                                        @if($url)
                                            <a href="{{ $url }}" 
                                               onclick="event.preventDefault(); document.getElementById('read-form-{{ $notification->id }}').submit();"
                                               class="px-3 py-1.5 bg-iris-blue text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                                                👁️ Voir
                                            </a>
                                            <form id="read-form-{{ $notification->id }}" 
                                                  method="POST" 
                                                  action="{{ route('notifications.read', $notification->id) }}" 
                                                  class="hidden">
                                                @csrf
                                                <input type="hidden" name="redirect_url" value="{{ $url }}">
                                            </form>
                                        @endif
                                        
                                        <!-- Marquer comme lu (si non lu) -->
                                        @if($isUnread)
                                            <form method="POST" action="{{ route('notifications.read', $notification->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white font-medium">
                                                    ✓ Marquer lu
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <!-- Supprimer -->
                                        <form method="POST" action="{{ route('notifications.destroy', $notification->id) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    onclick="return confirm('Supprimer cette notification ?')"
                                                    class="text-sm text-red-600 hover:text-red-700 font-medium">
                                                🗑️
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            @if($notifications->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $notifications->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection