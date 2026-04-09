<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    /**
     * Afficher toutes les notifications
     */
    public function index()
    {
        $user = auth()->user();
        
        // Notifications non lues
        $nonLues = $user->unreadNotifications()
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Notifications lues (30 derniers jours)
        $lues = $user->notifications()
            ->whereNotNull('read_at')
            ->where('created_at', '>=', now()->subDays(30))
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('notifications.index', compact('nonLues', 'lues'));
    }

    /**
     * Marquer une notification comme lue
     */
    public function markAsRead($id)
    {
        $notification = auth()->user()
            ->notifications()
            ->findOrFail($id);
        
        $notification->markAsRead();
        
        // Rediriger vers l'URL de la notification si elle existe
        $url = $notification->data['url'] ?? null;
        
        if ($url) {
            return redirect($url);
        }
        
        return redirect()->back()->with('success', 'Notification marquée comme lue.');
    }

    /**
     * Marquer toutes les notifications comme lues
     */
    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        
        return redirect()->back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }

    /**
     * Supprimer une notification
     */
    public function destroy($id)
    {
        $notification = auth()->user()
            ->notifications()
            ->findOrFail($id);
        
        $notification->delete();
        
        return redirect()->back()->with('success', 'Notification supprimée.');
    }

    /**
     * Compter les notifications non lues (pour l'API/AJAX)
     */
    public function unreadCount()
    {
        $count = auth()->user()->unreadNotifications()->count();
        
        return response()->json(['count' => $count]);
    }
}