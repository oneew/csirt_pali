@extends('admin.layouts.app')

@section('title', 'User Profile')
@section('page-title', 'User Profile: ' . $user->full_name)

@section('content')
<div class="user-detail">
    <div class="detail-header">
        <div class="header-left">
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
                <h1 class="user-name">{{ $user->full_name }}</h1>
                <div class="user-badges">
                    <span class="badge badge-{{ $user->is_active ? 'success' : 'danger' }}">
                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                    </span>
                    <span class="badge badge-primary">{{ ucfirst($user->role) }}</span>
                    @if($user->email_verified_at)
                        <span class="badge badge-success">Verified</span>
                    @else
                        <span class="badge badge-warning">Unverified</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i>
                Edit
            </a>
            <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-{{ $user->is_active ? 'danger' : 'success' }}">
                    <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }}"></i>
                    {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                </button>
            </form>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Back to List
            </a>
        </div>
    </div>

    <div class="detail-grid">
        <div class="detail-card">
            <h3 class="card-title">Personal Information</h3>
            <div class="info-grid">
                <div class="info-item">
                    <label>First Name:</label>
                    <span>{{ $user->first_name }}</span>
                </div>
                <div class="info-item">
                    <label>Last Name:</label>
                    <span>{{ $user->last_name }}</span>
                </div>
                <div class="info-item">
                    <label>Email:</label>
                    <span>{{ $user->email }}</span>
                </div>
                <div class="info-item">
                    <label>Phone:</label>
                    <span>{{ $user->phone ?: 'Not provided' }}</span>
                </div>
                <div class="info-item">
                    <label>Country:</label>
                    <span>{{ $user->country }}</span>
                </div>
            </div>
        </div>

        <div class="detail-card">
            <h3 class="card-title">Professional Information</h3>
            <div class="info-grid">
                <div class="info-item">
                    <label>Organization:</label>
                    <span>{{ $user->organization }}</span>
                </div>
                <div class="info-item">
                    <label>Department:</label>
                    <span>{{ $user->department ?: 'Not provided' }}</span>
                </div>
                <div class="info-item">
                    <label>Position:</label>
                    <span>{{ $user->position ?: 'Not provided' }}</span>
                </div>
                <div class="info-item">
                    <label>Role:</label>
                    <span class="badge badge-primary">{{ ucfirst($user->role) }}</span>
                </div>
            </div>
        </div>

        <div class="detail-card">
            <h3 class="card-title">Account Status</h3>
            <div class="info-grid">
                <div class="info-item">
                    <label>Status:</label>
                    <span class="badge badge-{{ $user->is_active ? 'success' : 'danger' }}">
                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <div class="info-item">
                    <label>Email Verified:</label>
                    <span class="badge badge-{{ $user->email_verified_at ? 'success' : 'warning' }}">
                        {{ $user->email_verified_at ? 'Verified' : 'Unverified' }}
                    </span>
                </div>
                <div class="info-item">
                    <label>Joined:</label>
                    <span>{{ $user->created_at->format('Y-m-d H:i:s') }}</span>
                </div>
                @if($user->last_login_at)
                    <div class="info-item">
                        <label>Last Login:</label>
                        <span>{{ $user->last_login_at->format('Y-m-d H:i:s') }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if($user->bio)
        <div class="detail-card">
            <h3 class="card-title">Biography</h3>
            <div class="bio-content">
                {{ $user->bio }}
            </div>
        </div>
    @endif

    @if($user->permissions && count($user->permissions) > 0)
        <div class="detail-card">
            <h3 class="card-title">Permissions</h3>
            <div class="permissions-list">
                @foreach($user->permissions as $permission)
                    <span class="permission-tag">{{ ucfirst(str_replace('_', ' ', $permission)) }}</span>
                @endforeach
            </div>
        </div>
    @endif

    <div class="detail-card">
        <h3 class="card-title">Activity Statistics</h3>
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-number">{{ $stats['incidents_reported'] ?? 0 }}</div>
                <div class="stat-label">Incidents Reported</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $stats['incidents_assigned'] ?? 0 }}</div>
                <div class="stat-label">Incidents Assigned</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $stats['incidents_resolved'] ?? 0 }}</div>
                <div class="stat-label">Incidents Resolved</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $stats['news_published'] ?? 0 }}</div>
                <div class="stat-label">News Published</div>
            </div>
        </div>
    </div>

    @if(isset($recentActivities) && count($recentActivities) > 0)
        <div class="detail-card">
            <h3 class="card-title">Recent Activities</h3>
            <div class="activities-list">
                @foreach($recentActivities as $activity)
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-{{ $activity->action === 'login' ? 'sign-in-alt' : ($activity->action === 'created' ? 'plus' : ($activity->action === 'updated' ? 'edit' : 'eye')) }}"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-description">{{ $activity->description }}</div>
                            <div class="activity-time">{{ $activity->created_at->format('Y-m-d H:i:s') }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection