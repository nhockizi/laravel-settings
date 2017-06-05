<?php
namespace Kizi\Settings;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class ThemesServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the service provider.
     *
     * @return null
     */
    public function boot()
    {
        // $this->publishes([
        //     __DIR__.'/../config/themes.php' => config_path('themes.php')
        // ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // $this->mergeConfigFrom(
        //     __DIR__.'/../config/themes.php', 'caffeinated.themes'
        // );
        $this->registerAliases();
        $this->registerServices();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return ['themes'];
    }

    /**
     * Register the package services.
     *
     * @return void
     */
    protected function registerServices()
    {
        $this->app->singleton('themes', function ($app) {
            return new Themes($app['files'], $app['config'], $app['view']);
        });

        $this->app->booting(function ($app) {
            $app['themes']->register();
        });
    }
    /**
     * Register the aliases from this module.
     */
    protected function registerAliases()
    {
        $loader = AliasLoader::getInstance();
        $loader->alias('Theme', \Kizi\Settings\Facades\Theme::class);
    }
}
