<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MediaAsset extends Model
{
    protected $fillable = [
        'original_filename',
        'storage_path',
        'public_url',
        'mime_type',
        'size_bytes',
        'category',
        'uploaded_by',
    ];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function resolvedUrl(): string
    {
        return $this->public_url ?? Storage::disk('public')->url($this->storage_path);
    }
}
