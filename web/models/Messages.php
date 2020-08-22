<?php

use Aethletic\App\Container as App;

class Messages
{
    public static function getCountMessagesToday()
    {
      $rs = App::db()->table('stats_messages')->where('date', midnight())->get();

      if (sizeof($rs) > 0) {
          return $rs[0]['count'];
      } else {
          return 0;
      }
    }

    public static function getCountAll()
    {
      return App::db()->table('stats_messages')->sum('count');
    }

    public static function getLast($count = 10)
    {
      return App::db()->fetchAll("SELECT * FROM (SELECT * FROM stats_messages ORDER BY id DESC LIMIT {$count}) sub ORDER BY id ASC");
    }

    public static function getMax()
    {
      return App::db()->table('stats_messages')->max('count');
    }

    public static function getMin()
    {
      return App::db()->table('stats_messages')->min('count');
    }

    public static function getAvg()
    {
      return App::db()->table('stats_messages')->avg('count');
    }

    public static function getLastMessageData($count = 10, $page = 0, $filter = false)
    {
      $page = (int) $page;
      $offset = $page * $count;

      if (is_array($filter)) {
        $filter = array_map(function ($value) {
          return escape($value);
        }, $filter);

        $sql_q = "WHERE value LIKE '%%'";
        if (trim($filter['q']) !== '') {
          $sql_q = "WHERE value LIKE '%{$filter['q']}%'";
        }

        $sql_date = '';
        if (trim($filter['date']) !== '') {
          $timestamp = strtotime($filter['date']);
          $endOfDay = endOfDay($timestamp);
          $midnight = midnight($timestamp);
          $sql_date = "AND date >= {$midnight} AND date <= {$endOfDay}";
        }

        $sql_user = '';
        if (trim($filter['user']) !== '') {
          $sql_user = "AND user_id = '{$filter['user']}'";
        }

        $res = App::db()->fetchAll("SELECT * FROM  (SELECT * FROM messages $sql_q $sql_date $sql_user ORDER BY id DESC LIMIT {$offset}, {$count}) sub ORDER BY id DESC");
      } else {
        $sql_user = '';
        if (!empty($_GET['user_id'])) {
          $user_id = (int) escape($_GET['user_id']);
          $sql_user = "WHERE user_id = {$user_id}";
        }

        $res = App::db()->fetchAll("SELECT * FROM (SELECT * FROM messages $sql_user ORDER BY id DESC LIMIT {$offset}, {$count}) sub ORDER BY id DESC");
      }

      return array_map(function ($value) use ($page){
          $value['print'] = json_encode(json_decode($value['value'], true), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
          $value['value'] = json_decode($value['value'], true);

          if ($page == 0) {
            if (array_key_exists('message', $value['value'])) {
              if (array_key_exists('text', $value['value']['message'])) {
                $value['text'] = $value['value']['message']['text'];
                $value['text'] = mb_strimwidth($value['text'], 0, 120, "...");
              } else {
                $value['text'] = '💀 Нет текста';
              }
            } else {
              $value['text'] = '💀 Нет текста';
            }
          }

          return $value;
      }, $res);
    }
}
