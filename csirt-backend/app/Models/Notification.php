<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'category',
        'data',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessors
    public function getTypeBadgeAttribute()
    {
        $badges = [
            'info' => 'info',
            'success' => 'success',
            'warning' => 'warning',
            'error' => 'danger'
        ];

        return $badges[$this->type] ?? 'secondary';
    }

    public function getCategoryIconAttribute()
    {
        $icons = [
            'incident' => 'fas fa-exclamation-triangle',
            'news' => 'fas fa-newspaper',
            'system' => 'fas fa-cog',
            'security' => 'fas fa-shield-alt',
            'user' => 'fas fa-user'
        ];

        return $icons[$this->category] ?? 'fas fa-bell';
    }

    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // Methods
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now()
        ]);
    }

    public function markAsUnread()
    {
        $this->update([
            'is_read' => false,
            'read_at' => null
        ]);
    }

    // Static methods for creating notifications
    public static function createForUser($userId, $title, $message, $type = 'info', $category = 'system', $data = null)
    {
        return static::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'category' => $category,
            'data' => $data
        ]);
    }

    public static function createForAllUsers($title, $message, $type = 'info', $category = 'system', $data = null)
    {
        $users = User::active()->get();
        $notifications = [];

        foreach ($users as $user) {
            $notifications[] = [
                'user_id' => $user->id,
                'title' => $title,
                'message' => $message,
                'type' => $type,
                'category' => $category,
                'data' => $data,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        return static::insert($notifications);
    }

    public static function createIncidentNotification($userId, $incident)
    {
        return static::createForUser(
            $userId,
            'New Incident: ' . $incident->title,
            'A new incident has been reported with severity: ' . $incident->severity,
            $incident->severity === 'critical' ? 'error' : 'warning',
            'incident',
            ['incident_id' => $incident->id]
        );
    }

    public static function createNewsNotification($userId, $news)
    {
        return static::createForUser(
            $userId,
            'News Published: ' . $news->title,
            'A new news article has been published.',
            'info',
            'news',
            ['news_id' => $news->id]
        );
    }
}