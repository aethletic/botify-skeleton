<?php

use Aethletic\App\Container as App;

class Auth
{
    public static function isAdmin($die = false, $msg = 'Нет прав для этого действия.')
    {
      if (!isset($_SESSION['admin_code'])) {
        if ($die) {
          die(json_encode([
            'ok' => false,
            'title' => 'Ошибка',
            'message' => $msg,
          ]));
        }

        return false;
      }

      $data = App::memcache()->get($_SESSION['admin_code']);

      if ($data !== true) {
        if ($die) {
          die(json_encode([
            'ok' => false,
            'title' => 'Ошибка',
            'message' => 'Нет прав для этого действия.',
          ]));
        }
      }

      return $data == true;
    }

    public static function canView($die = false, $msg = 'Нет прав для просмотра.')
    {
      if (!isset($_SESSION['view_code'])) {
        if ($die) {
          die(json_encode([
            'ok' => false,
            'title' => 'Ошибка',
            'message' => $msg,
          ]));
        }

        return false;
      }

      $data = App::memcache()->get($_SESSION['view_code']);

      if ($data !== true) {
        if ($die) {
          die(json_encode([
            'ok' => false,
            'title' => 'Ошибка',
            'message' => 'Нет прав для этого действия.',
          ]));
        }
      }

      return $data == true;
    }
}
