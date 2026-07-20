<?php

namespace App\Http\ViewComposers;

use App\Services\SettingService;
use App\Models\Setting;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;

class SettingComposer
{
    protected $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function compose(View $view)
    {
        try {
            $settings = $this->settingService->getAll();
            
            // Handle Media URLs
            $model = Setting::where('key', 'app_name')->first();
            if ($model) {
                try {
                    $logo = $model->getFirstMediaUrl('logo');
                    if ($logo) $settings['app_logo'] = $logo;
                    
                    $favicon = $model->getFirstMediaUrl('favicon');
                    if ($favicon) $settings['app_favicon'] = $favicon;
                } catch (\Throwable $e) {
                    // Ignore media library errors
                }
            }

            $view->with('cms_settings', $settings);
        } catch (\Throwable $e) {
            $view->with('cms_settings', []);
        }
    }
}
