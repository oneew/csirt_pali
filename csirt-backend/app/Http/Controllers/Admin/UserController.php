<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Apply filters
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('organization', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $users = $query->paginate(15);

        // Get filter options
        $departments = User::whereNotNull('department')
            ->distinct()
            ->pluck('department')
            ->sort();

        return view('admin.users.index', compact('users', 'departments'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        $roles = ['admin', 'operator', 'analyst', 'viewer'];
        $permissions = $this->getAvailablePermissions();
        return view('admin.users.create', compact('roles', 'permissions'));
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:100',
            'position' => 'nullable|string|max:100',
            'organization' => 'required|string|max:255',
            'country' => 'required|string|max:100',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,operator,analyst,viewer',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
            'is_active' => 'boolean',
            'bio' => 'nullable|string|max:1000',
            'avatar' => 'nullable|image|max:2048', // 2MB max
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Handle avatar upload
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'department' => $request->department,
            'position' => $request->position,
            'organization' => $request->organization,
            'country' => $request->country,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'permissions' => $request->permissions ?? [],
            'is_active' => $request->boolean('is_active', true),
            'bio' => $request->bio,
            'avatar' => $avatarPath,
            'email_verified_at' => now(), // Auto-verify admin created users
        ]);

        // Log activity
        ActivityLog::logCreated($user, "Created user: {$user->full_name}");

        // Send welcome notification to the new user
        Notification::createForUser(
            $user->id,
            'Welcome to CSIRT PALI',
            'Your account has been created. You can now access the system.',
            'success',
            'user'
        );

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        // Load relationships
        $user->load(['reportedIncidents', 'assignedIncidents', 'news', 'galleries']);

        // Get user statistics
        $stats = [
            'incidents_reported' => $user->reportedIncidents()->count(),
            'incidents_assigned' => $user->assignedIncidents()->count(),
            'incidents_resolved' => $user->assignedIncidents()->whereIn('status', ['resolved', 'closed'])->count(),
            'news_articles' => $user->news()->count(),
            'news_published' => $user->news()->published()->count(),
            'galleries_uploaded' => $user->galleries()->count(),
        ];

        // Get recent activities
        $recentActivities = ActivityLog::where('user_id', $user->id)
            ->latest()
            ->limit(10)
            ->get();

        // Log viewing activity
        ActivityLog::logViewed($user, "Viewed user profile: {$user->full_name}");

        return view('admin.users.show', compact('user', 'stats', 'recentActivities'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        $roles = ['admin', 'operator', 'analyst', 'viewer'];
        $permissions = $this->getAvailablePermissions();
        return view('admin.users.edit', compact('user', 'roles', 'permissions'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:100',
            'position' => 'nullable|string|max:100',
            'organization' => 'required|string|max:255',
            'country' => 'required|string|max:100',
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,operator,analyst,viewer',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
            'is_active' => 'boolean',
            'bio' => 'nullable|string|max:1000',
            'avatar' => 'nullable|image|max:2048',
            'remove_avatar' => 'boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $oldData = $user->toArray();

        // Handle avatar
        $avatarPath = $user->avatar;
        if ($request->boolean('remove_avatar')) {
            if ($avatarPath) {
                Storage::disk('public')->delete($avatarPath);
            }
            $avatarPath = null;
        } elseif ($request->hasFile('avatar')) {
            if ($avatarPath) {
                Storage::disk('public')->delete($avatarPath);
            }
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        $updateData = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'department' => $request->department,
            'position' => $request->position,
            'organization' => $request->organization,
            'country' => $request->country,
            'role' => $request->role,
            'permissions' => $request->permissions ?? [],
            'is_active' => $request->boolean('is_active', true),
            'bio' => $request->bio,
            'avatar' => $avatarPath,
        ];

        // Update password if provided
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        // Log activity
        ActivityLog::logUpdated($user, $oldData, "Updated user: {$user->full_name}");

        // Notify user if their account status changed
        if ($oldData['is_active'] !== $user->is_active) {
            $message = $user->is_active 
                ? 'Your account has been activated.' 
                : 'Your account has been deactivated.';
            
            Notification::createForUser(
                $user->id,
                'Account Status Changed',
                $message,
                $user->is_active ? 'success' : 'warning',
                'user'
            );
        }

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        // Prevent self-deletion
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        // Remove avatar
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Log activity before deletion
        ActivityLog::logDeleted($user, "Deleted user: {$user->full_name}");

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Toggle user active status
     */
    public function toggleStatus(User $user)
    {
        // Prevent deactivating self
        if ($user->id === auth()->id()) {
            return response()->json(['error' => 'You cannot deactivate your own account.'], 400);
        }

        $oldData = ['is_active' => $user->is_active];
        
        $user->update(['is_active' => !$user->is_active]);

        $action = $user->is_active ? 'Activated' : 'Deactivated';

        // Log activity
        ActivityLog::logUpdated($user, $oldData, "{$action} user: {$user->full_name}");

        // Notify user
        $message = $user->is_active 
            ? 'Your account has been activated.' 
            : 'Your account has been deactivated.';
        
        Notification::createForUser(
            $user->id,
            'Account Status Changed',
            $message,
            $user->is_active ? 'success' : 'warning',
            'user'
        );

        return response()->json(['success' => true, 'is_active' => $user->is_active]);
    }

    /**
     * Update user role
     */
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,operator,analyst,viewer'
        ]);

        // Prevent changing own role
        if ($user->id === auth()->id()) {
            return response()->json(['error' => 'You cannot change your own role.'], 400);
        }

        $oldData = ['role' => $user->role];
        
        $user->update(['role' => $request->role]);

        // Log activity
        ActivityLog::logUpdated($user, $oldData, "Changed user role to {$request->role}: {$user->full_name}");

        // Notify user
        Notification::createForUser(
            $user->id,
            'Role Changed',
            "Your role has been changed to {$request->role}.",
            'info',
            'user'
        );

        return response()->json(['success' => true]);
    }

    /**
     * Update user permissions
     */
    public function updatePermissions(Request $request, User $user)
    {
        $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'string'
        ]);

        $oldData = ['permissions' => $user->permissions];
        
        $user->update(['permissions' => $request->permissions ?? []]);

        // Log activity
        ActivityLog::logUpdated($user, $oldData, "Updated permissions for user: {$user->full_name}");

        return response()->json(['success' => true]);
    }

    /**
     * Bulk operations on users
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        // Remove current user from bulk operations
        $userIds = array_filter($request->user_ids, function($id) {
            return $id != auth()->id();
        });

        $users = User::whereIn('id', $userIds)->get();
        $count = 0;

        foreach ($users as $user) {
            switch ($request->action) {
                case 'activate':
                    if (!$user->is_active) {
                        $user->update(['is_active' => true]);
                        Notification::createForUser(
                            $user->id,
                            'Account Activated',
                            'Your account has been activated.',
                            'success',
                            'user'
                        );
                        $count++;
                    }
                    break;
                case 'deactivate':
                    if ($user->is_active) {
                        $user->update(['is_active' => false]);
                        Notification::createForUser(
                            $user->id,
                            'Account Deactivated',
                            'Your account has been deactivated.',
                            'warning',
                            'user'
                        );
                        $count++;
                    }
                    break;
                case 'delete':
                    if ($user->avatar) {
                        Storage::disk('public')->delete($user->avatar);
                    }
                    $user->delete();
                    $count++;
                    break;
            }
        }

        // Log bulk activity
        ActivityLog::logActivity(
            'bulk_' . $request->action,
            "Bulk {$request->action} on {$count} users"
        );

        return response()->json([
            'success' => true,
            'message' => "{$count} users were {$request->action}d successfully."
        ]);
    }

    /**
     * Export users to CSV
     */
    public function export(Request $request)
    {
        $query = User::query();

        // Apply same filters as index
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        // ... other filters ...

        $users = $query->get();

        $csvData = [];
        $csvData[] = [
            'Name', 'Email', 'Phone', 'Organization', 'Department', 'Position',
            'Country', 'Role', 'Status', 'Last Login', 'Created At'
        ];

        foreach ($users as $user) {
            $csvData[] = [
                $user->full_name,
                $user->email,
                $user->phone,
                $user->organization,
                $user->department,
                $user->position,
                $user->country,
                $user->role,
                $user->is_active ? 'Active' : 'Inactive',
                $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i:s') : 'Never',
                $user->created_at->format('Y-m-d H:i:s')
            ];
        }

        $filename = 'users_export_' . now()->format('Y_m_d_H_i_s') . '.csv';
        
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
    }

    /**
     * Get available permissions
     */
    private function getAvailablePermissions()
    {
        return [
            'incidents' => [
                'incidents.view',
                'incidents.create', 
                'incidents.edit',
                'incidents.delete',
                'incidents.assign'
            ],
            'news' => [
                'news.view',
                'news.create',
                'news.edit', 
                'news.delete',
                'news.publish'
            ],
            'users' => [
                'users.view',
                'users.create',
                'users.edit',
                'users.delete'
            ],
            'settings' => [
                'settings.view',
                'settings.edit'
            ],
            'reports' => [
                'reports.view',
                'reports.export'
            ]
        ];
    }
}