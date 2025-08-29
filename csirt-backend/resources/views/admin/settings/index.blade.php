@extends('admin.layouts.app')

@section('title', 'System Settings')
@section('page-title', 'System Settings')

@push('styles')
<link rel="stylesheet" href="{{ asset('frontend/css/setting.css') }}">
@endpush

@section('content')
<div class="settings-container">
    <div class="settings-sidebar">
        <nav class="settings-nav">
            <a href="#general" class="settings-nav-link active" data-tab="general">
                <i class="fas fa-cog"></i>
                General Settings
            </a>
            <a href="#security" class="settings-nav-link" data-tab="security">
                <i class="fas fa-shield-alt"></i>
                Security
            </a>
            <a href="#notifications" class="settings-nav-link" data-tab="notifications">
                <i class="fas fa-bell"></i>
                Notifications
            </a>
            <a href="#system" class="settings-nav-link" data-tab="system">
                <i class="fas fa-server"></i>
                System
            </a>
        </nav>
    </div>
    
    <div class="settings-content">
        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- General Settings Tab -->
            <div class="settings-tab active" id="general-tab">
                <div class="settings-section">
                    <h3>General Configuration</h3>
                    
                    <div class="form-group">
                        <label for="site_name" class="form-label">Site Name</label>
                        <input type="text" id="site_name" name="settings[site_name]" class="form-input" value="CSIRT PALI">
                    </div>
                    
                    <div class="form-group">
                        <label for="site_description" class="form-label">Site Description</label>
                        <textarea id="site_description" name="settings[site_description]" class="form-textarea" rows="3">CSIRT PALI - Cybersecurity Incident Response Team for the Americas</textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="admin_email" class="form-label">Admin Email</label>
                        <input type="email" id="admin_email" name="settings[admin_email]" class="form-input" value="admin@csirtpali.org">
                    </div>
                </div>
            </div>
            
            <!-- Security Settings Tab -->
            <div class="settings-tab" id="security-tab">
                <div class="settings-section">
                    <h3>Security Configuration</h3>
                    
                    <div class="form-group">
                        <label class="form-label">Two-Factor Authentication</label>
                        <div class="toggle-switch">
                            <input type="checkbox" id="two_factor_enabled" name="settings[two_factor_enabled]" checked>
                            <label for="two_factor_enabled" class="toggle-label">
                                <span class="toggle-slider"></span>
                            </label>
                            <span class="toggle-text">Enable 2FA for all admin accounts</span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="session_timeout" class="form-label">Session Timeout (minutes)</label>
                        <input type="number" id="session_timeout" name="settings[session_timeout]" class="form-input" value="120" min="5" max="1440">
                    </div>
                </div>
            </div>
            
            <!-- Notifications Settings Tab -->
            <div class="settings-tab" id="notifications-tab">
                <div class="settings-section">
                    <h3>Notification Preferences</h3>
                    
                    <div class="form-group">
                        <label class="form-label">Email Notifications</label>
                        <div class="checkbox-group">
                            <label class="checkbox-item">
                                <input type="checkbox" name="settings[notify_new_incidents]" checked>
                                <span class="checkmark"></span>
                                New incidents created
                            </label>
                            <label class="checkbox-item">
                                <input type="checkbox" name="settings[notify_critical_incidents]" checked>
                                <span class="checkmark"></span>
                                Critical incidents
                            </label>
                            <label class="checkbox-item">
                                <input type="checkbox" name="settings[notify_user_registration]" checked>
                                <span class="checkmark"></span>
                                New user registrations
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- System Settings Tab -->
            <div class="settings-tab" id="system-tab">
                <div class="settings-section">
                    <h3>System Configuration</h3>
                    
                    <div class="form-group">
                        <label for="timezone" class="form-label">System Timezone</label>
                        <select id="timezone" name="settings[timezone]" class="form-select">
                            <option value="UTC">UTC</option>
                            <option value="America/New_York">Eastern Time</option>
                            <option value="America/Chicago">Central Time</option>
                            <option value="America/Denver">Mountain Time</option>
                            <option value="America/Los_Angeles">Pacific Time</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="items_per_page" class="form-label">Items Per Page</label>
                        <select id="items_per_page" name="settings[items_per_page]" class="form-select">
                            <option value="10">10</option>
                            <option value="25" selected>25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="settings-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Save Settings
                </button>
                <button type="button" class="btn btn-secondary" onclick="location.reload()">
                    <i class="fas fa-undo"></i>
                    Reset
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Tab switching functionality
    document.querySelectorAll('.settings-nav-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all nav links and tabs
            document.querySelectorAll('.settings-nav-link').forEach(l => l.classList.remove('active'));
            document.querySelectorAll('.settings-tab').forEach(t => t.classList.remove('active'));
            
            // Add active class to clicked nav link
            this.classList.add('active');
            
            // Show corresponding tab
            const tabId = this.dataset.tab + '-tab';
            document.getElementById(tabId).classList.add('active');
        });
    });
</script>
@endpush