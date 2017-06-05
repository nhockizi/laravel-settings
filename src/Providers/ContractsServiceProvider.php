<?php

namespace Kizi\Settings\Providers;

use Illuminate\Support\ServiceProvider;
use Kizi\Settings\Contracts\RepositoryInterface;
use Kizi\Settings\Repository;

class ContractsServiceProvider extends ServiceProvider
{
    /**
     * Register some binding.
     */
    public function register()
    {
        $this->app->bind(RepositoryInterface::class, Repository::class);
    }
}
