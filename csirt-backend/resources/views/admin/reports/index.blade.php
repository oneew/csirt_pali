@extends('admin.layouts.app')

@section('title', 'Reports & Analytics')
@section('page-title', 'Reports & Analytics')

@section('content')
<div class="reports-grid">
    <div class="report-card">
        <div class="report-header">
            <i class="fas fa-exclamation-triangle"></i>
            <h3>Incidents Report</h3>
        </div>
        <div class="report-content">
            <p>Generate detailed reports on incident trends, response times, and resolution statistics.</p>
            <a href="{{ route('admin.reports.incidents') }}" class="btn btn-primary">
                <i class="fas fa-chart-line"></i>
                View Incidents Report
            </a>
        </div>
    </div>
    
    <div class="report-card">
        <div class="report-header">
            <i class="fas fa-users"></i>
            <h3>Users Activity</h3>
        </div>
        <div class="report-content">
            <p>Analyze user activity, login patterns, and engagement metrics.</p>
            <a href="{{ route('admin.reports.users') }}" class="btn btn-primary">
                <i class="fas fa-user-chart"></i>
                View Users Report
            </a>
        </div>
    </div>
    
    <div class="report-card">
        <div class="report-header">
            <i class="fas fa-shield-alt"></i>
            <h3>Security Analytics</h3>
        </div>
        <div class="report-content">
            <p>Security logs, threat analysis, and system performance metrics.</p>
            <a href="{{ route('admin.reports.security') }}" class="btn btn-primary">
                <i class="fas fa-shield-virus"></i>
                View Security Report
            </a>
        </div>
    </div>
</div>
@endsection