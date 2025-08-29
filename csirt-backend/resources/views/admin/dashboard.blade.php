@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<!-- Dashboard Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon blue">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-change {{ $stats['users']['trend'] === 'up' ? 'positive' : 'negative' }}">
                <i class="fas fa-arrow-{{ $stats['users']['trend'] }}"></i>
                {{ $stats['users']['growth'] > 0 ? '+' : '' }}{{ $stats['users']['growth'] }}%
            </div>
        </div>
        <div class="stat-number">{{ $stats['users']['total'] }}</div>
        <div class="stat-label">Total Users</div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon yellow">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-change {{ $stats['incidents']['trend'] === 'up' ? 'positive' : 'negative' }}">
                <i class="fas fa-arrow-{{ $stats['incidents']['trend'] }}"></i>
                {{ $stats['incidents']['growth'] > 0 ? '+' : '' }}{{ $stats['incidents']['growth'] }}%
            </div>
        </div>
        <div class="stat-number">{{ $stats['incidents']['total'] }}</div>
        <div class="stat-label">Total Incidents</div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon red">
                <i class="fas fa-fire"></i>
            </div>
            <div class="stat-change {{ $stats['incidents']['critical'] > 0 ? 'negative' : 'positive' }}">
                <i class="fas fa-{{ $stats['incidents']['critical'] > 0 ? 'exclamation' : 'check' }}"></i>
                {{ $stats['incidents']['critical'] > 0 ? 'Critical' : 'Good' }}
            </div>
        </div>
        <div class="stat-number">{{ $stats['incidents']['open'] }}</div>
        <div class="stat-label">Open Incidents</div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon green">
                <i class="fas fa-newspaper"></i>
            </div>
            <div class="stat-change {{ $stats['news']['trend'] === 'up' ? 'positive' : 'negative' }}">
                <i class="fas fa-arrow-{{ $stats['news']['trend'] }}"></i>
                {{ $stats['news']['growth'] > 0 ? '+' : '' }}{{ $stats['news']['growth'] }}%
            </div>
        </div>
        <div class="stat-number">{{ $stats['news']['published'] }}</div>
        <div class="stat-label">Published News</div>
    </div>
</div>

<!-- Content Grid -->
<div class="content-grid">
    <!-- Recent Incidents -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Recent Incidents</h3>
            <a href="{{ route('admin.incidents.index') }}" class="btn btn-primary">
                <i class="fas fa-eye"></i>
                View All
            </a>
        </div>
        <div class="card-content">
            @if(count($criticalIncidents) > 0)
                <ul class="activity-list">
                    @foreach($criticalIncidents as $incident)
                        <li class="activity-item">
                            <div class="activity-icon red">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">
                                    {{ $incident['title'] }}
                                </div>
                                <div class="activity-meta">
                                    Assigned to: {{ $incident['assigned_to'] }} • {{ $incident['days_open'] }} days open
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-muted">No recent critical incidents</p>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Quick Actions</h3>
        </div>
        <div class="card-content">
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <a href="{{ route('admin.incidents.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Create Incident
                </a>
                <a href="{{ route('admin.news.create') }}" class="btn btn-success">
                    <i class="fas fa-newspaper"></i>
                    Publish News
                </a>
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.users.create') }}" class="btn btn-secondary">
                        <i class="fas fa-user-plus"></i>
                        Add User
                    </a>
                    <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">
                        <i class="fas fa-chart-line"></i>
                        Generate Report
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection