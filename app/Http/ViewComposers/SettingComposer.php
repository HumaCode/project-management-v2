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
        $settings = $this->settingService->getAll();
        
        // Handle Media URLs
        $model = Setting::where('key', 'app_name')->first();
        if ($model) {
            $settings['app_logo'] = $model->getFirstMediaUrl('logo');
            $settings['app_favicon'] = $model->getFirstMediaUrl('favicon');
        }

        $view->with('cms_settings', $settings);
    }
}
