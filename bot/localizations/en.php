<?php

return [
  'en' => [
    'ON_MAX_SYSTEM_LOAD' => 'At the moment your message cannot be processed.',
    'ON_BANNED' =>  "<b>You are blocked.</b>\n" .
                    "<b>Reason:</b> {comment}\n" .
                    "<b>Expires:</b> {time}",
    'ON_SPAM' => 'Too fast, wait another {time} sec.',
    'ON_NEW_VERSION' => 'Bot firmware updated to v{version} 🥳',
    'DEFAULT_MESSAGE' => 'Sorry, I didn\'t understand you.',
    'DEFAULT_COMMAND' => 'There is no such command.',
    'DEFAULT_CALLBACK' => 'There is no action assigned to this button.',
    'COMMAND_START' => 'I\'m alive!',
    'COMMAND_LANG_SUCCESS' => 'Language changed successfully!',
    'COMMAND_LANG_FAIL' =>  "This language does not exist.\n\n" .
                            "<b>List of available languages:</b>\n" .
                            "{langs}",
  ]
];
