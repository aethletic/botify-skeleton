<?php

use Aethletic\App\Container as App;

class SettingsController
{
    public static function index()
    {
        echo App::twig()->render('pages/settings.html', []);
    }
}
