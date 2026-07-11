<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class AuditService
{
    /**
     * @param  string  $action  entity.verb format, e.g. "notice.publish", "pricing.update"
     * @param  array<string, mixed>  $meta  before/after diff of changed fields
     */
    public function record(?User $user, string $action, ?Model $entity = null, array $meta = []): AuditLog
    {
        return AuditLog::create([
            'user_id' => $user?->id,
            'action' => $action,
            'entity_type' => $entity ? $entity::class : null,
            'entity_id' => $entity?->getKey(),
            'meta' => $meta,
        ]);
    }

    public function recent(int $limit = 10): Collection
    {
        return AuditLog::with('user')->latest()->limit($limit)->get();
    }
}
