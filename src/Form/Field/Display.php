<?php

namespace Kizi\Settings\Form\Field;

use Closure;
use Kizi\Settings\Form\Field;

class Display extends Field
{
    protected $callback;

    /**
     * @deprecated
     *
     * @param Closure $callback
     */
    public function format(Closure $callback)
    {
        $this->with($callback);
    }

    public function with(Closure $callback)
    {
        $this->callback = $callback;
    }

    public function render()
    {
        if ($this->callback instanceof Closure) {
            $callback = $this->callback->bindTo($this->form->model());

            $this->value = call_user_func($callback, $this->value);
        }

        return parent::render();
    }
}
