<?php

$bot->command('/\/start/', 'CommandController::start');
$bot->command('/[\/!]lang/', 'CommandController::lang');
$bot->command('{default}', 'CommandController::onCommand');
$bot->hear('{default}', 'MessageController::onMessage');
$bot->callback('{default}', 'CallbackController::onCallback');
$bot->callback('{default}', 'CallbackController::onCallback');

$bot->onMaxSystemLoad(function ($load) use ($bot) {
  die($bot->say($bot->l('ON_MAX_SYSTEM_LOAD')));
  $bot->stop();
});

$bot->onSpam(function ($time) use ($bot) {
  $bot->say($bot->l('ON_SPAM', ['time' => $time]));
  $bot->stop();
});

$bot->onBanned(function ($comment, $end) use ($bot) {
  $bot->say($bot->l('ON_BANNED', ['comment' => $comment, 'time' => date("d.m.Y H:i:s", $end)]));
  $bot->stop();
});

$bot->onNewVersion(function ($version) use ($bot) {
  $bot->say($bot->l('ON_NEW_VERSION', ['version' => $version]));
  $bot->stop();
});
