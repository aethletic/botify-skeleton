<?php

return [
  'ru' => [
    'BOTIFY_START_UNKNOWN' => 'неизвестно',
    'BOTIFY_START_MANAGE' => '⚙ Управление',
    'BOTIFY_START_MESSAGES' => '💬 Сообщения',
    'BOTIFY_START_NOTIFY_TEXT' => "<b>Новый пользователь! 🎉</b>\n" .
                                  "<b>Имя:</b> <a href=\"tg://user?id={user_id}\">{full_name}</a>\n" .
                                  "<b>Откуда:</b> {from}\n" .
                                  "<b>Бот:</b> {bot_username}\n" .
                                  "<b>Всего пользователей:</b> {count_users}",
  ]
];
