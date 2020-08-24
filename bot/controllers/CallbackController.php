<?php

class CallbackController
{
  public static function onCallback($bot)
  {
    $bot->notify($bot->l('DEFAULT_CALLBACK'), true);
  }
}
