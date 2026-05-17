<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get the latest notifications for the authenticated user.
     */
    public function getRecent()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 419);
        }

        $notifications = $user->unreadNotifications()->take(5)->get()->map(function ($notif) {
            // Default icon and message mapping based on notification type
            $icon = 'bi bi-bell-fill';
            $msg = 'Notifikasi baru';
            $url = '#';

            if (isset($notif->data['message'])) {
                $msg = $notif->data['message'];
            }

            if (isset($notif->data['url'])) {
                $url = $notif->data['url'];
            }

            // Custom icon/route mapping for known notification classes
            if (str_contains($notif->type, 'AccountCompletion')) {
                $icon = 'bi bi-person-check-fill';
                $url = route('users.index') . '?search=' . ($notif->data['user_id'] ?? '');
            }

            return [
                'id' => $notif->id,
                'message' => $msg,
                'icon' => $icon,
                'url' => $url,
                'time' => $notif->created_at->diffForHumans(),
            ];
        });

        return response()->json([
            'success' => true,
            'unread_count' => $user->unreadNotifications()->count(),
            'notifications' => $notifications,
        ]);
    }

    /**
     * Mark all unread notifications as read.
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 419);
        }

        $user->unreadNotifications->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi ditandai telah dibaca.',
        ]);
    }

    /**
     * Mark a specific notification as read.
     */
    public function markAsRead($id)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 419);
        }

        $notification = $user->notifications()->where('id', $id)->first();
        if ($notification) {
            $notification->markAsRead();
        }

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi ditandai dibaca.',
        ]);
    }
}
