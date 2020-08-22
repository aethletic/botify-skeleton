<?php

use Botify\Bot;

require __DIR__ . '/../vendor/autoload.php';

// Get config data
$config = require __DIR__ . '/../config/bot.php';

// Init bot
$bot = new Bot($config['bot.token'], $config);

// Register bot application
$bot->util->bootstrap([
  __DIR__ . '/helpers/*.php',
  __DIR__ . '/models/*.php',
  __DIR__ . '/controllers/*.php',
]);

// Register localization files
foreach (glob(__DIR__ . '/localizations/*.php') as $loc_file) {
  $bot->loc->add(require $loc_file);
}

// Add keyboards, replics, emojis
$bot->addKeyboards(require __DIR__ . '/registers/keyboards.php');
$bot->addReplics(require __DIR__ . '/registers/replics.php');
$bot->addEmojis(require __DIR__ . '/registers/emojis.php');

// Bot main handler
require __DIR__ . '/handler.php';

// Register bot modules
foreach (glob(__DIR__ . '/modules/*/module.php') as $loc_file) {
  $bot->loc->add(require $loc_file);
}
