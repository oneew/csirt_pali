<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'content',
        'icon',
        'category',
        'is_active',
        'is_featured',
        'order',
        'features',
        'contact_email',
        'contact_phone',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'features' => 'array',
        'order' => 'integer',
    ];

    // Accessors
    public function getCategoryBadgeAttribute()
    {
        $badges = [
            'incident_response' => 'danger',
            'threat_intelligence' => 'warning',
            'training' => 'info',
            'consultation' => 'primary',
            'assessment' => 'success'
        ];

        return $badges[$this->category] ?? 'secondary';
    }

    public function getIconUrlAttribute()
    {
        return $this->icon 
            ? asset('storage/' . $this->icon) 
            : asset('frontend/images/default-service-icon.png');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('name');
    }

    // Methods
    public function activate()
    {
        $this->update(['is_active' => true]);
    }

    public function deactivate()
    {
        $this->update(['is_active' => false]);
    }

    public function markAsFeatured()
    {
        $this->update(['is_featured' => true]);
    }

    public function unmarkAsFeatured()
    {
        $this->update(['is_featured' => false]);
    }

    public function addFeature($feature)
    {
        $features = $this->features ?? [];
        if (!in_array($feature, $features)) {
            $features[] = $feature;
            $this->update(['features' => $features]);
        }
    }

    public function removeFeature($feature)
    {
        $features = $this->features ?? [];
        $features = array_filter($features, function($f) use ($feature) {
            return $f !== $feature;
        });
        $this->update(['features' => array_values($features)]);
    }

    // Auto-generate slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($service) {
            if (empty($service->slug)) {
                $service->slug = Str::slug($service->name);
                
                // Ensure unique slug
                $originalSlug = $service->slug;
                $counter = 1;
                while (static::where('slug', $service->slug)->exists()) {
                    $service->slug = $originalSlug . '-' . $counter;
                    $counter++;
                }
            }
        });

        static::updating(function ($service) {
            if ($service->isDirty('name') && !$service->isDirty('slug')) {
                $service->slug = Str::slug($service->name);
                
                // Ensure unique slug
                $originalSlug = $service->slug;
                $counter = 1;
                while (static::where('slug', $service->slug)->where('id', '!=', $service->id)->exists()) {
                    $service->slug = $originalSlug . '-' . $counter;
                    $counter++;
                }
            }
        });
    }
}