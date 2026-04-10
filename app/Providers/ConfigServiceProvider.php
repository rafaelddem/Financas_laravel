<?php

namespace App\Providers;

use App\Models\Config;
use Illuminate\Support\ServiceProvider;

class ConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if (!app()->runningInConsole()) {
            $settings = Config::pluck('value', 'key')->all();

            foreach ($settings as $key => $value) {
                $config_key = 'services.settings.' . strtolower($key);
                config([$config_key => $value] ?? config($config_key));
            }
        }
    }
}
