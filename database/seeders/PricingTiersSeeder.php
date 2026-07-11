<?php

namespace Database\Seeders;

use App\Models\PricingTier;
use App\Services\PricingService;
use Illuminate\Database\Seeder;

/**
 * Seeded from the live pricing tables at ndc.bcc.gov.bd (Cloud Based
 * Service, page_id=902; Request Based Service, page_id=787) — the full,
 * real fee schedule, not a partial extraction from the static prototype.
 * See LARAVEL-DYNAMIZATION-PLAN.md Phase 4.1. tier_key values are freshly
 * assigned slugs (the source pages have no stable IDs) — kept stable here
 * once seeded. Truncates first since the row set has changed shape
 * (66 cloud + 37 request-based tiers) since the original partial seed.
 */
class PricingTiersSeeder extends Seeder
{
    public function run(): void
    {
        PricingTier::truncate();

        $path = __DIR__ . '/data/pricing_tiers.json';
        $rows = json_decode(file_get_contents($path), true);

        foreach ($rows as $row) {
            PricingTier::create([
                'tier_key' => $row['tier_key'],
                'service_type' => $row['service_type'],
                'name' => $row['name'],
                'price_value' => $row['price_value'],
                'price_unit' => $row['price_unit'] ?? null,
                'price_display' => $row['price_display'],
                'specs' => $row['specs'],
                'is_visible' => true,
                'display_order' => $row['display_order'],
            ]);
        }

        // Reseeding replaces every row's underlying data; without this, a
        // previously-cached response (see PricingService::getForPage) can
        // keep serving stale or shape-mismatched data for up to TTL_MINUTES.
        app(PricingService::class)->flushCache();

        $this->command->info('Seeded ' . count($rows) . ' pricing tiers.');
    }
}
