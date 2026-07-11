<?php

use App\Services\SettingService;

if (! function_exists('setting')) {
    /**
     * Global settings accessor — Blade templates never query the Setting
     * model directly. See LARAVEL-DYNAMIZATION-PLAN.md Part 3.
     */
    function setting(string $key, mixed $default = null): mixed
    {
        return app(SettingService::class)->get($key, $default);
    }
}
