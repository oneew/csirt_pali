@extends('admin.layouts.app')

@section('title', 'Incident Details')
@section('page-title', 'Incident: ' . $incident->incident_id)

@section('content')
<div class="incident-detail">
    <div class="detail-header">
        <div class="header-left">
            <h1 class="incident-title">{{ $incident->title }}</h1>
            <div class="incident-badges">
                <span class="badge badge-{{ $incident->severity_badge }}">{{ ucfirst($incident->severity) }}</span>
                <span class="badge badge-{{ $incident->status_badge }}">{{ ucfirst($incident->status) }}</span>
                <span class="badge badge-outline">{{ ucfirst($incident->category) }}</span>
            </div>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.incidents.edit', $incident) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i>
                Edit
            </a>
            <a href="{{ route('admin.incidents.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Back to List
            </a>
        </div>
    </div>

    <div class="detail-grid">
        <div class="detail-card">
            <h3 class="card-title">Incident Information</h3>
            <div class="info-grid">
                <div class="info-item">
                    <label>Incident ID:</label>
                    <span>{{ $incident->incident_id }}</span>
                </div>
                <div class="info-item">
                    <label>Severity:</label>
                    <span class="badge badge-{{ $incident->severity_badge }}">{{ ucfirst($incident->severity) }}</span>
                </div>
                <div class="info-item">
                    <label>Status:</label>
                    <span class="badge badge-{{ $incident->status_badge }}">{{ ucfirst($incident->status) }}</span>
                </div>
                <div class="info-item">
                    <label>Category:</label>
                    <span>{{ ucfirst(str_replace('_', ' ', $incident->category)) }}</span>
                </div>
                <div class="info-item">
                    <label>Priority:</label>
                    <span class="badge badge-{{ $incident->priority }}">{{ ucfirst($incident->priority) }}</span>
                </div>
                <div class="info-item">
                    <label>Detected At:</label>
                    <span>{{ $incident->detected_at->format('Y-m-d H:i:s') }}</span>
                </div>
                @if($incident->resolved_at)
                    <div class="info-item">
                        <label>Resolved At:</label>
                        <span>{{ $incident->resolved_at->format('Y-m-d H:i:s') }}</span>
                    </div>
                @endif
                <div class="info-item">
                    <label>Days Open:</label>
                    <span>{{ $incident->days_open }} days</span>
                </div>
            </div>
        </div>

        <div class="detail-card">
            <h3 class="card-title">Assignment & Reporting</h3>
            <div class="info-grid">
                <div class="info-item">
                    <label>Assigned To:</label>
                    <span>{{ $incident->assignedUser ? $incident->assignedUser->full_name : 'Not Assigned' }}</span>
                </div>
                <div class="info-item">
                    <label>Reported By:</label>
                    <span>{{ $incident->reporter ? $incident->reporter->full_name : 'System' }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="detail-card">
        <h3 class="card-title">Description</h3>
        <div class="description-content">
            {{ $incident->description }}
        </div>
    </div>

    @if($incident->impact_description)
        <div class="detail-card">
            <h3 class="card-title">Impact Description</h3>
            <div class="description-content">
                {{ $incident->impact_description }}
            </div>
        </div>
    @endif

    @if($incident->affected_systems && count($incident->affected_systems) > 0)
        <div class="detail-card">
            <h3 class="card-title">Affected Systems</h3>
            <ul class="systems-list">
                @foreach($incident->affected_systems as $system)
                    <li>{{ $system }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($incident->indicators_of_compromise && count($incident->indicators_of_compromise) > 0)
        <div class="detail-card">
            <h3 class="card-title">Indicators of Compromise</h3>
            <ul class="ioc-list">
                @foreach($incident->indicators_of_compromise as $ioc)
                    <li><code>{{ $ioc }}</code></li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($incident->remediation_steps)
        <div class="detail-card">
            <h3 class="card-title">Remediation Steps</h3>
            <div class="description-content">
                {{ $incident->remediation_steps }}
            </div>
        </div>
    @endif

    @if($incident->lessons_learned)
        <div class="detail-card">
            <h3 class="card-title">Lessons Learned</h3>
            <div class="description-content">
                {{ $incident->lessons_learned }}
            </div>
        </div>
    @endif

    @if($incident->attachments && count($incident->attachments) > 0)
        <div class="detail-card">
            <h3 class="card-title">Attachments</h3>
            <div class="attachments-list">
                @foreach($incident->attachments as $attachment)
                    <div class="attachment-item">
                        <i class="fas fa-file"></i>
                        <span>{{ $attachment['original_name'] }}</span>
                        <a href="{{ asset('storage/' . $attachment['path']) }}" target="_blank" class="btn btn-sm btn-outline">
                            <i class="fas fa-download"></i>
                            Download
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection