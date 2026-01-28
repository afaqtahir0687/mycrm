<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $query = Notification::where('user_id', auth()->id())->orderBy('created_at', 'desc');
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('unread_only') && $request->input('unread_only') == '1') {
            $query->whereNull('read_at');
        }
        
        if ($request->filled('status') && $request->input('status') == 'unread') {
            $query->whereNull('read_at');
        }
        
        $notifications = $query->paginate(50);
        
        $summary = [
            'total' => Notification::where('user_id', auth()->id())->count(),
            'unread' => Notification::where('user_id', auth()->id())->whereNull('read_at')->count(),
            'read' => Notification::where('user_id', auth()->id())->whereNotNull('read_at')->count(),
        ];
        
        return view('notifications.index', compact('notifications', 'summary'));
    }
    
    /**
     * Get unread notifications count (for API/header)
     */
    public function unreadCount()
    {
        $count = Notification::where('user_id', auth()->id())
            ->whereNull('read_at')
            ->count();
        
        return response()->json(['count' => $count]);
    }
    
    /**
     * Get recent notifications (for header dropdown)
     */
    public function recent()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->latest()
            ->limit(10)
            ->get();
        
        return response()->json($notifications);
    }
    
    /**
     * Mark notification as read
     */
    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id != auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $notification->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Mark all as read
     */
    public function markAllAsRead()
    {
        Notification::where('user_id', auth()->id())
            ->whereNull('read_at')
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Show notification details
     */
    public function show(Notification $notification)
    {
        if ($notification->user_id != auth()->id()) {
            abort(403, 'Unauthorized');
        }
        
        // Mark as read when viewing
        if (!$notification->read_at) {
            $notification->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
        
        return view('notifications.show', compact('notification'));
    }
    
    /**
     * Delete notification
     */
    public function destroy(Notification $notification)
    {
        if ($notification->user_id != auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $notification->delete();
        return redirect()->route('notifications.index')->with('success', 'Notification deleted.');
    }
}
