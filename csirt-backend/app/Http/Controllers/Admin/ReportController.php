<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Incident;
use App\Models\News;
use App\Models\User;
use App\Models\Contact;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,operator']);
    }

    /**
     * Display reports dashboard
     */
    public function index()
    {
        $reportTypes = [
            'incidents' => [
                'title' => 'Incident Reports',
                'description' => 'Generate reports on security incidents, trends, and response times',
                'icon' => 'fas fa-exclamation-triangle',
                'route' => 'admin.reports.incidents'
            ],
            'users' => [
                'title' => 'User Reports',
                'description' => 'Generate reports on user activities, registrations, and engagement',
                'icon' => 'fas fa-users',
                'route' => 'admin.reports.users'
            ],
            'security' => [
                'title' => 'Security Reports',
                'description' => 'Generate security analysis and threat intelligence reports',
                'icon' => 'fas fa-shield-alt',
                'route' => 'admin.reports.security'
            ],
            'performance' => [
                'title' => 'Performance Reports',
                'description' => 'Generate system performance and activity reports',
                'icon' => 'fas fa-chart-line',
                'route' => 'admin.reports.performance'
            ]
        ];

        return view('admin.reports.index', compact('reportTypes'));
    }

    /**
     * Generate incident reports
     */
    public function incidents(Request $request)
    {
        $dateRange = $this->getDateRange($request);
        
        try {
            // Incident statistics
            $stats = [
                'total' => Incident::whereBetween('detected_at', $dateRange)->count(),
                'open' => Incident::whereBetween('detected_at', $dateRange)->open()->count(),
                'resolved' => Incident::whereBetween('detected_at', $dateRange)->closed()->count(),
                'critical' => Incident::whereBetween('detected_at', $dateRange)->where('severity', 'critical')->count(),
                'avg_resolution_time' => $this->getAverageResolutionTime($dateRange)
            ];

            // Incidents by severity
            $bySeverity = Incident::select('severity', DB::raw('COUNT(*) as count'))
                ->whereBetween('detected_at', $dateRange)
                ->groupBy('severity')
                ->pluck('count', 'severity')
                ->toArray();

            // Incidents by category
            $byCategory = Incident::select('category', DB::raw('COUNT(*) as count'))
                ->whereBetween('detected_at', $dateRange)
                ->groupBy('category')
                ->orderByDesc('count')
                ->get();

            // Incidents trend (daily)
            $trend = $this->getIncidentTrend($dateRange);

            // Response time analysis
            $responseTimeStats = $this->getResponseTimeStats($dateRange);

            // Top assignees
            $topAssignees = Incident::with('assignedUser')
                ->select('assigned_to', DB::raw('COUNT(*) as count'))
                ->whereBetween('detected_at', $dateRange)
                ->whereNotNull('assigned_to')
                ->groupBy('assigned_to')
                ->orderByDesc('count')
                ->limit(10)
                ->get();

            $data = compact('stats', 'bySeverity', 'byCategory', 'trend', 'responseTimeStats', 'topAssignees', 'dateRange');

            if ($request->has('download')) {
                return $this->downloadReport('incidents', $data);
            }

            return view('admin.reports.incidents', $data);
            
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while generating the report. Please try again.');
        }
    }

    /**
     * Generate user reports
     */
    public function users(Request $request)
    {
        $dateRange = $this->getDateRange($request);
        
        try {
            // User statistics
            $stats = [
                'total' => User::whereBetween('created_at', $dateRange)->count(),
                'active' => User::whereBetween('created_at', $dateRange)->where('is_active', true)->count(),
                'inactive' => User::whereBetween('created_at', $dateRange)->where('is_active', false)->count(),
                'verified' => User::whereBetween('created_at', $dateRange)->whereNotNull('email_verified_at')->count()
            ];

            // Users by role
            $byRole = User::select('role', DB::raw('COUNT(*) as count'))
                ->whereBetween('created_at', $dateRange)
                ->groupBy('role')
                ->pluck('count', 'role')
                ->toArray();

            // Users by country
            $byCountry = User::select('country', DB::raw('COUNT(*) as count'))
                ->whereBetween('created_at', $dateRange)
                ->whereNotNull('country')
                ->groupBy('country')
                ->orderByDesc('count')
                ->limit(10)
                ->get();

            // Registration trend
            $registrationTrend = $this->getUserRegistrationTrend($dateRange);

            // User activity
            $userActivity = $this->getUserActivityStats($dateRange);

            // Most active users
            $mostActiveUsers = ActivityLog::with('user')
                ->select('user_id', DB::raw('COUNT(*) as activity_count'))
                ->whereBetween('created_at', $dateRange)
                ->whereNotNull('user_id')
                ->groupBy('user_id')
                ->orderByDesc('activity_count')
                ->limit(10)
                ->get();

            $data = compact('stats', 'byRole', 'byCountry', 'registrationTrend', 'userActivity', 'mostActiveUsers', 'dateRange');

            if ($request->has('download')) {
                return $this->downloadReport('users', $data);
            }

            return view('admin.reports.users', $data);
            
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while generating the report. Please try again.');
        }
    }

    /**
     * Generate security reports
     */
    public function security(Request $request)
    {
        $dateRange = $this->getDateRange($request);
        
        try {
            // Security statistics
            $stats = [
                'critical_incidents' => Incident::whereBetween('detected_at', $dateRange)
                    ->where('severity', 'critical')->count(),
                'high_incidents' => Incident::whereBetween('detected_at', $dateRange)
                    ->where('severity', 'high')->count(),
                'security_alerts' => News::whereBetween('created_at', $dateRange)
                    ->where('category', 'security_alert')->count(),
                'threat_intel' => News::whereBetween('created_at', $dateRange)
                    ->where('category', 'threat_intelligence')->count()
            ];

            // Threat landscape
            $threatLandscape = Incident::select('category', 'severity', DB::raw('COUNT(*) as count'))
                ->whereBetween('detected_at', $dateRange)
                ->groupBy('category', 'severity')
                ->get();

            // Security trends
            $securityTrends = $this->getSecurityTrends($dateRange);

            // Response effectiveness
            $responseEffectiveness = $this->getResponseEffectiveness($dateRange);

            // Top threat sources
            $topThreats = Incident::select('category', DB::raw('COUNT(*) as count'))
                ->whereBetween('detected_at', $dateRange)
                ->groupBy('category')
                ->orderByDesc('count')
                ->get();

            $data = compact('stats', 'threatLandscape', 'securityTrends', 'responseEffectiveness', 'topThreats', 'dateRange');

            if ($request->has('download')) {
                return $this->downloadReport('security', $data);
            }

            return view('admin.reports.security', $data);
            
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while generating the report. Please try again.');
        }
    }

    /**
     * Generate performance reports
     */
    public function performance(Request $request)
    {
        $dateRange = $this->getDateRange($request);
        
        try {
            // Performance statistics
            $stats = [
                'total_activities' => ActivityLog::whereBetween('created_at', $dateRange)->count(),
                'user_activities' => ActivityLog::whereBetween('created_at', $dateRange)
                    ->whereNotNull('user_id')->count(),
                'system_activities' => ActivityLog::whereBetween('created_at', $dateRange)
                    ->whereNull('user_id')->count(),
                'daily_average' => ActivityLog::whereBetween('created_at', $dateRange)->count() / 
                    max(1, Carbon::parse($dateRange[0])->diffInDays(Carbon::parse($dateRange[1])))
            ];

            // Activity trends
            $activityTrends = $this->getActivityTrends($dateRange);

            // System performance metrics
            $performanceMetrics = $this->getPerformanceMetrics($dateRange);

            // Popular actions
            $popularActions = ActivityLog::select('action', DB::raw('COUNT(*) as count'))
                ->whereBetween('created_at', $dateRange)
                ->groupBy('action')
                ->orderByDesc('count')
                ->limit(10)
                ->get();

            // Content engagement
            $contentEngagement = $this->getContentEngagement($dateRange);

            $data = compact('stats', 'activityTrends', 'performanceMetrics', 'popularActions', 'contentEngagement', 'dateRange');

            if ($request->has('download')) {
                return $this->downloadReport('performance', $data);
            }

            return view('admin.reports.performance', $data);
            
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while generating the report. Please try again.');
        }
    }

    /**
     * Get date range from request
     */
    private function getDateRange(Request $request)
    {
        $period = $request->get('period', 'last_30_days');
        
        switch ($period) {
            case 'today':
                return [now()->startOfDay(), now()->endOfDay()];
            case 'yesterday':
                return [now()->subDay()->startOfDay(), now()->subDay()->endOfDay()];
            case 'last_7_days':
                return [now()->subDays(7)->startOfDay(), now()->endOfDay()];
            case 'last_30_days':
                return [now()->subDays(30)->startOfDay(), now()->endOfDay()];
            case 'this_month':
                return [now()->startOfMonth(), now()->endOfMonth()];
            case 'last_month':
                return [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()];
            case 'this_year':
                return [now()->startOfYear(), now()->endOfYear()];
            case 'custom':
                return [
                    Carbon::parse($request->get('date_from', now()->subDays(30)))->startOfDay(),
                    Carbon::parse($request->get('date_to', now()))->endOfDay()
                ];
            default:
                return [now()->subDays(30)->startOfDay(), now()->endOfDay()];
        }
    }

    /**
     * Get average resolution time for incidents
     */
    private function getAverageResolutionTime($dateRange)
    {
        $resolvedIncidents = Incident::whereBetween('detected_at', $dateRange)
            ->whereNotNull('resolved_at')
            ->get();

        if ($resolvedIncidents->isEmpty()) {
            return 0;
        }

        $totalHours = $resolvedIncidents->sum(function ($incident) {
            return $incident->detected_at->diffInHours($incident->resolved_at);
        });

        return round($totalHours / $resolvedIncidents->count(), 2);
    }

    /**
     * Get incident trend data
     */
    private function getIncidentTrend($dateRange)
    {
        return Incident::select(
            DB::raw('DATE(detected_at) as date'),
            DB::raw('COUNT(*) as count')
        )
        ->whereBetween('detected_at', $dateRange)
        ->groupBy('date')
        ->orderBy('date')
        ->get();
    }

    /**
     * Get response time statistics
     */
    private function getResponseTimeStats($dateRange)
    {
        $incidents = Incident::whereBetween('detected_at', $dateRange)
            ->whereNotNull('resolved_at')
            ->get();

        if ($incidents->isEmpty()) {
            return ['average' => 0, 'median' => 0, 'fastest' => 0, 'slowest' => 0];
        }

        $times = $incidents->map(function ($incident) {
            return $incident->detected_at->diffInHours($incident->resolved_at);
        })->sort();

        return [
            'average' => round($times->average(), 2),
            'median' => $times->median(),
            'fastest' => $times->min(),
            'slowest' => $times->max()
        ];
    }

    /**
     * Get user registration trend
     */
    private function getUserRegistrationTrend($dateRange)
    {
        return User::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
        ->whereBetween('created_at', $dateRange)
        ->groupBy('date')
        ->orderBy('date')
        ->get();
    }

    /**
     * Get user activity statistics
     */
    private function getUserActivityStats($dateRange)
    {
        return [
            'logins' => ActivityLog::whereBetween('created_at', $dateRange)
                ->where('action', 'login')->count(),
            'incident_reports' => ActivityLog::whereBetween('created_at', $dateRange)
                ->where('action', 'created')
                ->where('model_type', 'App\\Models\\Incident')->count(),
            'profile_updates' => ActivityLog::whereBetween('created_at', $dateRange)
                ->where('action', 'updated')
                ->where('model_type', 'App\\Models\\User')->count()
        ];
    }

    /**
     * Get security trends
     */
    private function getSecurityTrends($dateRange)
    {
        return Incident::select(
            DB::raw('DATE(detected_at) as date'),
            'severity',
            DB::raw('COUNT(*) as count')
        )
        ->whereBetween('detected_at', $dateRange)
        ->whereIn('severity', ['high', 'critical'])
        ->groupBy('date', 'severity')
        ->orderBy('date')
        ->get();
    }

    /**
     * Get response effectiveness
     */
    private function getResponseEffectiveness($dateRange)
    {
        $total = Incident::whereBetween('detected_at', $dateRange)->count();
        $resolved = Incident::whereBetween('detected_at', $dateRange)->closed()->count();
        
        return [
            'resolution_rate' => $total > 0 ? round(($resolved / $total) * 100, 2) : 0,
            'avg_response_time' => $this->getAverageResolutionTime($dateRange)
        ];
    }

    /**
     * Get activity trends
     */
    private function getActivityTrends($dateRange)
    {
        return ActivityLog::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
        ->whereBetween('created_at', $dateRange)
        ->groupBy('date')
        ->orderBy('date')
        ->get();
    }

    /**
     * Get performance metrics
     */
    private function getPerformanceMetrics($dateRange)
    {
        return [
            'peak_activity_hour' => ActivityLog::select(DB::raw('HOUR(created_at) as hour'), DB::raw('COUNT(*) as count'))
                ->whereBetween('created_at', $dateRange)
                ->groupBy('hour')
                ->orderByDesc('count')
                ->first()?->hour ?? 0,
            'busiest_day' => ActivityLog::select(DB::raw('DAYNAME(created_at) as day'), DB::raw('COUNT(*) as count'))
                ->whereBetween('created_at', $dateRange)
                ->groupBy('day')
                ->orderByDesc('count')
                ->first()?->day ?? 'Unknown'
        ];
    }

    /**
     * Get content engagement metrics
     */
    private function getContentEngagement($dateRange)
    {
        return [
            'news_published' => News::whereBetween('published_at', $dateRange)->count(),
            'news_views' => News::whereBetween('published_at', $dateRange)->sum('views_count'),
            'contact_submissions' => Contact::whereBetween('created_at', $dateRange)->count()
        ];
    }

    /**
     * Download report as PDF or Excel
     */
    private function downloadReport($type, $data)
    {
        // This would implement PDF/Excel export functionality
        // For now, return a CSV export
        $csvData = $this->convertToCSV($type, $data);
        
        $filename = "{$type}_report_" . now()->format('Y_m_d_H_i_s') . '.csv';
        
        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }

    /**
     * Convert report data to CSV format
     */
    private function convertToCSV($type, $data)
    {
        $handle = fopen('php://temp', 'w');
        
        // Add basic report info
        fputcsv($handle, [ucfirst($type) . ' Report']);
        fputcsv($handle, ['Generated on: ' . now()->format('Y-m-d H:i:s')]);
        fputcsv($handle, ['Period: ' . $data['dateRange'][0]->format('Y-m-d') . ' to ' . $data['dateRange'][1]->format('Y-m-d')]);
        fputcsv($handle, []);
        
        // Add statistics
        fputcsv($handle, ['Statistics']);
        foreach ($data['stats'] as $key => $value) {
            fputcsv($handle, [ucwords(str_replace('_', ' ', $key)), $value]);
        }
        
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);
        
        return $csv;
    }
}