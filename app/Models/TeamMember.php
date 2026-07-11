<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    protected $fillable = [
        'name',
        'designation',
        'group',
        'photo_media_id',
        'display_order',
    ];

    public function photo()
    {
        return $this->belongsTo(MediaAsset::class, 'photo_media_id');
    }
}
