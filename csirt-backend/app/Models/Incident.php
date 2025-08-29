<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Incident extends Model
{
    use HasFactory;

    protected $fillable = [
        'incident_id',
        'title',
        'description',
        'severity',
        'category',
        'status',
        'priority',
        'assigned_to',
        'reported_by',
        'detected_at',
        'resolved_at',
        'impact_description',
        'affected_systems',
        'indicators_of_compromise',
        'remediation_steps',
        'lessons_learned',
        'attachments',
    ];

    protected $casts = [
        'detected_at' => 'datetime',
        'resolved_at' => 'datetime',
        'affected_systems' => 'array',
        'indicators_of_compromise' => 'array',
        'attachments' => 'array',
    ];

    // Relationships
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    // Accessors
    public function getSeverityBadgeAttribute()
    {
        $badges = [
            'low' => 'success',
            'medium' => 'warning',
            'high' => 'danger',
            'critical' => 'dark'
        ];

        return $badges[$this->severity] ?? 'secondary';
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'open' => 'danger',
            'investigating' => 'warning',
            'resolved' => 'success',
            'closed' => 'secondary'
        ];

        return $badges[$this->status] ?? 'secondary';
    }

    public function getDaysOpenAttribute()
    {
        $endDate = $this->resolved_at ?? now();
        return $this->detected_at->diffInDays($endDate);
    }

    public function getIsOverdueAttribute()
    {
        if ($this->status === 'resolved' || $this->status === 'closed') {
            return false;
        }

        $overdueDays = [
            'low' => 30,
            'medium' => 14,
            'high' => 7,
            'critical' => 1
        ];

        return $this->days_open > ($overdueDays[$this->severity] ?? 30);
    }

    // Scopes
    public function scopeOpen($query)
    {
        return $query->whereIn('status', ['open', 'investigating']);
    }

    public function scopeClosed($query)
    {
        return $query->whereIn('status', ['resolved', 'closed']);
    }

    public function scopeBySeverity($query, $severity)
    {
        return $query->where('severity', $severity);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    public function scopeCritical($query)
    {
        return $query->where('severity', 'critical');
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('detected_at', '>=', now()->subDays($days));
    }

    // Methods
    public function markAsResolved()
    {
        $this->update([
            'status' => 'resolved',
            'resolved_at' => now()
        ]);
    }

    public function markAsClosed()
    {
        $this->update([
            'status' => 'closed',
            'resolved_at' => $this->resolved_at ?? now()
        ]);
    }

    public function assignTo(User $user)
    {
        $this->update(['assigned_to' => $user->id]);
    }

    // Auto-generate incident ID
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($incident) {
            if (empty($incident->incident_id)) {
                $year = date('Y');
                $lastIncident = static::whereYear('created_at', $year)->latest()->first();
                $number = $lastIncident ? intval(substr($lastIncident->incident_id, -3)) + 1 : 1;
                $incident->incident_id = 'CSIRT-' . $year . '-' . str_pad($number, 3, '0', STR_PAD_LEFT);
            }
        });
    }
}