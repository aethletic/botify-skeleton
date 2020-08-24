<?php

return [
  'ru' => [
    'BOTIFY_START_UNKNOWN' => 'неизсвестно',
    'BOTIFY_START_MANAGE' => 'Управление',
    'BOTIFY_START_MESSAGES' => 'Сообщения',
    'BOTIFY_START_NOTIFY_TEXT' => "<b>Новый пользователь!</b>\n" .
                                  "<a href=\"tg://user?id={user_id}\">{full_name}</a>\n" .
                                  "Откуда: {from}\n" .
                                  "Бот: {bot_username}";
  ]
];
