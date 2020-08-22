<?php

use Aethletic\App\Container as App;

class Users
{
    public static function getCountUsers()
    {
      return App::db()->table('users')->count();
    }

    public static function getLastUserData($count = 10, $page = 0, $filter = false)
    {
      $page = (int) $page;
      $offset = $page * $count;

      if (is_array($filter)) {
        $filter = array_map(function ($value) {
          return escape($value);
        }, $filter);

        $sql_q = "WHERE full_name LIKE '%%'";
        if (trim($filter['q']) !== '') {
          $sql_q = "WHERE full_name LIKE '%{$filter['q']}%'";
        }

        $sql_date = '';
        if (trim($filter['date']) !== '') {
          $timestamp = strtotime($filter['date']);
          $endOfDay = endOfDay($timestamp);
          $midnight = midnight($timestamp);
          $sql_date = "AND first_message >= {$midnight} AND first_message <= {$endOfDay}";
        }

        $sql_user = '';
        if (trim($filter['user']) !== '') {
          $sql_user = "AND user_id = '{$filter['user']}'";
        }

        $res = App::db()->fetchAll("SELECT * FROM (SELECT * FROM users $sql_q $sql_date $sql_user ORDER BY id DESC LIMIT {$offset}, {$count}) sub ORDER BY id DESC");

      } else {
        $sql_user = '';
        if (!empty($_GET['user_id'])) {
          $user_id = (int) escape($_GET['user_id']);
          $sql_user = "WHERE user_id = {$user_id}";
        }

        $res = App::db()->fetchAll("SELECT * FROM (SELECT * FROM users $sql_user ORDER BY id DESC LIMIT {$offset}, {$count}) sub ORDER BY id DESC");
      }

      return $res;
    }

    public static function getCountNewUsersToday()
    {
        $rs = App::db()->table('stats_new_users')->where('date', midnight())->get();

        if (sizeof($rs) > 0) {
            return $rs[0]['count'];
        } else {
            return 0;
        }
    }

    public static function getLast($count = 10)
    {
      return App::db()->fetchAll("SELECT * FROM (SELECT * FROM stats_new_users ORDER BY id DESC LIMIT {$count}) sub ORDER BY id ASC");
    }

    public static function getMax()
    {
      return App::db()->table('stats_new_users')->max('count');
    }

    public static function getMin()
    {
      return App::db()->table('stats_new_users')->min('count');
    }

    public static function getAvg()
    {
      return App::db()->table('stats_new_users')->avg('count');
    }

    public static function getById($user_id)
    {
      $res = App::db()->table('users')->where('user_id', $user_id)->get();
      return sizeof($res) == 0 ? false: $res[0];
    }

    public static function update($update)
    {
      if (array_key_exists('id', $update)) {
        unset($update['id']);
      }

      $update = array_map(function ($v) {
        if (trim($v) == '' || is_bool($v)) {
          $v = null;
        } else {
          escape($v);
        }
        return $v;
      }, $update);

      return App::db()->table('users')->where('user_id', $update['user_id'])->update($update);
    }

    public static function getCountActives()
    {
      return App::db()->table('users')->where('is_active', 1)->count();
    }
}
