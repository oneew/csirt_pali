<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'description',
        'old_data',
        'new_data',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function model()
    {
        return $this->morphTo();
    }

    // Accessors
    public function getActionBadgeAttribute()
    {
        $badges = [
            'created' => 'success',
            'updated' => 'warning',
            'deleted' => 'danger',
            'viewed' => 'info',
            'login' => 'primary',
            'logout' => 'secondary'
        ];

        return $badges[$this->action] ?? 'secondary';
    }

    public function getModelNameAttribute()
    {
        if ($this->model_type) {
            return class_basename($this->model_type);
        }
        return null;
    }

    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    // Scopes
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    public function scopeByModel($query, $modelType, $modelId = null)
    {
        $query = $query->where('model_type', $modelType);
        
        if ($modelId) {
            $query->where('model_id', $modelId);
        }
        
        return $query;
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    // Static methods for logging activities
    public static function logActivity($action, $description, $model = null, $oldData = null, $newData = null)
    {
        $user = auth()->user();
        $request = request();

        return static::create([
            'user_id' => $user ? $user->id : null,
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model ? $model->id : null,
            'description' => $description,
            'old_data' => $oldData,
            'new_data' => $newData,
            'ip_address' => $request ? $request->ip() : null,
            'user_agent' => $request ? $request->userAgent() : null,
        ]);
    }

    public static function logLogin($user)
    {
        return static::logActivity(
            'login',
            "User {$user->full_name} logged in"
        );
    }

    public static function logLogout($user)
    {
        return static::logActivity(
            'logout',
            "User {$user->full_name} logged out"
        );
    }

    public static function logCreated($model, $description = null)
    {
        $modelName = class_basename($model);
        $description = $description ?: "Created {$modelName}";
        
        return static::logActivity(
            'created',
            $description,
            $model,
            null,
            $model->toArray()
        );
    }

    public static function logUpdated($model, $oldData, $description = null)
    {
        $modelName = class_basename($model);
        $description = $description ?: "Updated {$modelName}";
        
        return static::logActivity(
            'updated',
            $description,
            $model,
            $oldData,
            $model->toArray()
        );
    }

    public static function logDeleted($model, $description = null)
    {
        $modelName = class_basename($model);
        $description = $description ?: "Deleted {$modelName}";
        
        return static::logActivity(
            'deleted',
            $description,
            $model,
            $model->toArray(),
            null
        );
    }

    public static function logViewed($model, $description = null)
    {
        $modelName = class_basename($model);
        $description = $description ?: "Viewed {$modelName}";
        
        return static::logActivity(
            'viewed',
            $description,
            $model
        );
    }
}