<?php

$bot->command('/\/start/', 'CommandController::start');
$bot->command('/[\/!]lang/', 'CommandController::lang');
$bot->hear('{default}', 'DefaultController::onMessage');
$bot->command('{default}', 'DefaultController::onCommand');
$bot->callback('{default}', 'DefaultController::onCallback');
