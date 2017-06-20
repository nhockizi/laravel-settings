<?php

namespace Kizi\Settings\Form\Field;

class Password extends Text
{
    public function render()
    {
        $this->prepend('<i class="fa fa-eye-slash"></i>')
            ->defaultAttribute('type', 'password');

        return parent::render();
    }
}
