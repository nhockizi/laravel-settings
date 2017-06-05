<?php

namespace Kizi\Settings\Process;

use Kizi\Settings\Contracts\RunableInterface;
use Kizi\Settings\Repository;

class Runner implements RunableInterface
{
    /**
     * The module instance.
     *
     * @var \Kizi\Settings\Repository
     */
    protected $module;

    /**
     * The constructor.
     *
     * @param \Kizi\Settings\Repository $module
     */
    public function __construct(Repository $module)
    {
        $this->module = $module;
    }

    /**
     * Run the given command.
     *
     * @param string $command
     */
    public function run($command)
    {
        passthru($command);
    }
}
