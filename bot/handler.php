<?php

$bot->command('/\/start/', 'CommandController::start');
$bot->command('/[\/!]lang/', 'CommandController::lang');
$bot->command('{default}', 'CommandController::onCommand');
$bot->hear('{default}', 'MessageController::onMessage');
$bot->callback('{default}', 'CallbackController::onCallback');
