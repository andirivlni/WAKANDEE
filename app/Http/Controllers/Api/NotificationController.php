<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get unread notifications count.
     */
    public function unread()
    {
        $count = Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->count();

        return response()->json([
            'success' => true,
            'unread' => $count
        ]);
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $notification->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read'
        ]);
    }
}
