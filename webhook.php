<?php

use Botify\Bot;

require __DIR__ . '/vendor/autoload.php';

$logo = "
 _                _    ___
| |           _  (_)  / __)
| |__   ___ _| |_ _ _| |__ _   _
|  _ \ / _ (_   _) (_   __) | | |
| |_) ) |_| || |_| | | |  | |_| |_
|____/ \___/  \__)_| |_|   \__  (_)
                          (____/

";

$config = include __DIR__ . '/config/bot.php';
$bot = new Bot($config['bot.token'], $config);

$help = "{$logo}Available arguments:" . PHP_EOL .
        "[set]    - for set webhook;" . PHP_EOL .
        "[delete] - for delete webhook;" . PHP_EOL .
        "[info]   - return information about the current status of a webhook;" . PHP_EOL .
        "[help]   - list of available arguments;" . PHP_EOL . PHP_EOL;

if (!isset($argv[1])) {
  echo $help;
  return;
}

$cmd = mb_strtolower($argv[1]);

switch ($cmd) {
  case 'set':
    $res = $bot->setWebhook();
    break;

  case 'delete':
    $res = $bot->deleteWebhook();
    break;

  case 'info':
    $res = $bot->getWebhookInfo();
    break;

  case 'help':
  case '?':
    $res = $help;
    break;
}

print_r($res);
