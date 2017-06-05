<?php

namespace Kizi\Settings\Providers;

use Illuminate\Support\ServiceProvider;
use Kizi\Settings\Commands\CommandCommand;
use Kizi\Settings\Commands\ControllerCommand;
use Kizi\Settings\Commands\DisableCommand;
use Kizi\Settings\Commands\DumpCommand;
use Kizi\Settings\Commands\EnableCommand;
use Kizi\Settings\Commands\GenerateEventCommand;
use Kizi\Settings\Commands\GenerateJobCommand;
use Kizi\Settings\Commands\GenerateListenerCommand;
use Kizi\Settings\Commands\GenerateMailCommand;
use Kizi\Settings\Commands\GenerateMiddlewareCommand;
use Kizi\Settings\Commands\GenerateNotificationCommand;
use Kizi\Settings\Commands\GenerateProviderCommand;
use Kizi\Settings\Commands\GenerateRouteProviderCommand;
use Kizi\Settings\Commands\InstallCommand;
use Kizi\Settings\Commands\ListCommand;
use Kizi\Settings\Commands\MakeCommand;
use Kizi\Settings\Commands\MakeRequestCommand;
use Kizi\Settings\Commands\MigrateCommand;
use Kizi\Settings\Commands\MigrateRefreshCommand;
use Kizi\Settings\Commands\MigrateResetCommand;
use Kizi\Settings\Commands\MigrateRollbackCommand;
use Kizi\Settings\Commands\MigrationCommand;
use Kizi\Settings\Commands\ModelCommand;
use Kizi\Settings\Commands\PublishCommand;
use Kizi\Settings\Commands\PublishConfigurationCommand;
use Kizi\Settings\Commands\PublishMigrationCommand;
use Kizi\Settings\Commands\PublishTranslationCommand;
use Kizi\Settings\Commands\SeedCommand;
use Kizi\Settings\Commands\SeedMakeCommand;
use Kizi\Settings\Commands\SetupCommand;
use Kizi\Settings\Commands\UpdateCommand;
use Kizi\Settings\Commands\UseCommand;

class ConsoleServiceProvider extends ServiceProvider
{
    protected $defer = false;

    /**
     * The available commands
     *
     * @var array
     */
    protected $commands = [
        MakeCommand::class,
        CommandCommand::class,
        ControllerCommand::class,
        DisableCommand::class,
        EnableCommand::class,
        GenerateEventCommand::class,
        GenerateListenerCommand::class,
        GenerateMiddlewareCommand::class,
        GenerateProviderCommand::class,
        GenerateRouteProviderCommand::class,
        InstallCommand::class,
        ListCommand::class,
        MigrateCommand::class,
        MigrateRefreshCommand::class,
        MigrateResetCommand::class,
        MigrateRollbackCommand::class,
        MigrationCommand::class,
        ModelCommand::class,
        PublishCommand::class,
        PublishMigrationCommand::class,
        PublishTranslationCommand::class,
        SeedCommand::class,
        SeedMakeCommand::class,
        SetupCommand::class,
        UpdateCommand::class,
        UseCommand::class,
        DumpCommand::class,
        MakeRequestCommand::class,
        PublishConfigurationCommand::class,
        GenerateJobCommand::class,
        GenerateMailCommand::class,
        GenerateNotificationCommand::class,
    ];

    /**
     * Register the commands.
     */
    public function register()
    {
        $this->commands($this->commands);
    }

    /**
     * @return array
     */
    public function provides()
    {
        $provides = $this->commands;

        return $provides;
    }
}
