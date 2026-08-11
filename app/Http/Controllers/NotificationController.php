<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()
            ->notifications()
            ->latest()
            ->take(50)
            ->get();

        return Auth::user()->isAdmin()
            ? view('admin.notifications.index', compact('notifications'))
            : view('notifications.index', compact('notifications'));
    }

    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return back();
    }

    public function markRead(Request $request, string $id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        $url = $request->input('redirect');

        return $url ? redirect($url) : back();
    }

    public function destroy(Request $request, string $id)
    {
        Auth::user()->notifications()
            ->whereKey($id)
            ->delete();

        return back()->with('success', 'Notification deleted.');
    }

    public function destroyMany(Request $request)
    {
        $ids = $request->input('ids', []);

        if (is_string($ids)) {
            $ids = array_values(array_filter(array_map('trim', explode(',', $ids))));
        }

        if (is_array($ids) && count($ids) > 0) {
            Auth::user()->notifications()
                ->whereIn('id', $ids)
                ->delete();
        }

        $count = is_array($ids) ? count($ids) : 0;

        return back()->with('success', $count . ' notification(s) deleted.');
    }
}
