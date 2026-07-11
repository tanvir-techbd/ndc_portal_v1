<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactInquiry extends Model
{
    protected $fillable = [
        'full_name',
        'organization',
        'email',
        'phone',
        'inquiry_type',
        'message',
        'status',
        'source',
        'submitted_from_ip',
    ];

    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }
}
