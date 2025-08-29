@extends('admin.layouts.app')

@section('title', 'My Profile')
@section('page-title', 'My Profile')

@push('styles')
<link rel="stylesheet" href="{{ asset('frontend/css/profile.css') }}">
@endpush

@section('content')
<div class="profile-container">
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
    @endif

    <div class="profile-grid">
        <!-- User Info Card -->
        <div class="profile-info-card">
            <div class="profile-header">
                <div class="user-avatar-large">
                    @if($user->avatar)
                        <img src="{{ $user->avatar_url }}" alt="{{ $user->full_name }}">
                    @else
                        <div class="avatar-placeholder">
                            {{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div class="user-info">
                    <h2 class="user-name">{{ $user->full_name }}</h2>
                    <p class="user-role">{{ ucfirst($user->role) }}</p>
                    <p class="user-organization">{{ $user->organization ?? 'No organization' }}</p>
                    <p class="member-since">Member since {{ $user->created_at->format('F Y') }}</p>
                </div>
            </div>

            <!-- User Statistics -->
            <div class="user-stats">
                <div class="stat-item">
                    <div class="stat-number">{{ $userStats['incidents_reported'] ?? 0 }}</div>
                    <div class="stat-label">Incidents Reported</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ $userStats['incidents_assigned'] ?? 0 }}</div>
                    <div class="stat-label">Incidents Assigned</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ $userStats['incidents_resolved'] ?? 0 }}</div>
                    <div class="stat-label">Incidents Resolved</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ $userStats['news_articles'] ?? 0 }}</div>
                    <div class="stat-label">News Articles</div>
                </div>
            </div>
        </div>

        <!-- Profile Edit Form -->
        <div class="profile-form-card">
            <h3 class="card-title">
                <i class="fas fa-user-edit"></i>
                Update Profile
            </h3>
            
            <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-grid">
                    <div class="form-group">
                        <label for="first_name" class="form-label">First Name *</label>
                        <input type="text" id="first_name" name="first_name" class="form-input" 
                               value="{{ old('first_name', $user->first_name) }}" required>
                        @error('first_name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="last_name" class="form-label">Last Name *</label>
                        <input type="text" id="last_name" name="last_name" class="form-input" 
                               value="{{ old('last_name', $user->last_name) }}" required>
                        @error('last_name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" id="email" name="email" class="form-input" 
                               value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" id="phone" name="phone" class="form-input" 
                               value="{{ old('phone', $user->phone) }}">
                        @error('phone')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="organization" class="form-label">Organization</label>
                        <input type="text" id="organization" name="organization" class="form-input" 
                               value="{{ old('organization', $user->organization) }}">
                        @error('organization')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="department" class="form-label">Department</label>
                        <input type="text" id="department" name="department" class="form-input" 
                               value="{{ old('department', $user->department) }}">
                        @error('department')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="position" class="form-label">Position</label>
                        <input type="text" id="position" name="position" class="form-input" 
                               value="{{ old('position', $user->position) }}">
                        @error('position')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="country" class="form-label">Country</label>
                        <input type="text" id="country" name="country" class="form-input" 
                               value="{{ old('country', $user->country) }}">
                        @error('country')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="bio" class="form-label">Biography</label>
                    <textarea id="bio" name="bio" class="form-textarea" rows="4" 
                              placeholder="Tell us about yourself...">{{ old('bio', $user->bio) }}</textarea>
                    @error('bio')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="avatar" class="form-label">Profile Picture</label>
                    <input type="file" id="avatar" name="avatar" class="form-input" accept="image/*">
                    @if($user->avatar)
                        <div class="current-avatar">
                            <img src="{{ $user->avatar_url }}" alt="Current avatar" style="max-width: 100px; margin-top: 0.5rem;">
                            <div class="form-checkbox" style="margin-top: 0.5rem;">
                                <input type="checkbox" id="remove_avatar" name="remove_avatar" value="1">
                                <label for="remove_avatar">Remove current picture</label>
                            </div>
                        </div>
                    @endif
                    @error('avatar')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <h4 class="form-section-title">
                    <i class="fas fa-lock"></i>
                    Change Password
                </h4>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input type="password" id="current_password" name="current_password" class="form-input">
                        @error('current_password')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">New Password</label>
                        <input type="password" id="password" name="password" class="form-input">
                        @error('password')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-input">
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Update Profile
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Back to Dashboard
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="profile-activity">
        <h3 class="section-title">
            <i class="fas fa-clock"></i>
            Recent Activity
        </h3>
        <div class="activity-list">
            @if(isset($recentActivities) && $recentActivities->count() > 0)
                @foreach($recentActivities as $activity)
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-{{ $activity->action_icon ?? 'circle' }}"></i>
                        </div>
                        <div class="activity-content">
                            <p class="activity-description">{{ $activity->description }}</p>
                            <span class="activity-time">{{ $activity->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <i class="fas fa-history"></i>
                    <p>No recent activity found.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.querySelector('form');
    const passwordField = document.getElementById('password');
    const confirmPasswordField = document.getElementById('password_confirmation');
    const currentPasswordField = document.getElementById('current_password');

    // Password confirmation validation
    function validatePasswordConfirmation() {
        if (passwordField.value !== confirmPasswordField.value) {
            confirmPasswordField.setCustomValidity('Passwords do not match');
        } else {
            confirmPasswordField.setCustomValidity('');
        }
    }

    // Require current password if new password is entered
    function validateCurrentPassword() {
        if (passwordField.value && !currentPasswordField.value) {
            currentPasswordField.setCustomValidity('Current password is required to set a new password');
        } else {
            currentPasswordField.setCustomValidity('');
        }
    }

    passwordField.addEventListener('input', function() {
        validatePasswordConfirmation();
        validateCurrentPassword();
    });

    confirmPasswordField.addEventListener('input', validatePasswordConfirmation);
    currentPasswordField.addEventListener('input', validateCurrentPassword);

    // Avatar preview
    const avatarInput = document.getElementById('avatar');
    avatarInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Create preview if doesn't exist
                let preview = document.querySelector('.avatar-preview');
                if (!preview) {
                    preview = document.createElement('div');
                    preview.className = 'avatar-preview';
                    preview.innerHTML = '<img src="" alt="Avatar preview" style="max-width: 100px; margin-top: 0.5rem; border-radius: 50%;">';
                    avatarInput.parentNode.appendChild(preview);
                }
                preview.querySelector('img').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    // Auto-hide alerts
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.remove();
            }, 500);
        }, 5000);
    });
});
</script>
@endpush
@endsection