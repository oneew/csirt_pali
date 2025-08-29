@extends('admin.layouts.app')

@section('title', 'Create Incident')
@section('page-title', 'Create New Incident')

@section('content')
<div class="form-container">
    <form action="{{ route('admin.incidents.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="form-grid">
            <div class="form-group">
                <label for="title" class="form-label">Title *</label>
                <input type="text" id="title" name="title" class="form-input" value="{{ old('title') }}" required>
                @error('title')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="severity" class="form-label">Severity *</label>
                <select id="severity" name="severity" class="form-select" required>
                    <option value="">Select Severity</option>
                    <option value="low" {{ old('severity') == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ old('severity') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ old('severity') == 'high' ? 'selected' : '' }}>High</option>
                    <option value="critical" {{ old('severity') == 'critical' ? 'selected' : '' }}>Critical</option>
                </select>
                @error('severity')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="category" class="form-label">Category *</label>
                <select id="category" name="category" class="form-select" required>
                    <option value="">Select Category</option>
                    <option value="malware" {{ old('category') == 'malware' ? 'selected' : '' }}>Malware</option>
                    <option value="phishing" {{ old('category') == 'phishing' ? 'selected' : '' }}>Phishing</option>
                    <option value="ddos" {{ old('category') == 'ddos' ? 'selected' : '' }}>DDoS</option>
                    <option value="data_breach" {{ old('category') == 'data_breach' ? 'selected' : '' }}>Data Breach</option>
                    <option value="unauthorized_access" {{ old('category') == 'unauthorized_access' ? 'selected' : '' }}>Unauthorized Access</option>
                    <option value="vulnerability" {{ old('category') == 'vulnerability' ? 'selected' : '' }}>Vulnerability</option>
                    <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('category')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="priority" class="form-label">Priority *</label>
                <select id="priority" name="priority" class="form-select" required>
                    <option value="">Select Priority</option>
                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                    <option value="critical" {{ old('priority') == 'critical' ? 'selected' : '' }}>Critical</option>
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
                            <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>
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
                       value="{{ old('detected_at', now()->format('Y-m-d\TH:i')) }}" required>
                @error('detected_at')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="description" class="form-label">Description *</label>
            <textarea id="description" name="description" class="form-textarea" rows="4" required>{{ old('description') }}</textarea>
            @error('description')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="impact_description" class="form-label">Impact Description</label>
            <textarea id="impact_description" name="impact_description" class="form-textarea" rows="3">{{ old('impact_description') }}</textarea>
            @error('impact_description')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i>
                Create Incident
            </button>
            <a href="{{ route('admin.incidents.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i>
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection