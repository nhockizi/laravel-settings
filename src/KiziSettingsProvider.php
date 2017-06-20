<?php

namespace Kizi\Settings;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Kizi\Settings\ThemesServiceProvider;

class KiziSettingsProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $commands = [
        'Kizi\Settings\Commands\KiziInstallSettings',
    ];
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->publishes([__DIR__ . '/../config/kizi.settings.php' => config_path('kizi.settings.php')], 'kizi-settings');
        $this->publishes([__DIR__ . '/../config/modules.php' => config_path('modules.php')], 'kizi-modules');
        $this->publishes([__DIR__ . '/../config/themes.php' => config_path('themes.php')], 'kizi-themes');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerRouteMiddleware();
        $this->registerClass();
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
    /**
     * Register all modules.
     */
    protected function registerClass()
    {
        $this->app->register(ThemesServiceProvider::class);
        $this->app->register(LaravelModulesServiceProvider::class);
        $this->app->register(Providers\AdminServiceProvider::class);
    }
}
