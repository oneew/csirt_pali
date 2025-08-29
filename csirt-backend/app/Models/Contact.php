<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'organization',
        'position',
        'country',
        'contact_type',
        'message',
        'status',
        'contacted_at',
        'notes',
    ];

    protected $casts = [
        'contacted_at' => 'datetime',
    ];

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'warning',
            'contacted' => 'info',
            'resolved' => 'success'
        ];

        return $badges[$this->status] ?? 'secondary';
    }

    public function getContactTypeBadgeAttribute()
    {
        $badges = [
            'member' => 'primary',
            'partner' => 'success',
            'external' => 'info',
            'emergency' => 'danger'
        ];

        return $badges[$this->contact_type] ?? 'secondary';
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeContacted($query)
    {
        return $query->where('status', 'contacted');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('contact_type', $type);
    }

    public function scopeEmergency($query)
    {
        return $query->where('contact_type', 'emergency');
    }

    public function scopeByCountry($query, $country)
    {
        return $query->where('country', $country);
    }

    // Methods
    public function markAsContacted($notes = null)
    {
        $this->update([
            'status' => 'contacted',
            'contacted_at' => now(),
            'notes' => $notes
        ]);
    }

    public function markAsResolved($notes = null)
    {
        $this->update([
            'status' => 'resolved',
            'notes' => $notes
        ]);
    }

    public function addNotes($notes)
    {
        $existingNotes = $this->notes ? $this->notes . "\n\n" : '';
        $this->update([
            'notes' => $existingNotes . '[' . now()->format('Y-m-d H:i:s') . '] ' . $notes
        ]);
    }
}