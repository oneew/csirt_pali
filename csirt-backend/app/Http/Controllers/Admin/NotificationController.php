<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,operator']);
    }

    /**
     * Display a listing of notifications
     */
    public function index(Request $request)
    {
        $query = Notification::with('user');

        // Apply filters
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            if ($request->status === 'read') {
                $query->where('is_read', true);
            } elseif ($request->status === 'unread') {
                $query->where('is_read', false);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $notifications = $query->paginate(20);
        $users = User::active()->orderBy('first_name')->get();

        // Get statistics
        $stats = [
            'total' => Notification::count(),
            'unread' => Notification::unread()->count(),
            'today' => Notification::whereDate('created_at', today())->count(),
            'this_week' => Notification::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count()
        ];

        return view('admin.notifications.index', compact('notifications', 'users', 'stats'));
    }

    /**
     * Show the form for creating a new notification
     */
    public function create()
    {
        $users = User::active()->orderBy('first_name')->get();
        return view('admin.notifications.create', compact('users'));
    }

    /**
     * Store a newly created notification
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'recipients' => 'required|string|in:all,specific,role',
            'user_ids' => 'required_if:recipients,specific|array',
            'user_ids.*' => 'exists:users,id',
            'role' => 'required_if:recipients,role|string|in:admin,operator,analyst,viewer',
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'type' => 'required|in:info,success,warning,error',
            'category' => 'required|in:system,incident,news,user,security',
            'send_immediately' => 'boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $recipients = [];

            // Determine recipients
            switch ($request->recipients) {
                case 'all':
                    $recipients = User::active()->pluck('id')->toArray();
                    break;
                case 'specific':
                    $recipients = $request->user_ids;
                    break;
                case 'role':
                    $recipients = User::active()->where('role', $request->role)->pluck('id')->toArray();
                    break;
            }

            $createdCount = 0;
            foreach ($recipients as $userId) {
                Notification::createForUser(
                    $userId,
                    $request->title,
                    $request->message,
                    $request->type,
                    $request->category
                );
                $createdCount++;
            }

            // Log activity
            ActivityLog::logActivity(
                'created',
                "Sent notification '{$request->title}' to {$createdCount} users"
            );

            return redirect()->route('admin.notifications.index')
                ->with('success', "Notification sent to {$createdCount} users successfully.");
                
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while sending notifications. Please try again.');
        }
    }

    /**
     * Display the specified notification
     */
    public function show(Notification $notification)
    {
        $notification->load('user');
        
        // Log viewing activity
        ActivityLog::logViewed($notification, "Viewed notification: {$notification->title}");

        return view('admin.notifications.show', compact('notification'));
    }

    /**
     * Show the form for editing the specified notification
     */
    public function edit(Notification $notification)
    {
        $users = User::active()->orderBy('first_name')->get();
        return view('admin.notifications.edit', compact('notification', 'users'));
    }

    /**
     * Update the specified notification
     */
    public function update(Request $request, Notification $notification)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'type' => 'required|in:info,success,warning,error',
            'category' => 'required|in:system,incident,news,user,security',
            'is_read' => 'boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $oldData = $notification->toArray();

            $notification->update([
                'title' => $request->title,
                'message' => $request->message,
                'type' => $request->type,
                'category' => $request->category,
                'is_read' => $request->boolean('is_read'),
                'read_at' => $request->boolean('is_read') && !$notification->is_read ? now() : $notification->read_at,
            ]);

            // Log activity
            ActivityLog::logUpdated($notification, $oldData, "Updated notification: {$notification->title}");

            return redirect()->route('admin.notifications.show', $notification)
                ->with('success', 'Notification updated successfully.');
                
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while updating the notification. Please try again.');
        }
    }

    /**
     * Remove the specified notification
     */
    public function destroy(Notification $notification)
    {
        try {
            // Log activity before deletion
            ActivityLog::logDeleted($notification, "Deleted notification: {$notification->title}");

            $notification->delete();

            return redirect()->route('admin.notifications.index')
                ->with('success', 'Notification deleted successfully.');
                
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while deleting the notification. Please try again.');
        }
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Notification $notification)
    {
        try {
            if (!$notification->is_read) {
                $oldData = ['is_read' => false, 'read_at' => null];
                
                $notification->markAsRead();

                // Log activity
                ActivityLog::logUpdated(
                    $notification, 
                    $oldData, 
                    "Marked notification as read: {$notification->title}"
                );
            }

            return response()->json(['success' => true]);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred.'], 500);
        }
    }

    /**
     * Mark all notifications as read for a user
     */
    public function markAllAsRead(Request $request)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id'
        ]);

        try {
            $query = Notification::unread();
            
            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }

            $notifications = $query->get();
            $count = 0;

            foreach ($notifications as $notification) {
                $notification->markAsRead();
                $count++;
            }

            // Log activity
            $userText = $request->filled('user_id') ? "for user ID {$request->user_id}" : "for all users";
            ActivityLog::logActivity(
                'bulk_read',
                "Marked {$count} notifications as read {$userText}"
            );

            return response()->json([
                'success' => true,
                'message' => "{$count} notifications marked as read."
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred.'], 500);
        }
    }

    /**
     * Bulk operations on notifications
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:mark_read,mark_unread,delete',
            'notification_ids' => 'required|array',
            'notification_ids.*' => 'exists:notifications,id'
        ]);

        try {
            $notifications = Notification::whereIn('id', $request->notification_ids)->get();
            $count = 0;

            foreach ($notifications as $notification) {
                switch ($request->action) {
                    case 'mark_read':
                        if (!$notification->is_read) {
                            $notification->markAsRead();
                            $count++;
                        }
                        break;
                    case 'mark_unread':
                        if ($notification->is_read) {
                            $notification->markAsUnread();
                            $count++;
                        }
                        break;
                    case 'delete':
                        $notification->delete();
                        $count++;
                        break;
                }
            }

            // Log bulk activity
            ActivityLog::logActivity(
                'bulk_' . $request->action,
                "Bulk {$request->action} on {$count} notifications"
            );

            return response()->json([
                'success' => true,
                'message' => "{$count} notifications were processed successfully."
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred.'], 500);
        }
    }

    /**
     * Get notifications for current user (API endpoint)
     */
    public function getUserNotifications(Request $request)
    {
        try {
            $user = auth()->user();
            
            $notifications = Notification::where('user_id', $user->id)
                ->latest()
                ->limit($request->get('limit', 10))
                ->get()
                ->map(function ($notification) {
                    return [
                        'id' => $notification->id,
                        'title' => $notification->title,
                        'message' => $notification->message,
                        'type' => $notification->type,
                        'category' => $notification->category,
                        'is_read' => $notification->is_read,
                        'time_ago' => $notification->time_ago,
                        'created_at' => $notification->created_at->format('Y-m-d H:i:s')
                    ];
                });

            $unreadCount = Notification::where('user_id', $user->id)
                ->unread()
                ->count();

            return response()->json([
                'notifications' => $notifications,
                'unread_count' => $unreadCount
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred.'], 500);
        }
    }

    /**
     * Export notifications to CSV
     */
    public function export(Request $request)
    {
        try {
            $query = Notification::with('user');

            // Apply same filters as index
            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }
            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }
            if ($request->filled('category')) {
                $query->where('category', $request->category);
            }

            $notifications = $query->get();

            $csvData = [];
            $csvData[] = [
                'Title', 'Message', 'User', 'Type', 'Category', 'Status',
                'Read At', 'Created At'
            ];

            foreach ($notifications as $notification) {
                $csvData[] = [
                    $notification->title,
                    $notification->message,
                    $notification->user ? $notification->user->full_name : 'Unknown',
                    $notification->type,
                    $notification->category,
                    $notification->is_read ? 'Read' : 'Unread',
                    $notification->read_at ? $notification->read_at->format('Y-m-d H:i:s') : '',
                    $notification->created_at->format('Y-m-d H:i:s')
                ];
            }

            $filename = 'notifications_export_' . now()->format('Y_m_d_H_i_s') . '.csv';
            
            $handle = fopen('php://temp', 'w');
            foreach ($csvData as $row) {
                fputcsv($handle, $row);
            }
            rewind($handle);
            $csv = stream_get_contents($handle);
            fclose($handle);

            return response($csv)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
                
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while exporting notifications. Please try again.');
        }
    }
}