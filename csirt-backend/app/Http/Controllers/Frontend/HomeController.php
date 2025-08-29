<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Incident;
use App\Models\News;
use App\Models\Service;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    /**
     * Show the main homepage
     */
    public function index()
    {
        try {
            // Get featured news
            $featuredNews = News::published()
                ->featured()
                ->with('author')
                ->latest('published_at')
                ->limit(3)
                ->get();

            // Get recent news
            $recentNews = News::published()
                ->with('author')
                ->latest('published_at')
                ->limit(6)
                ->get();

            // Get featured services
            $featuredServices = Service::active()
                ->featured()
                ->ordered()
                ->limit(4)
                ->get();

            // Get recent gallery items
            $recentGallery = Gallery::featured()
                ->with('uploader')
                ->ordered()
                ->limit(6)
                ->get();

            // Get basic statistics
            $stats = [
                'total_incidents' => Incident::count(),
                'resolved_incidents' => Incident::closed()->count(),
                'active_threats' => Incident::where('severity', 'critical')->open()->count(),
                'total_members' => User::active()->count()
            ];

            return view('frontend.index', compact(
                'featuredNews',
                'recentNews', 
                'featuredServices',
                'recentGallery',
                'stats'
            ));
        } catch (\Exception $e) {
            // Fallback to basic view if there are database issues
            return view('frontend.index');
        }
    }

    /**
     * Show the profile/about page
     */
    public function profile()
    {
        try {
            // Get organization information
            $organizationInfo = [
                'name' => Setting::get('organization_name', 'CSIRT PALI'),
                'description' => Setting::get('organization_description', ''),
                'mission' => Setting::get('organization_mission', ''),
                'vision' => Setting::get('organization_vision', ''),
                'address' => Setting::get('organization_address', ''),
                'phone' => Setting::get('organization_phone', ''),
                'email' => Setting::get('organization_email', ''),
            ];

            // Get team members
            $teamMembers = User::active()
                ->whereIn('role', ['admin', 'operator', 'analyst'])
                ->orderBy('role')
                ->orderBy('first_name')
                ->get();

            // Get services offered
            $services = Service::active()
                ->ordered()
                ->get();

            return view('frontend.profile', compact(
                'organizationInfo',
                'teamMembers',
                'services'
            ));
        } catch (\Exception $e) {
            return view('frontend.profile');
        }
    }

    /**
     * Show user dashboard (authenticated users)
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        try {
            // Get user's reported incidents
            $reportedIncidents = Incident::with(['assignedUser'])
                ->where('reported_by', $user->id)
                ->latest('detected_at')
                ->limit(5)
                ->get();

            // Get user's assigned incidents (if they can be assigned)
            $assignedIncidents = collect();
            if (in_array($user->role, ['admin', 'operator', 'analyst'])) {
                $assignedIncidents = Incident::with(['reporter'])
                    ->where('assigned_to', $user->id)
                    ->open()
                    ->latest('detected_at')
                    ->limit(5)
                    ->get();
            }

            // Get recent news
            $recentNews = News::published()
                ->with('author')
                ->latest('published_at')
                ->limit(5)
                ->get();

            // Get user statistics
            $userStats = [
                'incidents_reported' => Incident::where('reported_by', $user->id)->count(),
                'incidents_assigned' => Incident::where('assigned_to', $user->id)->count(),
                'incidents_resolved' => Incident::where('assigned_to', $user->id)
                    ->whereIn('status', ['resolved', 'closed'])->count(),
                'unread_notifications' => $user->notifications()->unread()->count() ?? 0
            ];

            return view('frontend.dashboard', compact(
                'user',
                'reportedIncidents',
                'assignedIncidents',
                'recentNews',
                'userStats'
            ));
        } catch (\Exception $e) {
            return view('frontend.dashboard', compact('user'));
        }
    }
}