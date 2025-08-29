@extends('admin.layouts.app')

@section('title', 'My Profile')
@section('page-title', 'My Profile')

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
                    <p class="user-organization">{{ $user->organization }}</p>
                    @if(isset($userStats['member_since']))
                        <p class="member-since">Member since {{ $userStats['member_since'] }}</p>
                    @endif
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
</div>
@endsection