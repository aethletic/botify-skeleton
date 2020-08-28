<?php

use Aethletic\App\Container as App;

class MessagesController
{
    public static function index()
    {
        $messages = Messages::getLast(10);
        $messageData = [];
        foreach ($messages as $key => $value) {
            $messageData['date'][] = date("d.m.Y", $value['date']);
            $messageData['count'][] = $value['count'];
        }

        echo App::twig()->render('pages/messages.html', [
            'messages_data' => Messages::getLastMessageData(20),
            'messages_count_today' => number_format(Messages::getCountMessagesToday(), 0, ',', ' '),
            'messages_count' => number_format(Messages::getCountAll(), 0, ',', ' '),
            'messages_max' => number_format(Messages::getMax(), 0, ',', ' '),
            'messages_min' => number_format(Messages::getMin(), 0, ',', ' '),
            'messages_avg' => number_format(round(Messages::getAvg()), 0, ',', ' '),
            'users_count' => number_format(Users::getCountUsers(), 0, ',', ' '),
            'messages_data_dates' => !empty($messageData['date']) ? json_encode($messageData['date'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_QUOT) : '',
            'messages_data_vars' => !empty($messageData['count']) ? json_encode($messageData['count'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_QUOT) : '',
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

      $data = Messages::getLastMessageData(20, $page, $filter);

      $response = [];

      if (sizeof($data) == 0) {
        $response['nextPage'] = false;
      } else {
        $response['nextPage'] = true;
      }

      $response['content'] = App::twig()->render(
        'blocks/messages_content.html',
        [
          'messages_data' => $data,
          'is_more' => $filter['is_more'],
        ]
      );

      die(json_encode($response));
    }
}
