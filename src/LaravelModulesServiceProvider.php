<?php

namespace Kizi\Settings;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Kizi\Settings\Providers\BootstrapServiceProvider;
use Kizi\Settings\Providers\ConsoleServiceProvider;
use Kizi\Settings\Providers\ContractsServiceProvider;
use Kizi\Settings\Support\Stub;

class LaravelModulesServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Booting the package.
     */
    public function boot()
    {
        $this->registerNamespaces();

        $this->registerModules();
    }

    /**
     * Register all modules.
     */
    protected function registerModules()
    {
        $this->app->register(BootstrapServiceProvider::class);
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->registerServices();
        $this->setupStubPath();
        $this->registerProviders();
        $loader = AliasLoader::getInstance();

        $loader->alias('Module', \Kizi\Modules\Facades\Module::class);
    }

    /**
     * Setup stub path.
     */
    public function setupStubPath()
    {
        $this->app->booted(function ($app) {
            Stub::setBasePath(__DIR__ . '/Commands/stubs');

            if ($app['modules']->config('stubs.enabled') === true) {
                Stub::setBasePath($app['modules']->config('stubs.path'));
            }
        });
    }

    /**
     * Register package's namespaces.
     */
    protected function registerNamespaces()
    {
        // $configPath = __DIR__ . '/../config/config.php';
        // $this->mergeConfigFrom($configPath, 'modules');
        // $this->publishes([
        //     $configPath => config_path('modules.php'),
        // ], 'config');
    }

    /**
     * Register the service provider.
     */
    protected function registerServices()
    {
        $this->app->singleton('modules', function ($app) {
            $path = $app['config']->get('modules.paths.modules');

            return new Repository($app, $path);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['modules'];
    }

    /**
     * Register providers.
     */
    protected function registerProviders()
    {
        $this->app->register(ConsoleServiceProvider::class);
        $this->app->register(ContractsServiceProvider::class);
    }
}
