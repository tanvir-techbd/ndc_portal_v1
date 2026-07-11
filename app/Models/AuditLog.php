<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    public $timestamps = true;
    const UPDATED_AT = null; // append-only log, no update timestamp needed

    protected $fillable = [
        'user_id',
        'action',
        'entity_type',
        'entity_id',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'meta' => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
