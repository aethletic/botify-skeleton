<?php

/**
 * User management via Telegram bot.
 * @package botify.admin
 * @version 1.0.0 (22.08.2020)
 * @author Botify <hello@botify.ru>
 * @link https://botify.ru
 */
$bot->onAdmin(function () use ($bot) {
  $bot->loc->add(require __DIR__ . '/localization/ru.php');

  $bot->addMiddleware('isAdminAlreadyAuth', function () use ($bot) {
    if ($bot->user->state_name == 'admin' && $bot->user->state_data == 1) {
      exit($bot->reply('Вы уже авторизованы.'));
    }
  });

  // Admin auth
  $bot->middleware('isAdminAlreadyAuth')
      ->command(['/\/admin/iu'], function () use ($bot) {
        $bot->user->setState('admin_auth');
        $bot->say('Введите пароль Администратора.');
      });

  $bot->state('admin_auth', '/cancel')
      ->hear($bot->config['admin.password'], function () use ($bot) {
        $bot->user->setState('admin', 1);
        $bot->say('Вы успешно авторизовались!');
      });

  $bot->state('admin_auth', '/cancel')
      ->hear('{default}', function () use ($bot) {
        $bot->say('Неверный пароль.');
      });

  $bot->state('admin_auth')
      ->command(['/\/cancel/iu'], function () use ($bot) {
        $bot->user->clearState();
        $bot->say('Вход отменён.');
      });

  // Admin menu
  $bot->state('admin')
      ->command(['/\/exit/iu'], function () use ($bot) {
        $bot->user->clearState();
        $bot->say('Вы вышли из панели Администратора.');
      });

  $bot->state('admin')
      ->hear(['hello'], function () use ($bot) {
        $bot->say($bot->l('TEST'));
      });
});
