<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name',
        'tag',
        'slug',
        'kind',
        'group_slug',
        'description',
        'icon',
        'tiers',
        'features',
        'is_featured',
        'is_visible',
        'display_order',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'is_visible' => 'boolean',
            'tiers' => 'array',
            'features' => 'array',
        ];
    }

    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeGroups($query)
    {
        return $query->where('kind', 'group');
    }

    public function scopeDetails($query)
    {
        return $query->where('kind', 'detail');
    }
}
