<?php
namespace Kizi\Settings\Commands\Installers\Scripts;

use Illuminate\Console\Command;
use Kizi\Settings\Commands\Installers\SetupScript;

class ThemeAssets implements SetupScript
{
    /**
     * Fire the install script
     * @param  Command $command
     * @return mixed
     */
    public function fire(Command $command)
    {
        if ($command->option('verbose')) {
            $command->blockMessage('Themes', 'Publishing theme assets ...', 'comment');
        }

        if ($command->option('verbose')) {
            $command->call('stylist:publish');

            return;
        }
        $command->callSilent('stylist:publish');
    }
}
