<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityLogController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,operator']);
    }

    /**
     * Display a listing of activity logs
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with('user');

        // Apply filters
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('action', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $logs = $query->paginate(25);

        // Get filter options
        $users = User::active()->orderBy('first_name')->get();
        $actions = ActivityLog::distinct()->pluck('action')->filter()->sort();
        $modelTypes = ActivityLog::distinct()->pluck('model_type')->filter()->map(function($type) {
            return class_basename($type);
        })->sort();

        // Get statistics
        $stats = [
            'total' => ActivityLog::count(),
            'today' => ActivityLog::whereDate('created_at', today())->count(),
            'this_week' => ActivityLog::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => ActivityLog::whereMonth('created_at', now()->month)->count()
        ];

        return view('admin.activity-logs.index', compact('logs', 'users', 'actions', 'modelTypes', 'stats'));
    }

    /**
     * Display the specified activity log
     */
    public function show(ActivityLog $activityLog)
    {
        $activityLog->load('user');
        
        return view('admin.activity-logs.show', compact('activityLog'));
    }

    /**
     * Remove the specified activity log
     */
    public function destroy(ActivityLog $activityLog)
    {
        try {
            $activityLog->delete();

            return redirect()->route('admin.activity-logs.index')
                ->with('success', 'Activity log deleted successfully.');
                
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while deleting the activity log. Please try again.');
        }
    }

    /**
     * Bulk operations on activity logs
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete',
            'log_ids' => 'required|array',
            'log_ids.*' => 'exists:activity_logs,id'
        ]);

        try {
            $count = 0;

            if ($request->action === 'delete') {
                $count = ActivityLog::whereIn('id', $request->log_ids)->count();
                ActivityLog::whereIn('id', $request->log_ids)->delete();
            }

            return response()->json([
                'success' => true,
                'message' => "{$count} activity logs were processed successfully."
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred.'], 500);
        }
    }

    /**
     * Clean up old activity logs
     */
    public function cleanup(Request $request)
    {
        $request->validate([
            'days' => 'required|integer|min:1|max:365'
        ]);

        try {
            $cutoffDate = now()->subDays($request->days);
            $deletedCount = ActivityLog::where('created_at', '<', $cutoffDate)->count();
            
            ActivityLog::where('created_at', '<', $cutoffDate)->delete();

            // Log the cleanup activity
            ActivityLog::logActivity(
                'cleanup',
                "Cleaned up {$deletedCount} activity logs older than {$request->days} days"
            );

            return response()->json([
                'success' => true,
                'message' => "Deleted {$deletedCount} activity logs older than {$request->days} days."
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred.'], 500);
        }
    }

    /**
     * Get activity statistics
     */
    public function statistics(Request $request)
    {
        try {
            $days = $request->get('days', 30);
            $startDate = now()->subDays($days)->startOfDay();

            // Activity by day
            $dailyActivity = ActivityLog::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

            // Activity by action
            $actionStats = ActivityLog::select('action', DB::raw('COUNT(*) as count'))
                ->where('created_at', '>=', $startDate)
                ->groupBy('action')
                ->orderByDesc('count')
                ->get();

            // Activity by user
            $userStats = ActivityLog::with('user')
                ->select('user_id', DB::raw('COUNT(*) as count'))
                ->where('created_at', '>=', $startDate)
                ->whereNotNull('user_id')
                ->groupBy('user_id')
                ->orderByDesc('count')
                ->limit(10)
                ->get()
                ->map(function($stat) {
                    return [
                        'user' => $stat->user ? $stat->user->full_name : 'Unknown',
                        'count' => $stat->count
                    ];
                });

            // Activity by model type
            $modelStats = ActivityLog::select(
                DB::raw('model_type'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', $startDate)
            ->whereNotNull('model_type')
            ->groupBy('model_type')
            ->orderByDesc('count')
            ->get()
            ->map(function($stat) {
                return [
                    'model' => class_basename($stat->model_type),
                    'count' => $stat->count
                ];
            });

            return response()->json([
                'daily_activity' => $dailyActivity,
                'action_stats' => $actionStats,
                'user_stats' => $userStats,
                'model_stats' => $modelStats
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred.'], 500);
        }
    }

    /**
     * Export activity logs to CSV
     */
    public function export(Request $request)
    {
        try {
            $query = ActivityLog::with('user');

            // Apply same filters as index
            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }
            if ($request->filled('action')) {
                $query->where('action', $request->action);
            }
            if ($request->filled('model_type')) {
                $query->where('model_type', $request->model_type);
            }
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $logs = $query->orderBy('created_at', 'desc')->get();

            $csvData = [];
            $csvData[] = [
                'Date', 'User', 'Action', 'Model', 'Description', 'IP Address', 'User Agent'
            ];

            foreach ($logs as $log) {
                $csvData[] = [
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->user ? $log->user->full_name : 'System',
                    $log->action,
                    $log->model_type ? class_basename($log->model_type) : '',
                    $log->description,
                    $log->ip_address,
                    $log->user_agent
                ];
            }

            $filename = 'activity_logs_export_' . now()->format('Y_m_d_H_i_s') . '.csv';
            
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
            return back()->with('error', 'An error occurred while exporting activity logs. Please try again.');
        }
    }

    /**
     * Get recent activity for dashboard widget
     */
    public function getRecentActivity(Request $request)
    {
        try {
            $limit = $request->get('limit', 10);
            
            $activities = ActivityLog::with('user')
                ->latest()
                ->limit($limit)
                ->get()
                ->map(function ($activity) {
                    return [
                        'id' => $activity->id,
                        'user' => $activity->user ? $activity->user->full_name : 'System',
                        'action' => $activity->action,
                        'description' => $activity->description,
                        'model' => $activity->model_type ? class_basename($activity->model_type) : null,
                        'time_ago' => $activity->time_ago,
                        'created_at' => $activity->created_at->format('Y-m-d H:i:s')
                    ];
                });

            return response()->json(['activities' => $activities]);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred.'], 500);
        }
    }
}