<?php

class CommandController
{
  public static function onCommand($bot)
  {
    $bot->reply($bot->l('DEFAULT_COMMAND'));
  }
  
  public static function start($bot)
  {
    if (sizeof($cmd = $bot->parse()) == 2 && $bot->user->isNewUser) {
      $bot->user->update(['from_source' => $cmd[1]]);
    }

    return $bot->say($bot->l('COMMAND_START'));
  }

  public static function lang($bot)
  {
    $cmd = array_filter(array_map('trim', $bot->parse()));
    $lang = mb_strtolower(@$cmd[1]);

    if (!$bot->loc->exists($lang)) {
      $langs = $bot->loc->getAvailableLangs();
      $langs = array_map(function ($v) {return "🔹 {$v}";}, $langs);
      $langs = implode("\n", $langs);

      return $bot->reply($bot->loc->get('COMMAND_LANG_FAIL', ['langs' => $langs]));
    }

    $bot->loc->setLang($lang);
    $bot->user->update(['lang' => $lang]);

    return $bot->say($bot->l('COMMAND_LANG_SUCCESS'));
  }
}
