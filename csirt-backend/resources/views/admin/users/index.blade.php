@extends('admin.layouts.app')

@section('title', 'Users Management')
@section('page-title', 'Users Management')

@push('styles')
<link rel="stylesheet" href="{{ asset('frontend/css/user.css') }}">
@endpush

@section('content')
<div class="content-header">
    <div class="header-actions">
        @if(auth()->user()->role === 'admin')
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                Add New User
            </a>
        @endif
        <div class="filter-group">
            <select id="roleFilter" class="form-select">
                <option value="">All Roles</option>
                <option value="admin">Admin</option>
                <option value="operator">Operator</option>
                <option value="analyst">Analyst</option>
                <option value="viewer">Viewer</option>
            </select>
            <select id="statusFilter" class="form-select">
                <option value="">All Statuses</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
    </div>
</div>

<div class="users-grid">
    @forelse($users ?? [] as $user)
        <div class="user-card" data-role="{{ $user->role }}" data-status="{{ $user->is_active ? 'active' : 'inactive' }}">
            <div class="user-avatar-section">
                <div class="user-avatar-large">
                    @if($user->avatar)
                        <img src="{{ $user->avatar_url }}" alt="{{ $user->full_name }}">
                    @else
                        <span>{{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}</span>
                    @endif
                </div>
                <div class="user-status-indicator {{ $user->is_active ? 'active' : 'inactive' }}"></div>
            </div>
            
            <div class="user-info">
                <h3 class="user-name">{{ $user->full_name }}</h3>
                <p class="user-email">{{ $user->email }}</p>
                <p class="user-organization">{{ $user->organization }}</p>
                
                <div class="user-badges">
                    <span class="badge badge-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'operator' ? 'warning' : ($user->role === 'analyst' ? 'info' : 'secondary')) }}">
                        {{ ucfirst($user->role) }}
                    </span>
                    @if($user->is_active)
                        <span class="badge badge-success">Active</span>
                    @else
                        <span class="badge badge-secondary">Inactive</span>
                    @endif
                </div>
                
                <div class="user-meta">
                    <div class="meta-item">
                        <i class="fas fa-building"></i>
                        <span>{{ $user->department ?? 'N/A' }}</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-globe"></i>
                        <span>{{ $user->country }}</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-calendar"></i>
                        <span>Joined {{ $user->created_at->format('M Y') }}</span>
                    </div>
                    @if($user->last_login_at)
                        <div class="meta-item">
                            <i class="fas fa-clock"></i>
                            <span>Last login {{ $user->last_login_at->diffForHumans() }}</span>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="user-actions">
                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-info">
                    <i class="fas fa-eye"></i>
                    View
                </a>
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i>
                        Edit
                    </a>
                    @if($user->id !== auth()->id())
                        <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-sm {{ $user->is_active ? 'btn-secondary' : 'btn-success' }}">
                                <i class="fas fa-{{ $user->is_active ? 'pause' : 'play' }}"></i>
                                {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>
                    @endif
                @endif
            </div>
        </div>
    @empty
        <div class="empty-state">
            <i class="fas fa-users"></i>
            <h3>No Users Found</h3>
            <p>There are no users to display.</p>
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Add First User
                </a>
            @endif
        </div>
    @endforelse
</div>

@if(isset($users) && $users->hasPages())
    <div class="pagination-wrapper">
        {{ $users->links() }}
    </div>
@endif
@endsection

@push('scripts')
<script>
    // Filter functionality
    document.getElementById('roleFilter').addEventListener('change', filterUsers);
    document.getElementById('statusFilter').addEventListener('change', filterUsers);

    function filterUsers() {
        const roleFilter = document.getElementById('roleFilter').value;
        const statusFilter = document.getElementById('statusFilter').value;
        const userCards = document.querySelectorAll('.user-card');

        userCards.forEach(card => {
            const role = card.dataset.role;
            const status = card.dataset.status;
            
            const showByRole = !roleFilter || role === roleFilter;
            const showByStatus = !statusFilter || status === statusFilter;
            
            if (showByRole && showByStatus) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }
</script>
@endpush