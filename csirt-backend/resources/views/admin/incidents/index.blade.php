@extends('admin.layouts.app')

@section('title', 'Incidents Management')
@section('page-title', 'Incidents Management')

@push('styles')
<link rel="stylesheet" href="{{ asset('frontend/css/incident.css') }}">
@endpush

@section('content')
<div class="content-header">
    <div class="header-actions">
        <a href="{{ route('admin.incidents.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Create New Incident
        </a>
        <div class="filter-group">
            <select id="severityFilter" class="form-select">
                <option value="">All Severities</option>
                <option value="critical">Critical</option>
                <option value="high">High</option>
                <option value="medium">Medium</option>
                <option value="low">Low</option>
            </select>
            <select id="statusFilter" class="form-select">
                <option value="">All Statuses</option>
                <option value="open">Open</option>
                <option value="investigating">Investigating</option>
                <option value="resolved">Resolved</option>
                <option value="closed">Closed</option>
            </select>
        </div>
    </div>
</div>

<div class="incidents-grid">
    @forelse($incidents ?? [] as $incident)
        <div class="incident-card" data-severity="{{ $incident->severity }}" data-status="{{ $incident->status }}">
            <div class="incident-header">
                <div class="incident-id">{{ $incident->incident_id }}</div>
                <div class="incident-badges">
                    <span class="badge badge-{{ $incident->severity_badge }}">{{ ucfirst($incident->severity) }}</span>
                    <span class="badge badge-{{ $incident->status_badge }}">{{ ucfirst($incident->status) }}</span>
                </div>
            </div>
            
            <div class="incident-content">
                <h3 class="incident-title">{{ $incident->title }}</h3>
                <p class="incident-description">{{ Str::limit($incident->description, 100) }}</p>
                
                <div class="incident-meta">
                    <div class="meta-item">
                        <i class="fas fa-user"></i>
                        <span>{{ $incident->assignedUser ? $incident->assignedUser->full_name : 'Unassigned' }}</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-clock"></i>
                        <span>{{ $incident->detected_at->format('M d, Y H:i') }}</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-calendar-days"></i>
                        <span>{{ $incident->days_open }} days open</span>
                    </div>
                </div>
            </div>
            
            <div class="incident-actions">
                <a href="{{ route('admin.incidents.show', $incident) }}" class="btn btn-sm btn-info">
                    <i class="fas fa-eye"></i>
                    View
                </a>
                <a href="{{ route('admin.incidents.edit', $incident) }}" class="btn btn-sm btn-warning">
                    <i class="fas fa-edit"></i>
                    Edit
                </a>
                @if($incident->status !== 'closed')
                    <form action="{{ route('admin.incidents.status', $incident) }}" method="POST" style="display: inline;">
                        @csrf
                        <input type="hidden" name="status" value="{{ $incident->status === 'resolved' ? 'closed' : 'resolved' }}">
                        <button type="submit" class="btn btn-sm btn-success">
                            <i class="fas fa-check"></i>
                            {{ $incident->status === 'resolved' ? 'Close' : 'Resolve' }}
                        </button>
                    </form>
                @endif
            </div>
        </div>
    @empty
        <div class="empty-state">
            <i class="fas fa-exclamation-triangle"></i>
            <h3>No Incidents Found</h3>
            <p>There are no incidents to display. Create a new incident to get started.</p>
            <a href="{{ route('admin.incidents.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                Create First Incident
            </a>
        </div>
    @endforelse
</div>

@if(isset($incidents) && $incidents->hasPages())
    <div class="pagination-wrapper">
        {{ $incidents->links() }}
    </div>
@endif
@endsection

@push('scripts')
<script>
    // Filter functionality
    document.getElementById('severityFilter').addEventListener('change', filterIncidents);
    document.getElementById('statusFilter').addEventListener('change', filterIncidents);

    function filterIncidents() {
        const severityFilter = document.getElementById('severityFilter').value;
        const statusFilter = document.getElementById('statusFilter').value;
        const incidents = document.querySelectorAll('.incident-card');

        incidents.forEach(incident => {
            const severity = incident.dataset.severity;
            const status = incident.dataset.status;
            
            const showBySeverity = !severityFilter || severity === severityFilter;
            const showByStatus = !statusFilter || status === statusFilter;
            
            if (showBySeverity && showByStatus) {
                incident.style.display = 'block';
            } else {
                incident.style.display = 'none';
            }
        });
    }
</script>
@endpush