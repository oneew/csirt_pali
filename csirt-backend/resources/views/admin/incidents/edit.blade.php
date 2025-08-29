@extends('admin.layouts.app')

@section('title', 'Edit Incident')
@section('page-title', 'Edit Incident: ' . $incident->incident_id)

@section('content')
<div class="form-container">
    <form action="{{ route('admin.incidents.update', $incident) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="form-grid">
            <div class="form-group">
                <label for="title" class="form-label">Title *</label>
                <input type="text" id="title" name="title" class="form-input" value="{{ old('title', $incident->title) }}" required>
                @error('title')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="severity" class="form-label">Severity *</label>
                <select id="severity" name="severity" class="form-select" required>
                    <option value="">Select Severity</option>
                    <option value="low" {{ old('severity', $incident->severity) == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ old('severity', $incident->severity) == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ old('severity', $incident->severity) == 'high' ? 'selected' : '' }}>High</option>
                    <option value="critical" {{ old('severity', $incident->severity) == 'critical' ? 'selected' : '' }}>Critical</option>
                </select>
                @error('severity')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="status" class="form-label">Status *</label>
                <select id="status" name="status" class="form-select" required>
                    <option value="open" {{ old('status', $incident->status) == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="investigating" {{ old('status', $incident->status) == 'investigating' ? 'selected' : '' }}>Investigating</option>
                    <option value="resolved" {{ old('status', $incident->status) == 'resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="closed" {{ old('status', $incident->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
                @error('status')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="category" class="form-label">Category *</label>
                <select id="category" name="category" class="form-select" required>
                    <option value="">Select Category</option>
                    <option value="malware" {{ old('category', $incident->category) == 'malware' ? 'selected' : '' }}>Malware</option>
                    <option value="phishing" {{ old('category', $incident->category) == 'phishing' ? 'selected' : '' }}>Phishing</option>
                    <option value="ddos" {{ old('category', $incident->category) == 'ddos' ? 'selected' : '' }}>DDoS</option>
                    <option value="data_breach" {{ old('category', $incident->category) == 'data_breach' ? 'selected' : '' }}>Data Breach</option>
                    <option value="unauthorized_access" {{ old('category', $incident->category) == 'unauthorized_access' ? 'selected' : '' }}>Unauthorized Access</option>
                    <option value="vulnerability" {{ old('category', $incident->category) == 'vulnerability' ? 'selected' : '' }}>Vulnerability</option>
                    <option value="other" {{ old('category', $incident->category) == 'other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('category')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="priority" class="form-label">Priority *</label>
                <select id="priority" name="priority" class="form-select" required>
                    <option value="">Select Priority</option>
                    <option value="low" {{ old('priority', $incident->priority) == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ old('priority', $incident->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ old('priority', $incident->priority) == 'high' ? 'selected' : '' }}>High</option>
                    <option value="critical" {{ old('priority', $incident->priority) == 'critical' ? 'selected' : '' }}>Critical</option>
                </select>
                @error('priority')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="assigned_to" class="form-label">Assign To</label>
                <select id="assigned_to" name="assigned_to" class="form-select">
                    <option value="">Select User</option>
                    @if(isset($users))
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('assigned_to', $incident->assigned_to) == $user->id ? 'selected' : '' }}>
                                {{ $user->full_name }}
                            </option>
                        @endforeach
                    @endif
                </select>
                @error('assigned_to')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="detected_at" class="form-label">Detection Date *</label>
                <input type="datetime-local" id="detected_at" name="detected_at" class="form-input" 
                       value="{{ old('detected_at', $incident->detected_at->format('Y-m-d\TH:i')) }}" required>
                @error('detected_at')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            @if(in_array($incident->status, ['resolved', 'closed']))
                <div class="form-group">
                    <label for="resolved_at" class="form-label">Resolution Date</label>
                    <input type="datetime-local" id="resolved_at" name="resolved_at" class="form-input" 
                           value="{{ old('resolved_at', $incident->resolved_at ? $incident->resolved_at->format('Y-m-d\TH:i') : '') }}">
                    @error('resolved_at')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="description" class="form-label">Description *</label>
            <textarea id="description" name="description" class="form-textarea" rows="4" required>{{ old('description', $incident->description) }}</textarea>
            @error('description')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="impact_description" class="form-label">Impact Description</label>
            <textarea id="impact_description" name="impact_description" class="form-textarea" rows="3">{{ old('impact_description', $incident->impact_description) }}</textarea>
            @error('impact_description')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="remediation_steps" class="form-label">Remediation Steps</label>
            <textarea id="remediation_steps" name="remediation_steps" class="form-textarea" rows="4">{{ old('remediation_steps', $incident->remediation_steps) }}</textarea>
            @error('remediation_steps')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="lessons_learned" class="form-label">Lessons Learned</label>
            <textarea id="lessons_learned" name="lessons_learned" class="form-textarea" rows="3">{{ old('lessons_learned', $incident->lessons_learned) }}</textarea>
            @error('lessons_learned')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i>
                Update Incident
            </button>
            <a href="{{ route('admin.incidents.show', $incident) }}" class="btn btn-secondary">
                <i class="fas fa-times"></i>
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection