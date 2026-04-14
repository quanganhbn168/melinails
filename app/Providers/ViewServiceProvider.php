<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Settings\GeneralSettings;
use App\Http\View\Composers\GlobalSettingsComposer;
use Exception;

class ViewServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Share general settings globally to all views
        try {
            View::share('setting', app(GeneralSettings::class));
            View::share('pageSettings', app(\App\Settings\PageSettings::class));
        } catch (Exception $e) {
            // Ignore during setup/migrations
        }

        // Attach Composer to specific views that need media URLs resolved
        View::composer(
            ['layouts.master', 'frontend.index'],
            GlobalSettingsComposer::class
        );
    }
}
