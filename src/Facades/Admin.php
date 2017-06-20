<?php

namespace Kizi\Settings\Facades;

use Illuminate\Support\Facades\Facade;

class Admin extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Kizi\Settings\Admin::class;
    }
}
