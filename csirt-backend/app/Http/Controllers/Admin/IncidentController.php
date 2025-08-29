<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Incident;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class IncidentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,operator,analyst']);
    }

    /**
     * Display a listing of incidents
     */
    public function index(Request $request)
    {
        $query = Incident::with(['assignedUser', 'reporter']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('incident_id', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->get('sort', 'detected_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $incidents = $query->paginate(15);
        $users = User::active()->orderBy('first_name')->get();

        return view('admin.incidents.index', compact('incidents', 'users'));
    }

    /**
     * Show the form for creating a new incident
     */
    public function create()
    {
        $users = User::active()->orderBy('first_name')->get();
        return view('admin.incidents.create', compact('users'));
    }

    /**
     * Store a newly created incident
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'severity' => 'required|in:low,medium,high,critical',
            'category' => 'required|in:malware,phishing,ddos,data_breach,unauthorized_access,vulnerability,other',
            'priority' => 'required|in:low,medium,high,critical',
            'assigned_to' => 'nullable|exists:users,id',
            'detected_at' => 'required|date',
            'impact_description' => 'nullable|string',
            'affected_systems' => 'nullable|array',
            'indicators_of_compromise' => 'nullable|array',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240', // 10MB max per file
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Handle file uploads
        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('incidents', 'public');
                $attachments[] = [
                    'original_name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                ];
            }
        }

        $incident = Incident::create([
            'title' => $request->title,
            'description' => $request->description,
            'severity' => $request->severity,
            'category' => $request->category,
            'priority' => $request->priority,
            'status' => 'open',
            'assigned_to' => $request->assigned_to,
            'reported_by' => auth()->id(),
            'detected_at' => $request->detected_at,
            'impact_description' => $request->impact_description,
            'affected_systems' => $request->affected_systems,
            'indicators_of_compromise' => $request->indicators_of_compromise,
            'attachments' => $attachments,
        ]);

        // Log activity
        ActivityLog::logCreated($incident, "Created incident: {$incident->incident_id}");

        // Create notifications
        if ($incident->assigned_to) {
            Notification::createIncidentNotification($incident->assigned_to, $incident);
        }

        // Notify all admins and operators for critical incidents
        if ($incident->severity === 'critical') {
            $adminUsers = User::whereIn('role', ['admin', 'operator'])->get();
            foreach ($adminUsers as $user) {
                if ($user->id !== $incident->assigned_to) {
                    Notification::createIncidentNotification($user->id, $incident);
                }
            }
        }

        return redirect()->route('admin.incidents.show', $incident)
            ->with('success', 'Incident created successfully.');
    }

    /**
     * Display the specified incident
     */
    public function show(Incident $incident)
    {
        $incident->load(['assignedUser', 'reporter']);
        
        // Log viewing activity
        ActivityLog::logViewed($incident, "Viewed incident: {$incident->incident_id}");

        return view('admin.incidents.show', compact('incident'));
    }

    /**
     * Show the form for editing the specified incident
     */
    public function edit(Incident $incident)
    {
        $users = User::active()->orderBy('first_name')->get();
        return view('admin.incidents.edit', compact('incident', 'users'));
    }

    /**
     * Update the specified incident
     */
    public function update(Request $request, Incident $incident)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'severity' => 'required|in:low,medium,high,critical',
            'category' => 'required|in:malware,phishing,ddos,data_breach,unauthorized_access,vulnerability,other',
            'status' => 'required|in:open,investigating,resolved,closed',
            'priority' => 'required|in:low,medium,high,critical',
            'assigned_to' => 'nullable|exists:users,id',
            'detected_at' => 'required|date',
            'resolved_at' => 'nullable|date',
            'impact_description' => 'nullable|string',
            'affected_systems' => 'nullable|array',
            'indicators_of_compromise' => 'nullable|array',
            'remediation_steps' => 'nullable|string',
            'lessons_learned' => 'nullable|string',
            'new_attachments' => 'nullable|array',
            'new_attachments.*' => 'file|max:10240',
            'remove_attachments' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $oldData = $incident->toArray();

        // Handle new file uploads
        $attachments = $incident->attachments ?? [];
        if ($request->hasFile('new_attachments')) {
            foreach ($request->file('new_attachments') as $file) {
                $path = $file->store('incidents', 'public');
                $attachments[] = [
                    'original_name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                ];
            }
        }

        // Remove specified attachments
        if ($request->filled('remove_attachments')) {
            foreach ($request->remove_attachments as $index) {
                if (isset($attachments[$index])) {
                    Storage::disk('public')->delete($attachments[$index]['path']);
                    unset($attachments[$index]);
                }
            }
            $attachments = array_values($attachments); // Re-index array
        }

        // Set resolved_at automatically if status changed to resolved/closed
        $resolvedAt = $request->resolved_at;
        if (in_array($request->status, ['resolved', 'closed']) && !$resolvedAt) {
            $resolvedAt = now();
        } elseif (!in_array($request->status, ['resolved', 'closed'])) {
            $resolvedAt = null;
        }

        $incident->update([
            'title' => $request->title,
            'description' => $request->description,
            'severity' => $request->severity,
            'category' => $request->category,
            'status' => $request->status,
            'priority' => $request->priority,
            'assigned_to' => $request->assigned_to,
            'detected_at' => $request->detected_at,
            'resolved_at' => $resolvedAt,
            'impact_description' => $request->impact_description,
            'affected_systems' => $request->affected_systems,
            'indicators_of_compromise' => $request->indicators_of_compromise,
            'remediation_steps' => $request->remediation_steps,
            'lessons_learned' => $request->lessons_learned,
            'attachments' => $attachments,
        ]);

        // Log activity
        ActivityLog::logUpdated($incident, $oldData, "Updated incident: {$incident->incident_id}");

        // Notify assigned user if assignment changed
        if ($incident->wasChanged('assigned_to') && $incident->assigned_to) {
            Notification::createForUser(
                $incident->assigned_to,
                'Incident Assigned',
                "You have been assigned to incident: {$incident->incident_id}",
                'info',
                'incident',
                ['incident_id' => $incident->id]
            );
        }

        return redirect()->route('admin.incidents.show', $incident)
            ->with('success', 'Incident updated successfully.');
    }

    /**
     * Remove the specified incident
     */
    public function destroy(Incident $incident)
    {
        // Remove associated files
        if ($incident->attachments) {
            foreach ($incident->attachments as $attachment) {
                Storage::disk('public')->delete($attachment['path']);
            }
        }

        // Log activity before deletion
        ActivityLog::logDeleted($incident, "Deleted incident: {$incident->incident_id}");

        $incident->delete();

        return redirect()->route('admin.incidents.index')
            ->with('success', 'Incident deleted successfully.');
    }

    /**
     * Assign incident to user
     */
    public function assign(Request $request, Incident $incident)
    {
        $request->validate([
            'assigned_to' => 'required|exists:users,id'
        ]);

        $oldData = ['assigned_to' => $incident->assigned_to];
        
        $incident->update(['assigned_to' => $request->assigned_to]);

        // Log activity
        ActivityLog::logUpdated(
            $incident, 
            $oldData, 
            "Assigned incident {$incident->incident_id} to user"
        );

        // Notify assigned user
        Notification::createForUser(
            $request->assigned_to,
            'Incident Assigned',
            "You have been assigned to incident: {$incident->incident_id}",
            'info',
            'incident',
            ['incident_id' => $incident->id]
        );

        return response()->json(['success' => true]);
    }

    /**
     * Update incident status
     */
    public function updateStatus(Request $request, Incident $incident)
    {
        $request->validate([
            'status' => 'required|in:open,investigating,resolved,closed'
        ]);

        $oldData = ['status' => $incident->status];
        
        $updateData = ['status' => $request->status];
        
        // Set resolved_at if status is resolved or closed
        if (in_array($request->status, ['resolved', 'closed']) && !$incident->resolved_at) {
            $updateData['resolved_at'] = now();
        }

        $incident->update($updateData);

        // Log activity
        ActivityLog::logUpdated(
            $incident, 
            $oldData, 
            "Changed incident {$incident->incident_id} status to {$request->status}"
        );

        return response()->json(['success' => true]);
    }

    /**
     * Export incidents to CSV
     */
    public function export(Request $request)
    {
        $query = Incident::with(['assignedUser', 'reporter']);

        // Apply same filters as index
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        // ... other filters ...

        $incidents = $query->get();

        $csvData = [];
        $csvData[] = [
            'Incident ID', 'Title', 'Severity', 'Category', 'Status', 'Priority',
            'Assigned To', 'Reported By', 'Detected At', 'Resolved At', 'Days Open'
        ];

        foreach ($incidents as $incident) {
            $csvData[] = [
                $incident->incident_id,
                $incident->title,
                $incident->severity,
                $incident->category,
                $incident->status,
                $incident->priority,
                $incident->assignedUser ? $incident->assignedUser->full_name : 'Unassigned',
                $incident->reporter->full_name,
                $incident->detected_at->format('Y-m-d H:i:s'),
                $incident->resolved_at ? $incident->resolved_at->format('Y-m-d H:i:s') : '',
                $incident->days_open
            ];
        }

        $filename = 'incidents_export_' . now()->format('Y_m_d_H_i_s') . '.csv';
        
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
}