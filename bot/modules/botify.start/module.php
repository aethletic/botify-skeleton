<?php

/**
 * Senв message about new user
 * @package botify.start
 * @version 1.0.0 (24.08.2020)
 * @author Botify <hello@botify.ru>
 * @link https://botify.ru
 */
$bot->command('/\/start/', function () use ($bot) {
  if ($bot->user->isNewUser) {
    $cmd          = $bot->parse();
    $from         = sizeof($cmd) == 2 ? "#{$cmd[1]}" : "#{$bot->loc->get('BOTIFY_START_UNKNOW')}";
    $target_id    = $bot->config['botify.start']['target_id'];
    $bot_username = $bot->config['bot.username'];
    $bot_username = stripos($bot_username, '@') ? $bot_username : "@{$bot_username}";

    $msg = $bot->loc->get('BOTIFY_START_NOTIFY_TEXT', [
      'user_id'       => $bot->user_id,
      'full_name'     => $bot->full_name,
      'from'          => $from,
      'bot_username'  => $bot_username,
    ]);

    $inline = [
      [
        ['text' => $bot->loc->get('BOTIFY_START_MANAGE'), 'url' => 'https://botify.ru']
      ],
      [
        ['text' => $bot->loc->get('BOTIFY_START_MESSAGES'), 'url' => 'https://botify.ru']
      ],
    ];

    $bot->sendMessage($target_id, $msg, $bot->keyboard->inline($inline));

    if (!empty($bot->config['botify.start']['add_from_to_database']) && sizeof($cmd) == 2) {
      $bot->user->update(['from_source' => $from]);
    }
  }
});
