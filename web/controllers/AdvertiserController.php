<?php

use Aethletic\App\Container as App;

class AdvertiserController
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

        $count_active_users = Users::getCountActives();
        $users_can_read = round(0.90 * $count_active_users);
        $price_mailing_ad = ceil(0.10 * $count_active_users);
        $price_native_ad = ceil(0.10 * Users::getCountUsers());

        echo App::twig()->render('pages/advertiser.html', [
            'users_count' => number_format(Users::getCountUsers(), 0, ',', ' '),
            'users_count_today' => number_format(Users::getCountNewUsersToday(), 0, ',', ' '),
            'messages_count_today' => number_format(Messages::getCountMessagesToday(), 0, ',', ' '),
            'messages_count' => number_format(Messages::getCountAll(), 0, ',', ' '),
            'messages_max' => number_format(Messages::getMax(), 0, ',', ' '),
            'messages_min' => number_format(Messages::getMin(), 0, ',', ' '),
            'messages_avg' => number_format(round(Messages::getAvg()), 0, ',', ' '),
            'users_max' => number_format(Users::getMax(), 0, ',', ' '),
            'users_min' => number_format(Users::getMin(), 0, ',', ' '),
            'users_avg' => number_format(round(Users::getAvg()), 0, ',', ' '),
            'messages_data_dates' => json_encode($messageData['date'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_QUOT),
            'messages_data_vars' => json_encode($messageData['count'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_QUOT),
            'users_data_dates' => json_encode($userData['date'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_QUOT),
            'users_data_vars' => json_encode($userData['count'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_QUOT),
            'count_active_users' => number_format($count_active_users, 0, ',', ' '),
            'native_ad_alltime' => number_format(0.90 * round(Messages::getCountAll()), 0, ',', ' '),
            'native_ad_today' => number_format(0.90 * round(Messages::getCountMessagesToday()), 0, ',', ' '),
            'native_ad_day' => number_format(0.90 * round(Messages::getAvg()), 0, ',', ' '),
            'users_can_read' => number_format($users_can_read, 0, ',', ' '),
            'users_link_click' => number_format(round(0.80 * $users_can_read), 0, ',', ' '),
            'price_mailing_ad' => number_format($price_mailing_ad, 0, ',', ' '),
            'price_native_ad' => number_format($price_native_ad, 0, ',', ' '),
        ]);
    }
}
