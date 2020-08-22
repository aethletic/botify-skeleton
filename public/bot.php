<?php

require __DIR__ . '/../bot/bootstrap.php';

ini_set('display_errors', $bot->config['bot.debug']);
date_default_timezone_set($bot->config['bot.timezone']);

$bot->run();
