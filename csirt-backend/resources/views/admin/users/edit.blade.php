@extends('admin.layouts.app')

@section('title', 'Edit User')
@section('page-title', 'Edit User: ' . $user->full_name)

@section('content')
<div class="form-container">
    <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="form-grid">
            <div class="form-group">
                <label for="first_name" class="form-label">First Name *</label>
                <input type="text" id="first_name" name="first_name" class="form-input" value="{{ old('first_name', $user->first_name) }}" required>
                @error('first_name')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="last_name" class="form-label">Last Name *</label>
                <input type="text" id="last_name" name="last_name" class="form-input" value="{{ old('last_name', $user->last_name) }}" required>
                @error('last_name')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email *</label>
                <input type="email" id="email" name="email" class="form-input" value="{{ old('email', $user->email) }}" required>
                @error('email')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" id="phone" name="phone" class="form-input" value="{{ old('phone', $user->phone) }}">
                @error('phone')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="organization" class="form-label">Organization *</label>
                <input type="text" id="organization" name="organization" class="form-input" value="{{ old('organization', $user->organization) }}" required>
                @error('organization')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="department" class="form-label">Department</label>
                <input type="text" id="department" name="department" class="form-input" value="{{ old('department', $user->department) }}">
                @error('department')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="position" class="form-label">Position</label>
                <input type="text" id="position" name="position" class="form-input" value="{{ old('position', $user->position) }}">
                @error('position')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="country" class="form-label">Country *</label>
                <input type="text" id="country" name="country" class="form-input" value="{{ old('country', $user->country) }}" required>
                @error('country')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="role" class="form-label">Role *</label>
                <select id="role" name="role" class="form-select" required>
                    <option value="">Select Role</option>
                    @if(isset($roles))
                        @foreach($roles as $role)
                            <option value="{{ $role }}" {{ old('role', $user->role) == $role ? 'selected' : '' }}>
                                {{ ucfirst($role) }}
                            </option>
                        @endforeach
                    @endif
                </select>
                @error('role')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">New Password (Leave blank to keep current)</label>
                <input type="password" id="password" name="password" class="form-input">
                @error('password')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-input">
                @error('password_confirmation')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="avatar" class="form-label">Avatar</label>
                <input type="file" id="avatar" name="avatar" class="form-input" accept="image/*">
                @if($user->avatar)
                    <div class="current-avatar">
                        <img src="{{ $user->avatar_url }}" alt="Current avatar" style="max-width: 100px; margin-top: 0.5rem;">
                        <div class="form-checkbox" style="margin-top: 0.5rem;">
                            <input type="checkbox" id="remove_avatar" name="remove_avatar" value="1">
                            <label for="remove_avatar">Remove current avatar</label>
                        </div>
                    </div>
                @endif
                @error('avatar')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <div class="form-checkbox">
                    <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                    <label for="is_active">Active User</label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="bio" class="form-label">Bio</label>
            <textarea id="bio" name="bio" class="form-textarea" rows="3">{{ old('bio', $user->bio) }}</textarea>
            @error('bio')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        @if(isset($permissions) && count($permissions) > 0)
            <div class="form-group">
                <label class="form-label">Permissions</label>
                <div class="permissions-grid">
                    @foreach($permissions as $permission)
                        <div class="form-checkbox">
                            <input type="checkbox" id="permission_{{ $permission }}" name="permissions[]" value="{{ $permission }}" 
                                   {{ in_array($permission, old('permissions', $user->permissions ?? [])) ? 'checked' : '' }}>
                            <label for="permission_{{ $permission }}">{{ ucfirst(str_replace('_', ' ', $permission)) }}</label>
                        </div>
                    @endforeach
                </div>
                @error('permissions')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
        @endif

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i>
                Update User
            </button>
            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-secondary">
                <i class="fas fa-times"></i>
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection