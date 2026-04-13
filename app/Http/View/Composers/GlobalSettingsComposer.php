<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;
use App\Settings\GeneralSettings;
use Awcodes\Curator\Models\Media;

class GlobalSettingsComposer
{
    protected ?GeneralSettings $setting = null;

    public function __construct()
    {
        try {
            $this->setting = app(GeneralSettings::class);
        } catch (\Exception $e) {
            // Do nothing if settings table isn't migrated
        }
    }

    public function compose(View $view): void
    {
        if (!$this->setting) {
            $view->with('globalFaviconUrl', asset('favicon.ico'));
            $view->with('globalMetaImageUrl', '');
            $view->with('globalLogoUrl', '');
            return;
        }

        // Cache the resolution of URLs to avoid DB hits
        $urls = Cache::remember('global_settings_media_urls', 60 * 24, function () {
            $favMedia = (!empty($this->setting->favicon) && is_numeric($this->setting->favicon)) 
                ? Media::find($this->setting->favicon) : null;
            
            $metaMedia = (!empty($this->setting->meta_image) && is_numeric($this->setting->meta_image)) 
                ? Media::find($this->setting->meta_image) : null;
            
            $logoMedia = (!empty($this->setting->logo) && is_numeric($this->setting->logo)) 
                ? Media::find($this->setting->logo) : null;

            return [
                'globalFaviconUrl' => $favMedia ? url($favMedia->url) : asset('favicon.ico'),
                'globalMetaImageUrl' => $metaMedia ? url($metaMedia->url) : '',
                'globalLogoUrl' => $logoMedia ? url($logoMedia->url) : ''
            ];
        });

        foreach ($urls as $key => $url) {
            $view->with($key, $url);
        }
    }
}
