<?php

use Aethletic\App\Container as App;

class IndexController
{
    public static function index()
    {
        $messages = Messages::getLast(10);
        $messageData = [];
        foreach ($messages as $key => $value) {
            $messageData['date'][] = date("d.m.Y", $value['date']);
            $messageData['count'][] = $value['count'];
        }

        $users = Users::getLast();
        $userData = [];
        foreach ($users as $key => $value) {
            $userData['date'][] = date("d.m.Y", $value['date']);
            $userData['count'][] = $value['count'];
        }

        echo App::twig()->render('pages/index.html', [
            'users_data' => Users::getLastUserData(4),
            'users_count' => number_format(Users::getCountUsers(), 0, ',', ' '),
            'users_count_today' => number_format(Users::getCountNewUsersToday(), 0, ',', ' '),
            'messages_data' => Messages::getLastMessageData(4),
            'messages_count_today' => number_format(Messages::getCountMessagesToday(), 0, ',', ' '),
            'messages_count' => number_format(Messages::getCountAll(), 0, ',', ' '),
            'messages_max' => number_format(Messages::getMax(), 0, ',', ' '),
            'messages_min' => number_format(Messages::getMin(), 0, ',', ' '),
            'messages_avg' => number_format(round(Messages::getAvg()), 0, ',', ' '),
            'users_max' => number_format(Users::getMax(), 0, ',', ' '),
            'users_min' => number_format(Users::getMin(), 0, ',', ' '),
            'users_avg' => number_format(round(Users::getAvg()), 0, ',', ' '),
            'messages_data_dates' => !empty($messageData['date']) ? json_encode($messageData['date'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_QUOT) : '',
            'messages_data_vars' => !empty($messageData['count']) ? json_encode($messageData['count'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_QUOT) : '',
            'users_data_dates' => !empty($userData['date']) ? json_encode($userData['date'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_QUOT) : '',
            'users_data_vars' => !empty($userData['count']) ? json_encode($userData['count'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_QUOT) : '',
        ]);
    }
}
