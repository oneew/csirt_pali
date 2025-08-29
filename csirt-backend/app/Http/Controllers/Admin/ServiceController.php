<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,operator']);
    }

    /**
     * Display a listing of services
     */
    public function index(Request $request)
    {
        $query = Service::query();

        // Apply filters
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if ($request->filled('featured')) {
            $query->where('is_featured', $request->featured);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->get('sort', 'order');
        $sortOrder = $request->get('order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $services = $query->paginate(15);

        return view('admin.services.index', compact('services'));
    }

    /**
     * Show the form for creating a new service
     */
    public function create()
    {
        return view('admin.services.create');
    }

    /**
     * Store a newly created service
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:services,slug',
            'description' => 'required|string|max:500',
            'content' => 'required|string',
            'category' => 'required|in:incident_response,threat_intelligence,training,consultation,assessment',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'features' => 'nullable|array',
            'features.*' => 'string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Handle icon upload
            $iconPath = null;
            if ($request->hasFile('icon')) {
                $iconPath = $request->file('icon')->store('services/icons', 'public');
            }

            // Process features
            $features = array_filter($request->features ?? []);

            $service = Service::create([
                'name' => $request->name,
                'slug' => $request->slug ?: Str::slug($request->name),
                'description' => $request->description,
                'content' => $request->content,
                'category' => $request->category,
                'icon' => $iconPath,
                'features' => $features,
                'contact_email' => $request->contact_email,
                'contact_phone' => $request->contact_phone,
                'order' => $request->order ?? 0,
                'is_active' => $request->boolean('is_active', true),
                'is_featured' => $request->boolean('is_featured'),
            ]);

            // Log activity
            ActivityLog::logCreated($service, "Created service: {$service->name}");

            return redirect()->route('admin.services.show', $service)
                ->with('success', 'Service created successfully.');
                
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while creating the service. Please try again.');
        }
    }

    /**
     * Display the specified service
     */
    public function show(Service $service)
    {
        // Log viewing activity
        ActivityLog::logViewed($service, "Viewed service: {$service->name}");

        return view('admin.services.show', compact('service'));
    }

    /**
     * Show the form for editing the specified service
     */
    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    /**
     * Update the specified service
     */
    public function update(Request $request, Service $service)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:services,slug,' . $service->id,
            'description' => 'required|string|max:500',
            'content' => 'required|string',
            'category' => 'required|in:incident_response,threat_intelligence,training,consultation,assessment',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'features' => 'nullable|array',
            'features.*' => 'string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'remove_icon' => 'boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $oldData = $service->toArray();

            // Handle icon update
            $iconPath = $service->icon;
            if ($request->boolean('remove_icon')) {
                if ($iconPath) {
                    Storage::disk('public')->delete($iconPath);
                }
                $iconPath = null;
            } elseif ($request->hasFile('icon')) {
                if ($iconPath) {
                    Storage::disk('public')->delete($iconPath);
                }
                $iconPath = $request->file('icon')->store('services/icons', 'public');
            }

            // Process features
            $features = array_filter($request->features ?? []);

            $service->update([
                'name' => $request->name,
                'slug' => $request->slug ?: Str::slug($request->name),
                'description' => $request->description,
                'content' => $request->content,
                'category' => $request->category,
                'icon' => $iconPath,
                'features' => $features,
                'contact_email' => $request->contact_email,
                'contact_phone' => $request->contact_phone,
                'order' => $request->order ?? 0,
                'is_active' => $request->boolean('is_active', true),
                'is_featured' => $request->boolean('is_featured'),
            ]);

            // Log activity
            ActivityLog::logUpdated($service, $oldData, "Updated service: {$service->name}");

            return redirect()->route('admin.services.show', $service)
                ->with('success', 'Service updated successfully.');
                
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while updating the service. Please try again.');
        }
    }

    /**
     * Remove the specified service
     */
    public function destroy(Service $service)
    {
        try {
            // Remove icon
            if ($service->icon) {
                Storage::disk('public')->delete($service->icon);
            }

            // Log activity before deletion
            ActivityLog::logDeleted($service, "Deleted service: {$service->name}");

            $service->delete();

            return redirect()->route('admin.services.index')
                ->with('success', 'Service deleted successfully.');
                
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while deleting the service. Please try again.');
        }
    }

    /**
     * Toggle active status of service
     */
    public function toggleActive(Service $service)
    {
        try {
            $oldData = ['is_active' => $service->is_active];
            
            $service->update(['is_active' => !$service->is_active]);

            $action = $service->is_active ? 'Activated' : 'Deactivated';

            // Log activity
            ActivityLog::logUpdated($service, $oldData, "{$action} service: {$service->name}");

            return response()->json(['success' => true, 'is_active' => $service->is_active]);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred.'], 500);
        }
    }

    /**
     * Toggle featured status of service
     */
    public function toggleFeatured(Service $service)
    {
        try {
            $oldData = ['is_featured' => $service->is_featured];
            
            $service->update(['is_featured' => !$service->is_featured]);

            $action = $service->is_featured ? 'Featured' : 'Unfeatured';

            // Log activity
            ActivityLog::logUpdated($service, $oldData, "{$action} service: {$service->name}");

            return response()->json(['success' => true, 'is_featured' => $service->is_featured]);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred.'], 500);
        }
    }

    /**
     * Bulk operations on services
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,feature,unfeature,delete',
            'service_ids' => 'required|array',
            'service_ids.*' => 'exists:services,id'
        ]);

        try {
            $services = Service::whereIn('id', $request->service_ids)->get();
            $count = 0;

            foreach ($services as $service) {
                switch ($request->action) {
                    case 'activate':
                        if (!$service->is_active) {
                            $service->update(['is_active' => true]);
                            $count++;
                        }
                        break;
                    case 'deactivate':
                        if ($service->is_active) {
                            $service->update(['is_active' => false]);
                            $count++;
                        }
                        break;
                    case 'feature':
                        if (!$service->is_featured) {
                            $service->update(['is_featured' => true]);
                            $count++;
                        }
                        break;
                    case 'unfeature':
                        if ($service->is_featured) {
                            $service->update(['is_featured' => false]);
                            $count++;
                        }
                        break;
                    case 'delete':
                        if ($service->icon) {
                            Storage::disk('public')->delete($service->icon);
                        }
                        $service->delete();
                        $count++;
                        break;
                }
            }

            // Log bulk activity
            ActivityLog::logActivity(
                'bulk_' . $request->action,
                "Bulk {$request->action} on {$count} services"
            );

            return response()->json([
                'success' => true,
                'message' => "{$count} services were processed successfully."
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred.'], 500);
        }
    }

    /**
     * Export services to CSV
     */
    public function export(Request $request)
    {
        try {
            $query = Service::query();

            // Apply same filters as index
            if ($request->filled('category')) {
                $query->where('category', $request->category);
            }
            if ($request->filled('status')) {
                if ($request->status === 'active') {
                    $query->where('is_active', true);
                } elseif ($request->status === 'inactive') {
                    $query->where('is_active', false);
                }
            }

            $services = $query->get();

            $csvData = [];
            $csvData[] = [
                'Name', 'Slug', 'Category', 'Description', 'Status', 'Featured',
                'Contact Email', 'Contact Phone', 'Order', 'Created At'
            ];

            foreach ($services as $service) {
                $csvData[] = [
                    $service->name,
                    $service->slug,
                    $service->category,
                    $service->description,
                    $service->is_active ? 'Active' : 'Inactive',
                    $service->is_featured ? 'Yes' : 'No',
                    $service->contact_email,
                    $service->contact_phone,
                    $service->order,
                    $service->created_at->format('Y-m-d H:i:s')
                ];
            }

            $filename = 'services_export_' . now()->format('Y_m_d_H_i_s') . '.csv';
            
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
            return back()->with('error', 'An error occurred while exporting services. Please try again.');
        }
    }
}