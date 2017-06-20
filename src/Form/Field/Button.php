<?php

namespace Kizi\Settings\Form\Field;

use Kizi\Settings\Form\Field;

class Button extends Field
{
    protected $class = 'btn-primary';

    public function info()
    {
        $this->class = 'btn-info';

        return $this;
    }

    public function on($event, $callback)
    {
        $this->script = <<<EOT

        $('{$this->getElementClassSelector()}').on('$event', function() {
            $callback
        });

EOT;
    }
}
