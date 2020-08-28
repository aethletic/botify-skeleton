<?php

use Aethletic\App\Container as App;

class UsersController
{
    public static function index()
    {
        $users = Users::getLast();
        $userData = [];
        foreach ($users as $key => $value) {
            $userData['date'][] = date("d.m.Y", $value['date']);
            $userData['count'][] = $value['count'];
        }

        echo App::twig()->render('pages/users.html', [
            'users_data' => Users::getLastUserData(10),
            'users_count' => number_format(Users::getCountUsers(), 0, ',', ' '),
            'users_count_today' => number_format(Users::getCountNewUsersToday(), 0, ',', ' '),
            'users_max' => number_format(Users::getMax(), 0, ',', ' '),
            'users_min' => number_format(Users::getMin(), 0, ',', ' '),
            'users_avg' => number_format(round(Users::getAvg()), 0, ',', ' '),
            'messages_count' => number_format(Messages::getCountAll(), 0, ',', ' '),
            'users_data_dates' => !empty($userData['date']) ? json_encode($userData['date'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_QUOT) : '',
            'users_data_vars' => !empty($userData['count']) ? json_encode($userData['count'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_QUOT) : '',
        ]);
    }

    public static function more()
    {
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $filter = json_decode(file_get_contents('php://input'), true);
        $page = $filter['page'];
      } else {
        $page = 0;
        $filter = false;
      }

      $data = Users::getLastUserData(20, $page, $filter);

      $response = [];

      if (sizeof($data) == 0) {
        $response['nextPage'] = false;
      } else {
        $response['nextPage'] = true;
      }

      $response['content'] = App::twig()->render(
        'blocks/users_content.html',
        [
          'users_data' => $data,
          'is_more' => $filter['is_more'],
        ]
      );

      die(json_encode($response));
    }

    public static function getById($user_id)
    {
      Auth::isAdmin(true, 'Нет прав для управления пользователем');

      $data = Users::getById($user_id);

      $response = [];

      $response['content'] = App::twig()->render(
        'blocks/user_modal.html',
        [
          'user_data' => $data,
        ]
      );

      die(json_encode($response));
    }

    public static function update()
    {
      Auth::isAdmin(true);

      $data = json_decode(file_get_contents('php://input'), true);

      if (Users::update($data)) {
        $response = [
          'ok' => true,
          'title' => 'Успешно',
          'message' => 'Данные пользователя обновлены'
        ];
      } else {
        $response = [
          'ok' => false,
          'title' => 'Ошибка',
          'message' => 'Произошла непредвиденная ошибка'
        ];
      }

      die(json_encode($response));
    }

    public static function refresh($user_id)
    {
      Auth::isAdmin(true, 'Нет прав для обновления данных');

      $bot = App::bot();
      $update = ['is_active' => (int) $bot->isActive($user_id)];
      $bot->db->table('users')->where('user_id', $user_id)->update($update);

      $response['ok'] = true;
      $response['content'] = App::twig()->render(
        'blocks/user_card.html',
        [
          'user' => Users::getById($user_id),
        ]
      );

      die(json_encode($response));
    }
}
