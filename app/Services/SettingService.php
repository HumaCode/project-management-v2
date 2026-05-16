<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingService
{
    /**
     * Get setting value by key.
     */
    public function get(string $key, $default = null)
    {
        return Cache::rememberForever("setting.$key", function () use ($key, $default) {
            $setting = Setting::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set setting value.
     */
    public function set(string $key, $value, string $group = 'general')
    {
        $setting = Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group]
        );

        Cache::forget("setting.$key");
        return $setting;
    }

    /**
     * Update system profile.
     */
    public function updateProfile(array $data)
    {
        foreach ($data as $key => $value) {
            if (!in_array($key, ['logo', 'favicon'])) {
                $this->set($key, $value, 'profile');
            }
        }

        return true;
    }

    /**
     * Get all settings as an object/array.
     */
    public function getAll()
    {
        return Setting::all()->pluck('value', 'key')->toArray();
    }
}
