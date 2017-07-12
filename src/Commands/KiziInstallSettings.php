<?php

namespace Kizi\Settings\Commands;

use Illuminate\Console\Command;
use Kizi\Settings\Commands\Installers\Installer;
use Kizi\Settings\Commands\Installers\Traits\BlockMessage;
use Kizi\Settings\Commands\Installers\Traits\SectionMessage;
use Kizi\Settings\Facades\Admin;
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
            $this->info('Settings ready! You can use');
        }
        $this->initAdminDirectory();
        $this->call('vendor:publish', [
            '--provider' => \Kizi\Settings\KiziSettingsProvider::class,
        ]);
        $this->publishDatabase();
    }

    protected function getOptions()
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'Force the installation, even if already installed'],
        ];
    }
    /**
     * Create tables and seed it.
     *
     * @return void
     */
    public function publishDatabase()
    {
        $this->call('migrate', ['--path' => str_replace(base_path(), '', __DIR__) . '/../../migrations/']);

        $this->call('db:seed', ['--class' => \Kizi\Settings\Auth\Database\AdminTablesSeeder::class]);
    }
    /**
     * Initialize the admin directory.
     *
     * @return void
     */
    protected function initAdminDirectory()
    {
        $this->directory = config('admin.directory');

        if (is_dir($this->directory)) {
            $this->line("<error>{$this->directory} directory already exists !</error> ");

            return;
        }

        $this->makeDir('/');
        $this->line('<info>Admin directory was created:</info> ' . str_replace(base_path(), '', $this->directory));

        $this->makeDir('Controllers');

        $this->createHomeController();
        // $this->createExampleController();
        //$this->createAuthController();
        //$this->createAdministratorController();

        //$this->createMenuFile();
        $this->createBootstrapFile();
        $this->createRoutesFile();

        //$this->copyLanguageFiles();
    }
    /**
     * Create HomeController.
     *
     * @return void
     */
    public function createHomeController()
    {
        $homeController = $this->directory . '/Controllers/HomeController.php';
        $contents       = $this->getStub('HomeController');

        $this->laravel['files']->put(
            $homeController,
            str_replace('DummyNamespace', Admin::controllerNamespace(), $contents)
        );
        $this->line('<info>HomeController file was created:</info> ' . str_replace(base_path(), '', $homeController));
    }

    /**
     * Create HomeController.
     *
     * @return void
     */
    public function createExampleController()
    {
        $exampleController = $this->directory . '/Controllers/ExampleController.php';
        $contents          = $this->getStub('ExampleController');

        $this->laravel['files']->put(
            $exampleController,
            str_replace('DummyNamespace', Admin::controllerNamespace(), $contents)
        );
        $this->line('<info>ExampleController file was created:</info> ' . str_replace(base_path(), '', $exampleController));
    }

    /**
     * Create AuthController.
     *
     * @return void
     */
    public function createAuthController()
    {
        $authController = $this->directory . '/Controllers/AuthController.php';
        $contents       = $this->getStub('AuthController');

        $this->laravel['files']->put(
            $authController,
            str_replace('DummyNamespace', Admin::controllerNamespace(), $contents)
        );
        $this->line('<info>AuthController file was created:</info> ' . str_replace(base_path(), '', $authController));
    }

    /**
     * Create AdministratorController.
     *
     * @return void
     */
    public function createAdministratorController()
    {
        $controller = $this->directory . '/Controllers/AdministratorController.php';
        $contents   = $this->getStub('AdministratorController');

        $this->laravel['files']->put(
            $controller,
            str_replace('DummyNamespace', Admin::controllerNamespace(), $contents)
        );
        $this->line(
            '<info>AdministratorController file was created:</info> ' . str_replace(base_path(), '', $controller)
        );
    }

    /**
     * Create menu file.
     *
     * @return void
     */
    protected function createMenuFile()
    {
        $file = $this->directory . '/menu.php';

        $contents = $this->getStub('menu');
        $this->laravel['files']->put($file, $contents);
        $this->line('<info>Menu file was created:</info> ' . str_replace(base_path(), '', $file));
    }

    /**
     * Create routes file.
     *
     * @return void
     */
    protected function createBootstrapFile()
    {
        $file = $this->directory . '/bootstrap.php';

        $contents = $this->getStub('bootstrap');
        $this->laravel['files']->put($file, $contents);
        $this->line('<info>Bootstrap file was created:</info> ' . str_replace(base_path(), '', $file));
    }

    /**
     * Create routes file.
     *
     * @return void
     */
    protected function createRoutesFile()
    {
        $file = $this->directory . '/routes.php';

        $contents = $this->getStub('routes');
        $this->laravel['files']->put($file, str_replace('DummyNamespace', Admin::controllerNamespace(), $contents));
        $this->line('<info>Routes file was created:</info> ' . str_replace(base_path(), '', $file));
    }

    /**
     * Copy language files to admin directory.
     *
     * @return void
     */
    protected function copyLanguageFiles()
    {
        $this->laravel['files']->copyDirectory(__DIR__ . '/../../lang/', "{$this->directory}/lang/");
    }

    /**
     * Get stub contents.
     *
     * @param $name
     *
     * @return string
     */
    protected function getStub($name)
    {
        return $this->laravel['files']->get(__DIR__ . "/stubs/admin/$name.stub");
    }

    /**
     * Make new directory.
     *
     * @param string $path
     */
    protected function makeDir($path = '')
    {
        $this->laravel['files']->makeDirectory("{$this->directory}/$path", 0755, true, true);
    }
}
