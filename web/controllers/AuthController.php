<?php

use Aethletic\App\Container as App;

class AuthController
{
    public static function admin($code)
    {
      $app = App::self();

      $data = $app->memcache->get($code);

      if ($data == true) {
        $_SESSION['admin_code'] = $code;
        header("Location: {$app->BASE_PATH}", true, 200);
        die;
      } else {
        header("Location: {$app->BASE_PATH}", true, 301);
        die;
      }
    }

    public static function view($code)
    {
      $app = App::self();

      $data = $app->memcache->get($code);

      if ($data == true) {
        $_SESSION['view_code'] = $code;
        header("Location: {$app->BASE_PATH}", true, 200);
        die;
      } else {
        header("Location: {$app->BASE_PATH}", true, 301);
        die;
      }
    }

    public static function create()
    {
      $data = json_decode(file_get_contents('php://input'), true);

      if (!isset($data['key'])) {
        die(json_encode([
          'ok' => false,
          'title' => 'Ошибка',
          'message' => 'Неверный ключ доступа, повторите попытку'
        ]));
      }

      $cfg = App::getConfig();
      if ($data['key'] !== $cfg['web']['secret.key']) {
        die(json_encode([
          'ok' => false,
          'title' => 'Ошибка',
          'message' => 'Неверный ключ доступа, повторите попытку'
        ]));
      }

      if (isset($data['time'])) {
        $min = (int) $data['time'];
        if ($min == 0) {
          $min = 5;
        }
        // max 6 hours
        if ($min > 360) {
          $min = 360;
        }
      } else {
        $min = 5;
      }

      $time = $min * 60;
      $type = isset($data['type']) ? $data['type'] : 'view';


      $code = generateCode(6);
      App::memcache()->set($code, true, $time);

      if ($type == 'admin') {
        $response = [
          'ok' => true,
          'content' => App::twig()->render('blocks/alert.html', [
            'link' => getScheme() . '://' . $_SERVER['SERVER_NAME'] . App::get('BASE_PATH') . "/auth/admin/{$code}",
            'time' => gmdate("H час. i мин. s сек.", $time),
          ]),
        ];
      } else {
        $response = [
          'ok' => true,
          'content' => App::twig()->render('blocks/alert.html', [
            'link' => getScheme() . '://' . $_SERVER['SERVER_NAME'] . App::get('BASE_PATH') . "/auth/{$code}",
            'time' => gmdate("H час. i мин. s сек.", $time),
          ]),
        ];
      }

      die(json_encode($response));
    }

    public static function login()
    {
      echo App::twig()->render('pages/login.html');
    }
}
