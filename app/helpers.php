<?php

use App\Models\Setting;

if (!function_exists('setting')) {
    /**
     * Get a setting value from the database with caching
     *
     * @param string $key The setting key
     * @param mixed $default The default value if setting doesn't exist
     * @return mixed
     */
    function setting(string $key, $default = null)
    {
        return Setting::get($key, $default);
    }
}

if (!function_exists('setting_bool')) {
    /**
     * Get a boolean setting value
     *
     * @param string $key The setting key
     * @param bool $default The default value if setting doesn't exist
     * @return bool
     */
    function setting_bool(string $key, bool $default = false): bool
    {
        return Setting::getBool($key, $default);
    }
}
