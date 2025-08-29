<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Contact;
use App\Models\Incident;
use App\Models\News;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,operator']);
    }

    /**
     * Show the admin dashboard
     */
    public function index()
    {
        $stats = $this->getDashboardStats();
        $charts = $this->getChartData();
        $recentActivities = $this->getRecentActivities();
        $criticalIncidents = $this->getCriticalIncidents();
        $pendingContacts = $this->getPendingContacts();

        return view('admin.dashboard', compact(
            'stats', 
            'charts', 
            'recentActivities', 
            'criticalIncidents',
            'pendingContacts'
        ));
    }

    /**
     * Get dashboard statistics
     */
    private function getDashboardStats()
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();

        // Users statistics
        $totalUsers = User::count();
        $activeUsers = User::active()->count();
        $newUsersThisMonth = User::where('created_at', '>=', $currentMonth)->count();
        $newUsersLastMonth = User::whereBetween('created_at', [$lastMonth, $currentMonth])->count();
        $usersGrowth = $newUsersLastMonth > 0 
            ? (($newUsersThisMonth - $newUsersLastMonth) / $newUsersLastMonth) * 100 
            : 0;

        // Incidents statistics
        $totalIncidents = Incident::count();
        $openIncidents = Incident::open()->count();
        $criticalIncidents = Incident::where('severity', 'critical')->open()->count();
        $newIncidentsThisMonth = Incident::where('detected_at', '>=', $currentMonth)->count();
        $newIncidentsLastMonth = Incident::whereBetween('detected_at', [$lastMonth, $currentMonth])->count();
        $incidentsGrowth = $newIncidentsLastMonth > 0 
            ? (($newIncidentsThisMonth - $newIncidentsLastMonth) / $newIncidentsLastMonth) * 100 
            : 0;

        // News statistics
        $totalNews = News::count();
        $publishedNews = News::published()->count();
        $draftNews = News::draft()->count();
        $newNewsThisMonth = News::where('created_at', '>=', $currentMonth)->count();
        $newNewsLastMonth = News::whereBetween('created_at', [$lastMonth, $currentMonth])->count();
        $newsGrowth = $newNewsLastMonth > 0 
            ? (($newNewsThisMonth - $newNewsLastMonth) / $newNewsLastMonth) * 100 
            : 0;

        return [
            'users' => [
                'total' => $totalUsers,
                'active' => $activeUsers,
                'growth' => round($usersGrowth, 1),
                'trend' => $usersGrowth >= 0 ? 'up' : 'down'
            ],
            'incidents' => [
                'total' => $totalIncidents,
                'open' => $openIncidents,
                'critical' => $criticalIncidents,
                'growth' => round($incidentsGrowth, 1),
                'trend' => $incidentsGrowth <= 0 ? 'up' : 'down' // Lower is better for incidents
            ],
            'news' => [
                'total' => $totalNews,
                'published' => $publishedNews,
                'draft' => $draftNews,
                'growth' => round($newsGrowth, 1),
                'trend' => $newsGrowth >= 0 ? 'up' : 'down'
            ]
        ];
    }

    /**
     * Get chart data for dashboard
     */
    private function getChartData()
    {
        $last30Days = collect(range(29, 0))->map(function ($daysBack) {
            return Carbon::now()->subDays($daysBack)->format('Y-m-d');
        });

        // Incidents trend (last 30 days)
        $incidentsTrend = $last30Days->map(function ($date) {
            return Incident::whereDate('detected_at', $date)->count();
        });

        // Users registration trend (last 30 days)
        $usersTrend = $last30Days->map(function ($date) {
            return User::whereDate('created_at', $date)->count();
        });

        // Incidents by severity
        $incidentsBySeverity = [
            'low' => Incident::where('severity', 'low')->count(),
            'medium' => Incident::where('severity', 'medium')->count(),
            'high' => Incident::where('severity', 'high')->count(),
            'critical' => Incident::where('severity', 'critical')->count(),
        ];

        // Incidents by status
        $incidentsByStatus = [
            'open' => Incident::where('status', 'open')->count(),
            'investigating' => Incident::where('status', 'investigating')->count(),
            'resolved' => Incident::where('status', 'resolved')->count(),
            'closed' => Incident::where('status', 'closed')->count(),
        ];

        return [
            'incidents_trend' => [
                'labels' => $last30Days->map(function ($date) {
                    return Carbon::parse($date)->format('M d');
                })->toArray(),
                'data' => $incidentsTrend->toArray()
            ],
            'users_trend' => [
                'labels' => $last30Days->map(function ($date) {
                    return Carbon::parse($date)->format('M d');
                })->toArray(),
                'data' => $usersTrend->toArray()
            ],
            'incidents_by_severity' => $incidentsBySeverity,
            'incidents_by_status' => $incidentsByStatus
        ];
    }

    /**
     * Get recent activities
     */
    private function getRecentActivities()
    {
        return ActivityLog::with('user')
            ->latest()
            ->limit(10)
            ->get()
            ->map(function ($activity) {
                return [
                    'id' => $activity->id,
                    'user' => $activity->user ? $activity->user->full_name : 'System',
                    'action' => $activity->action,
                    'description' => $activity->description,
                    'time_ago' => $activity->time_ago,
                    'created_at' => $activity->created_at->format('Y-m-d H:i:s')
                ];
            });
    }

    /**
     * Get critical incidents
     */
    private function getCriticalIncidents()
    {
        return Incident::with(['assignedUser', 'reporter'])
            ->where('severity', 'critical')
            ->open()
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($incident) {
                return [
                    'id' => $incident->id,
                    'incident_id' => $incident->incident_id,
                    'title' => $incident->title,
                    'status' => $incident->status,
                    'assigned_to' => $incident->assignedUser ? $incident->assignedUser->full_name : 'Unassigned',
                    'detected_at' => $incident->detected_at->format('Y-m-d H:i:s'),
                    'days_open' => $incident->days_open
                ];
            });
    }

    /**
     * Get pending contacts
     */
    private function getPendingContacts()
    {
        return Contact::pending()
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($contact) {
                return [
                    'id' => $contact->id,
                    'name' => $contact->name,
                    'email' => $contact->email,
                    'organization' => $contact->organization,
                    'contact_type' => $contact->contact_type,
                    'created_at' => $contact->created_at->format('Y-m-d H:i:s'),
                    'time_ago' => $contact->created_at->diffForHumans()
                ];
            });
    }

    /**
     * API endpoint for dashboard stats
     */
    public function getStats()
    {
        return response()->json([
            'stats' => $this->getDashboardStats(),
            'charts' => $this->getChartData()
        ]);
    }

    /**
     * API endpoint for recent activities
     */
    public function getRecentActivitiesApi()
    {
        return response()->json([
            'activities' => $this->getRecentActivities()
        ]);
    }

    /**
     * API endpoint for notifications count
     */
    public function getNotificationsCount()
    {
        $unreadCount = Notification::where('user_id', auth()->id())
            ->unread()
            ->count();

        return response()->json([
            'unread_count' => $unreadCount
        ]);
    }
}