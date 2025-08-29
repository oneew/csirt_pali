<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,operator']);
    }

    /**
     * Display a listing of contacts
     */
    public function index(Request $request)
    {
        $query = Contact::query();

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('contact_type')) {
            $query->where('contact_type', $request->contact_type);
        }

        if ($request->filled('country')) {
            $query->where('country', $request->country);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('organization', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $contacts = $query->paginate(15);

        // Get filter options
        $countries = Contact::whereNotNull('country')
            ->distinct()
            ->pluck('country')
            ->sort();

        return view('admin.contacts.index', compact('contacts', 'countries'));
    }

    /**
     * Show the form for creating a new contact
     */
    public function create()
    {
        return view('admin.contacts.create');
    }

    /**
     * Store a newly created contact
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'organization' => 'required|string|max:255',
            'position' => 'nullable|string|max:100',
            'country' => 'required|string|max:100',
            'contact_type' => 'required|in:member,partner,external,emergency',
            'message' => 'required|string',
            'status' => 'required|in:pending,contacted,resolved',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $contact = Contact::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'organization' => $request->organization,
                'position' => $request->position,
                'country' => $request->country,
                'contact_type' => $request->contact_type,
                'message' => $request->message,
                'status' => $request->status,
                'notes' => $request->notes,
                'contacted_at' => $request->status === 'contacted' ? now() : null,
            ]);

            // Log activity
            ActivityLog::logCreated($contact, "Created contact: {$contact->name}");

            return redirect()->route('admin.contacts.show', $contact)
                ->with('success', 'Contact created successfully.');
                
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while creating the contact. Please try again.');
        }
    }

    /**
     * Display the specified contact
     */
    public function show(Contact $contact)
    {
        // Log viewing activity
        ActivityLog::logViewed($contact, "Viewed contact: {$contact->name}");

        return view('admin.contacts.show', compact('contact'));
    }

    /**
     * Show the form for editing the specified contact
     */
    public function edit(Contact $contact)
    {
        return view('admin.contacts.edit', compact('contact'));
    }

    /**
     * Update the specified contact
     */
    public function update(Request $request, Contact $contact)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'organization' => 'required|string|max:255',
            'position' => 'nullable|string|max:100',
            'country' => 'required|string|max:100',
            'contact_type' => 'required|in:member,partner,external,emergency',
            'message' => 'required|string',
            'status' => 'required|in:pending,contacted,resolved',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $oldData = $contact->toArray();

            // Set contacted_at if status changed to contacted
            $contactedAt = $contact->contacted_at;
            if ($request->status === 'contacted' && $contact->status !== 'contacted') {
                $contactedAt = now();
            }

            $contact->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'organization' => $request->organization,
                'position' => $request->position,
                'country' => $request->country,
                'contact_type' => $request->contact_type,
                'message' => $request->message,
                'status' => $request->status,
                'notes' => $request->notes,
                'contacted_at' => $contactedAt,
            ]);

            // Log activity
            ActivityLog::logUpdated($contact, $oldData, "Updated contact: {$contact->name}");

            return redirect()->route('admin.contacts.show', $contact)
                ->with('success', 'Contact updated successfully.');
                
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while updating the contact. Please try again.');
        }
    }

    /**
     * Remove the specified contact
     */
    public function destroy(Contact $contact)
    {
        try {
            // Log activity before deletion
            ActivityLog::logDeleted($contact, "Deleted contact: {$contact->name}");

            $contact->delete();

            return redirect()->route('admin.contacts.index')
                ->with('success', 'Contact deleted successfully.');
                
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while deleting the contact. Please try again.');
        }
    }

    /**
     * Mark contact as contacted
     */
    public function markContacted(Request $request, Contact $contact)
    {
        $request->validate([
            'notes' => 'nullable|string'
        ]);

        try {
            $oldData = ['status' => $contact->status, 'contacted_at' => $contact->contacted_at];
            
            $contact->markAsContacted($request->notes);

            // Log activity
            ActivityLog::logUpdated(
                $contact, 
                $oldData, 
                "Marked contact as contacted: {$contact->name}"
            );

            return response()->json(['success' => true]);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred.'], 500);
        }
    }

    /**
     * Mark contact as resolved
     */
    public function markResolved(Request $request, Contact $contact)
    {
        $request->validate([
            'notes' => 'nullable|string'
        ]);

        try {
            $oldData = ['status' => $contact->status];
            
            $contact->markAsResolved($request->notes);

            // Log activity
            ActivityLog::logUpdated(
                $contact, 
                $oldData, 
                "Marked contact as resolved: {$contact->name}"
            );

            return response()->json(['success' => true]);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred.'], 500);
        }
    }

    /**
     * Add notes to contact
     */
    public function addNotes(Request $request, Contact $contact)
    {
        $request->validate([
            'notes' => 'required|string'
        ]);

        try {
            $oldData = ['notes' => $contact->notes];
            
            $contact->addNotes($request->notes);

            // Log activity
            ActivityLog::logUpdated(
                $contact, 
                $oldData, 
                "Added notes to contact: {$contact->name}"
            );

            return response()->json(['success' => true]);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred.'], 500);
        }
    }

    /**
     * Bulk operations on contacts
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:mark_contacted,mark_resolved,delete',
            'contact_ids' => 'required|array',
            'contact_ids.*' => 'exists:contacts,id'
        ]);

        try {
            $contacts = Contact::whereIn('id', $request->contact_ids)->get();
            $count = 0;

            foreach ($contacts as $contact) {
                switch ($request->action) {
                    case 'mark_contacted':
                        if ($contact->status === 'pending') {
                            $contact->markAsContacted();
                            $count++;
                        }
                        break;
                    case 'mark_resolved':
                        if ($contact->status !== 'resolved') {
                            $contact->markAsResolved();
                            $count++;
                        }
                        break;
                    case 'delete':
                        $contact->delete();
                        $count++;
                        break;
                }
            }

            // Log bulk activity
            ActivityLog::logActivity(
                'bulk_' . $request->action,
                "Bulk {$request->action} on {$count} contacts"
            );

            return response()->json([
                'success' => true,
                'message' => "{$count} contacts were processed successfully."
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred.'], 500);
        }
    }

    /**
     * Export contacts to CSV
     */
    public function export(Request $request)
    {
        try {
            $query = Contact::query();

            // Apply same filters as index
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            if ($request->filled('contact_type')) {
                $query->where('contact_type', $request->contact_type);
            }
            if ($request->filled('country')) {
                $query->where('country', $request->country);
            }

            $contacts = $query->get();

            $csvData = [];
            $csvData[] = [
                'Name', 'Email', 'Phone', 'Organization', 'Position', 'Country',
                'Contact Type', 'Status', 'Message', 'Contacted At', 'Created At'
            ];

            foreach ($contacts as $contact) {
                $csvData[] = [
                    $contact->name,
                    $contact->email,
                    $contact->phone,
                    $contact->organization,
                    $contact->position,
                    $contact->country,
                    $contact->contact_type,
                    $contact->status,
                    $contact->message,
                    $contact->contacted_at ? $contact->contacted_at->format('Y-m-d H:i:s') : '',
                    $contact->created_at->format('Y-m-d H:i:s')
                ];
            }

            $filename = 'contacts_export_' . now()->format('Y_m_d_H_i_s') . '.csv';
            
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
            return back()->with('error', 'An error occurred while exporting contacts. Please try again.');
        }
    }
}