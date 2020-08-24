<?php

class MessageController
{
  public static function onMessage($bot)
  {
    $bot->reply($bot->l('DEFAULT_MESSAGE'));
  }
}
