<?php

namespace App\Services;

use App\Models\PricingTier;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * See LARAVEL-DYNAMIZATION-PLAN.md Part 3. Called by PricingController to
 * render both public pricing pages and the admin pricing editors — both
 * read through getForPage() so they can never drift apart.
 */
class PricingService
{
    private const TTL_MINUTES = 30;

    public function __construct(private AuditService $audit)
    {
    }

    /**
     * @param  array<string>  $serviceTypePrefixes  e.g. ['cloud'] or ['rbs']
     * @return Collection<string, Collection<int, PricingTier>>  grouped by service_type
     */
    public function getForPage(array $serviceTypePrefixes, bool $publicOnly = true): Collection
    {
        $cacheKey = 'pricing:' . implode(',', $serviceTypePrefixes) . ($publicOnly ? ':public' : ':all');

        // Cache plain attribute arrays, not Eloquent model instances — caching
        // full model objects via the database/file cache driver is fragile
        // (unserialize() can return __PHP_Incomplete_Class across process
        // boundaries). Rehydrate into real PricingTier models after the read.
        $rows = Cache::remember($cacheKey, now()->addMinutes(self::TTL_MINUTES), function () use ($serviceTypePrefixes, $publicOnly) {
            $query = PricingTier::query()->orderBy('display_order');

            if ($publicOnly) {
                $query->visible();
            }

            $query->where(function ($q) use ($serviceTypePrefixes) {
                foreach ($serviceTypePrefixes as $prefix) {
                    $q->orWhere('service_type', 'like', "{$prefix}_%");
                }
            });

            return $query->get()->map->getAttributes()->all();
        });

        return PricingTier::hydrate($rows)->groupBy('service_type');
    }

    public function updatePrice(string $tierKey, ?string $priceValue, string $priceDisplay, ?User $actor = null): PricingTier
    {
        $tier = PricingTier::where('tier_key', $tierKey)->firstOrFail();
        $before = ['price_value' => $tier->price_value, 'price_display' => $tier->price_display];

        $tier->update(['price_value' => $priceValue, 'price_display' => $priceDisplay]);
        $this->flushCache();

        $this->audit->record($actor, 'pricing.update', $tier, ['before' => $before, 'after' => $priceDisplay]);

        return $tier;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function updateTier(string $tierKey, array $data, ?User $actor = null): PricingTier
    {
        $tier = PricingTier::where('tier_key', $tierKey)->firstOrFail();
        $before = $tier->getAttributes();

        $tier->update($data);
        $this->flushCache();

        $this->audit->record($actor, 'pricing.update', $tier, ['before' => $before, 'after' => $tier->getAttributes()]);

        return $tier;
    }

    public function toggleVisibility(string $tierKey, bool $visible, ?User $actor = null): PricingTier
    {
        $tier = PricingTier::where('tier_key', $tierKey)->firstOrFail();
        $tier->update(['is_visible' => $visible]);
        $this->flushCache();

        $this->audit->record($actor, 'pricing.toggle_visibility', $tier, ['is_visible' => $visible]);

        return $tier;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function addTier(array $data, ?User $actor = null): PricingTier
    {
        $tier = PricingTier::create($data);
        $this->flushCache();
        $this->audit->record($actor, 'pricing.add_tier', $tier);

        return $tier;
    }

    public function deleteTier(string $tierKey, ?User $actor = null): void
    {
        $tier = PricingTier::where('tier_key', $tierKey)->firstOrFail();
        $this->audit->record($actor, 'pricing.delete_tier', $tier, ['tier_key' => $tierKey]);
        $tier->delete();
        $this->flushCache();
    }

    public function flushCache(): void
    {
        Cache::flush();
    }
}
