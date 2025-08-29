@extends('admin.layouts.app')

@section('title', 'Create User')
@section('page-title', 'Create New User')

@section('content')
<div class="form-container">
    <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="form-grid">
            <div class="form-group">
                <label for="first_name" class="form-label">First Name *</label>
                <input type="text" id="first_name" name="first_name" class="form-input" value="{{ old('first_name') }}" required>
                @error('first_name')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="last_name" class="form-label">Last Name *</label>
                <input type="text" id="last_name" name="last_name" class="form-input" value="{{ old('last_name') }}" required>
                @error('last_name')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email *</label>
                <input type="email" id="email" name="email" class="form-input" value="{{ old('email') }}" required>
                @error('email')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" id="phone" name="phone" class="form-input" value="{{ old('phone') }}">
                @error('phone')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="organization" class="form-label">Organization *</label>
                <input type="text" id="organization" name="organization" class="form-input" value="{{ old('organization') }}" required>
                @error('organization')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="department" class="form-label">Department</label>
                <input type="text" id="department" name="department" class="form-input" value="{{ old('department') }}">
                @error('department')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="position" class="form-label">Position</label>
                <input type="text" id="position" name="position" class="form-input" value="{{ old('position') }}">
                @error('position')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="country" class="form-label">Country *</label>
                <input type="text" id="country" name="country" class="form-input" value="{{ old('country') }}" required>
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
                            <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }}>
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
                <label for="password" class="form-label">Password *</label>
                <input type="password" id="password" name="password" class="form-input" required>
                @error('password')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">Confirm Password *</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" required>
                @error('password_confirmation')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="avatar" class="form-label">Avatar</label>
                <input type="file" id="avatar" name="avatar" class="form-input" accept="image/*">
                @error('avatar')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <div class="form-checkbox">
                    <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                    <label for="is_active">Active User</label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="bio" class="form-label">Bio</label>
            <textarea id="bio" name="bio" class="form-textarea" rows="3">{{ old('bio') }}</textarea>
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
                                   {{ in_array($permission, old('permissions', [])) ? 'checked' : '' }}>
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
                Create User
            </button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i>
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection