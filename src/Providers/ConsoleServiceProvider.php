<?php

namespace Kizi\Settings\Providers;

use Illuminate\Support\ServiceProvider;
use Kizi\Settings\Commands\CrawlerCommand;
use Kizi\Settings\Commands\ModuleCommandCommand;
use Kizi\Settings\Commands\ModuleControllerCommand;
use Kizi\Settings\Commands\ModuleDisableCommand;
use Kizi\Settings\Commands\ModuleDumpCommand;
use Kizi\Settings\Commands\ModuleEnableCommand;
use Kizi\Settings\Commands\ModuleGenerateEventCommand;
use Kizi\Settings\Commands\ModuleGenerateJobCommand;
use Kizi\Settings\Commands\ModuleGenerateListenerCommand;
use Kizi\Settings\Commands\ModuleGenerateMailCommand;
use Kizi\Settings\Commands\ModuleGenerateMiddlewareCommand;
use Kizi\Settings\Commands\ModuleGenerateNotificationCommand;
use Kizi\Settings\Commands\ModuleGenerateProviderCommand;
use Kizi\Settings\Commands\ModuleGenerateRouteProviderCommand;
use Kizi\Settings\Commands\ModuleInstallCommand;
use Kizi\Settings\Commands\ModuleListCommand;
use Kizi\Settings\Commands\ModuleMakeCommand;
use Kizi\Settings\Commands\ModuleMakeRequestCommand;
use Kizi\Settings\Commands\ModuleMigrateCommand;
use Kizi\Settings\Commands\ModuleMigrateRefreshCommand;
use Kizi\Settings\Commands\ModuleMigrateResetCommand;
use Kizi\Settings\Commands\ModuleMigrateRollbackCommand;
use Kizi\Settings\Commands\ModuleMigrationCommand;
use Kizi\Settings\Commands\ModuleModelCommand;
use Kizi\Settings\Commands\ModulePublishCommand;
use Kizi\Settings\Commands\ModulePublishConfigurationCommand;
use Kizi\Settings\Commands\ModulePublishMigrationCommand;
use Kizi\Settings\Commands\ModulePublishTranslationCommand;
use Kizi\Settings\Commands\ModuleSeedCommand;
use Kizi\Settings\Commands\ModuleSeedMakeCommand;
use Kizi\Settings\Commands\ModuleSetupCommand;
use Kizi\Settings\Commands\ModuleUpdateCommand;
use Kizi\Settings\Commands\ModuleUseCommand;

class ConsoleServiceProvider extends ServiceProvider
{
    protected $defer = false;

    /**
     * The available commands
     *
     * @var array
     */
    protected $commands = [
        ModuleMakeCommand::class,
        ModuleCommandCommand::class,
        ModuleControllerCommand::class,
        ModuleDisableCommand::class,
        ModuleEnableCommand::class,
        ModuleGenerateEventCommand::class,
        ModuleGenerateListenerCommand::class,
        ModuleGenerateMiddlewareCommand::class,
        ModuleGenerateProviderCommand::class,
        ModuleGenerateRouteProviderCommand::class,
        ModuleInstallCommand::class,
        ModuleListCommand::class,
        ModuleMigrateCommand::class,
        ModuleMigrateRefreshCommand::class,
        ModuleMigrateResetCommand::class,
        ModuleMigrateRollbackCommand::class,
        ModuleMigrationCommand::class,
        ModuleModelCommand::class,
        ModulePublishCommand::class,
        ModulePublishMigrationCommand::class,
        ModulePublishTranslationCommand::class,
        ModuleSeedCommand::class,
        ModuleSeedMakeCommand::class,
        ModuleSetupCommand::class,
        ModuleUpdateCommand::class,
        ModuleUseCommand::class,
        ModuleDumpCommand::class,
        ModuleMakeRequestCommand::class,
        ModulePublishConfigurationCommand::class,
        ModuleGenerateJobCommand::class,
        ModuleGenerateMailCommand::class,
        ModuleGenerateNotificationCommand::class,
        CrawlerCommand::class,
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
