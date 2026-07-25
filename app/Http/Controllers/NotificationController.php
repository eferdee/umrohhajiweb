<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;

/**
 * Dipakai bareng oleh admin & customer — keduanya sama-sama Notifiable lewat
 * model User, jadi cukup satu controller yang memilih view sesuai role.
 */
class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->paginate(15);

        $view = Auth::user()->isAdmin() ? 'admin.notifications.index' : 'customer.notifications.index';

        return view($view, compact('notifications'));
    }

    public function markAsRead(DatabaseNotification $notification, Request $request)
    {
        abort_unless($notification->notifiable_id === Auth::id()
            && $notification->notifiable_type === Auth::user()::class, 403);

        $notification->markAsRead();

        return $request->has('redirect') && $notification->data['url']
            ? redirect($notification->data['url'])
            : back();
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }
}
