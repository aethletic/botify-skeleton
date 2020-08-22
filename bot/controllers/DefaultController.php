<?php

class DefaultController
{
  public static function onMessage($bot)
  {
    $bot->reply($bot->l('DEFAULT_MESSAGE'));
  }

  public static function onCommand($bot)
  {
    $bot->reply($bot->l('DEFAULT_COMMAND'));
  }

  public static function onCallback($bot)
  {
    $bot->notify($bot->l('DEFAULT_CALLBACK'), true);
  }
}
