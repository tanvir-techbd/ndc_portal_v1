<?php

namespace App\Services;

use App\Models\Notice;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class NoticeService
{
    public function __construct(private AuditService $audit)
    {
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function createOrUpdate(array $data, ?User $actor = null, ?Notice $notice = null): Notice
    {
        $data['body_html'] = Str::of($data['body_html'] ?? '')->stripTags(
            '<p><br><strong><em><ul><ol><li><a><h3><h4>'
        )->toString();

        if ($notice) {
            $before = $notice->only(array_keys($data));
            $notice->update($data);
            $this->audit->record($actor, 'notice.update', $notice, ['before' => $before, 'after' => $data]);

            return $notice;
        }

        $notice = Notice::create($data + ['created_by' => $actor?->id]);
        $this->audit->record($actor, 'notice.create', $notice);

        return $notice;
    }

    public function publish(Notice $notice, ?User $actor = null): Notice
    {
        $notice->update(['status' => 'published', 'published_at' => $notice->published_at ?? now()]);
        $this->audit->record($actor, 'notice.publish', $notice);

        return $notice;
    }

    /**
     * @param  array<int>  $ids
     */
    public function bulkAction(array $ids, string $action, ?User $actor = null): int
    {
        $notices = Notice::whereIn('id', $ids)->get();

        foreach ($notices as $notice) {
            match ($action) {
                'publish' => $notice->update(['status' => 'published', 'published_at' => $notice->published_at ?? now()]),
                'draft' => $notice->update(['status' => 'draft']),
                'delete' => $notice->delete(),
                default => null,
            };
        }

        $this->audit->record($actor, "notice.bulk_{$action}", null, ['ids' => $ids]);

        return $notices->count();
    }

    /**
     * @param  array{category?: string}  $filters
     */
    public function getPublic(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        return Notice::public()
            ->when($filters['category'] ?? null, fn ($q, $category) => $q->where('category', $category))
            ->orderByDesc('published_at')
            ->paginate($perPage);
    }

    public function latestPublic(int $limit = 4): Collection
    {
        return Notice::public()->orderByDesc('published_at')->limit($limit)->get();
    }

    /**
     * Homepage "Latest News" panel — day-to-day operational announcements
     * and service updates. See latestOfficialNotices() for the formal/
     * administrative counterpart; together these must not overlap or the
     * two homepage panels just show the same list twice.
     */
    public function latestNews(int $limit = 4): Collection
    {
        return Notice::public()
            ->whereIn('category', ['services', 'general'])
            ->orderByDesc('published_at')
            ->limit($limit)
            ->get();
    }

    /**
     * Homepage "Official Notices" panel — formal circulars: maintenance
     * windows, tenders, policy/fee changes, security advisories.
     */
    public function latestOfficialNotices(int $limit = 7): Collection
    {
        return Notice::public()
            ->whereIn('category', ['maintenance', 'tender', 'policy', 'security'])
            ->orderByDesc('published_at')
            ->limit($limit)
            ->get();
    }
}
