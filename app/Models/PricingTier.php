<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingTier extends Model
{
    protected $fillable = [
        'tier_key',
        'service_type',
        'name',
        'price_value',
        'price_unit',
        'price_display',
        'specs',
        'is_visible',
        'display_order',
    ];

    protected function casts(): array
    {
        return [
            'specs' => 'array',
            'is_visible' => 'boolean',
            'price_value' => 'decimal:2',
        ];
    }

    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    public function scopeForServiceType($query, string $serviceType)
    {
        return $query->where('service_type', $serviceType);
    }

    /**
     * Formats a numeric price into the standard displayed BDT string, e.g.
     * formatDisplay(3000, '/mo') === '৳3,000.00/mo'. Used whenever
     * price_unit is set, so admins only ever type a number — the display
     * string is never hand-typed for the common case.
     */
    public static function formatDisplay(string|float $value, ?string $unit): string
    {
        return '৳' . number_format((float) $value, 2) . ($unit ?? '');
    }
}
