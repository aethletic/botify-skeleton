<?php

use Aethletic\App\Container as App;

require __DIR__ . '/../web/bootstrap.php';
require __DIR__ . '/../web/routes.php';

$app = App::self();

$debug = (bool) $app->config['web']['debug'];
error_reporting($debug ? E_ALL : 0);
ini_set('display_errors', $debug);

date_default_timezone_set($app->config['bot']['bot.timezone']);

$app->route()->run();
