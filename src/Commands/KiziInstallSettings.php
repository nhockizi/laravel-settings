<?php

namespace Kizi\Settings\Commands;

use Illuminate\Console\Command;
use Kizi\Settings\Commands\Installers\Installer;
use Kizi\Settings\Commands\Installers\Traits\BlockMessage;
use Kizi\Settings\Commands\Installers\Traits\SectionMessage;
use Symfony\Component\Console\Input\InputOption;

class KiziInstallSettings extends Command
{
    use BlockMessage, SectionMessage;
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'kizi:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the settings';

    /**
     * Install directory.
     *
     * @var string
     */
    protected $directory = '';

    /**
     * @var Installer
     */
    private $installer;

    /**
     * Create a new command instance.
     *
     * @param Installer $installer
     * @internal param Filesystem $finder
     * @internal param Application $app
     * @internal param Composer $composer
     */
    public function __construct(Installer $installer)
    {
        parent::__construct();
        $this->getLaravel()['env'] = 'local';
        $this->installer           = $installer;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $this->blockMessage('Welcome!', 'Starting the installation settings process...', 'comment');
        $success = $this->installer->stack([
            \Kizi\Settings\Commands\Installers\Scripts\ProtectInstaller::class,
            \Kizi\Settings\Commands\Installers\Scripts\ConfigureDatabase::class,
            \Kizi\Settings\Commands\Installers\Scripts\SetAppKey::class,
        ])->install($this);
        if ($success) {
            $this->call('vendor:publish', [
                '--provider' => \Kizi\Settings\KiziSettingsProvider::class,
            ]);
            $this->info('Settings ready! You can use');
        }
    }

    protected function getOptions()
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'Force the installation, even if already installed'],
        ];
    }
}
