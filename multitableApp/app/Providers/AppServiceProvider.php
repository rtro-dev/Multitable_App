<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Setting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            // Se obtiene el nombre directamente del campo 'name' de la tabla "settings"
            $appName = Setting::first()?->name ?? config('app.name');
            view()->share('appName', $appName);
        } catch (\Exception $e) {
            view()->share('appName', config('app.name'));
        }
    }
}
