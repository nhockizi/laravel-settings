<?php

namespace Kizi\Settings\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\App;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public $locale;

    public function __construct()
    {
        \View::addLocation(base_path() . '/Themes/bootstrap/views/');
        $this->locale = App::getLocale();
    }

}
