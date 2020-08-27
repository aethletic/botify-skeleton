<?php

use Aethletic\App\Container as App;

class SettingsController
{
    public static function index()
    {
        echo App::twig()->render('pages/settings.html', [
          'users_count' => number_format(Users::getCountUsers(), 0, ',', ' '),
          'messages_count' => number_format(Messages::getCountAll(), 0, ',', ' '),
        ]);
    }
}
