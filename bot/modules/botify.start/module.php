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
    $bot->loc->add(require __DIR__ . '/localization/ru.php');

    $cmd          = $bot->parse();
    $unknown      = $bot->loc->get('BOTIFY_START_UNKNOWN');
    $from         = sizeof($cmd) == 2 ? "#{$cmd[1]}" : "#{$unknown}";
    $chat         = $bot->config['botify.start']['chat'];
    $bot_username = $bot->config['bot.username'];
    $bot_username = stripos($bot_username, '@') !== false ? $bot_username : "@{$bot_username}";
    $count_users  = $bot->db->table('users')->count();
    $msg = $bot->loc->get('BOTIFY_START_NOTIFY_TEXT', [
      'user_id'       => $bot->user_id,
      'full_name'     => $bot->full_name,
      'from'          => $from,
      'bot_username'  => $bot_username,
      'count_users'  => $count_users,
    ]);

    $inline = [
      [
        ['text' => $bot->loc->get('BOTIFY_START_MANAGE'), 'url' => "{$bot->config['bot.web']}users/?user_id={$bot->user_id}"],
        ['text' => $bot->loc->get('BOTIFY_START_MESSAGES'), 'url' => "{$bot->config['bot.web']}messages/?user_id={$bot->user_id}"]
      ],
    ];

    $bot->sendMessage($chat, $msg, $bot->keyboard->inline($inline));

    if (!empty($bot->config['botify.start']['add_from_to_database']) && sizeof($cmd) == 2) {
      $bot->user->update(['from_source' => $from]);
    }
  }
});
