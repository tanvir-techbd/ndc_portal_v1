<?php

namespace App\Services;

use App\Models\Page;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * Manages CMS page content_blocks JSON. See LARAVEL-DYNAMIZATION-PLAN.md Part 3.
 */
class ContentService
{
    private const TTL_MINUTES = 60;

    public function __construct(private AuditService $audit)
    {
    }

    public function getBlocks(string $slug): array
    {
        return Cache::remember("page:{$slug}", now()->addMinutes(self::TTL_MINUTES), function () use ($slug) {
            return Page::where('slug', $slug)->value('content_blocks') ?? [];
        });
    }

    public function getPage(string $slug): ?Page
    {
        return Page::where('slug', $slug)->first();
    }

    /**
     * @param  array<string, mixed>  $blocks
     */
    public function updateBlocks(string $slug, array $blocks, ?User $actor = null): Page
    {
        $page = Page::where('slug', $slug)->firstOrFail();
        $before = $page->content_blocks;

        $page->update(['content_blocks' => array_merge($page->content_blocks ?? [], $blocks)]);
        Cache::forget("page:{$slug}");

        $this->audit->record($actor, 'page.update', $page, ['before' => $before, 'after' => $page->content_blocks]);

        return $page;
    }

    public function getFeaturedServices(): Collection
    {
        return Service::visible()->featured()->orderBy('display_order')->get();
    }
}
