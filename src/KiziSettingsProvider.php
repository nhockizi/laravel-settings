<?php

namespace Kizi\Settings;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class KiziSettingsProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $commands = [
        'Kizi\Settings\Commands\InstallSettings',
    ];
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([__DIR__ . '/../config/kizi.settings.php' => config_path('kizi.settings.php')], 'kizi-settings');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerRouteMiddleware();
        $this->commands($this->commands);
    }
    /**
     * Register the route middleware.
     *
     * @return void
     */
    protected function registerRouteMiddleware()
    {

    }
}
