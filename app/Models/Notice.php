<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'body_html',
        'category',
        'status',
        'visibility',
        'attachment_media_id',
        'published_at',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    public function attachment()
    {
        return $this->belongsTo(MediaAsset::class, 'attachment_media_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopePublic($query)
    {
        return $query->where('status', 'published')->where('visibility', 'public');
    }
}
