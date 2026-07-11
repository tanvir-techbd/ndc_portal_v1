<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

/**
 * Global setting() helper (see helpers.php) calls get() so Blade templates
 * never query the DB directly for individual settings. See
 * LARAVEL-DYNAMIZATION-PLAN.md Part 3.
 */
class SettingService
{
    private const TTL_MINUTES = 60;

    public function __construct(private AuditService $audit)
    {
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return Cache::remember("setting:{$key}", now()->addMinutes(self::TTL_MINUTES), function () use ($key, $default) {
            return Setting::where('key', $key)->value('value') ?? $default;
        });
    }

    public function set(string $key, mixed $value): Setting
    {
        $setting = Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget("setting:{$key}");

        return $setting;
    }

    /**
     * @param  array<string, mixed>  $values
     */
    public function setBulk(array $values, ?User $actor = null): void
    {
        $before = Setting::whereIn('key', array_keys($values))->pluck('value', 'key');

        foreach ($values as $key => $value) {
            $this->set($key, $value);
        }

        $this->audit->record($actor, 'setting.update', null, [
            'before' => $before,
            'after' => $values,
        ]);
    }

    public function swapLogo(string $settingKey, int $mediaAssetId, ?User $actor = null): void
    {
        $this->set($settingKey, (string) $mediaAssetId);
        $this->audit->record($actor, 'setting.logo_swap', null, ['key' => $settingKey, 'media_id' => $mediaAssetId]);
    }
}
