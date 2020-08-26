<?php

use Aethletic\App\Container as App;

// Главная
App::route()->get('/', 'IndexController::index');
App::route()->get('/dashboard', 'IndexController::index');

// Пользователи
App::route()->get('/users', 'UsersController::index');
App::route()->post('/users', 'UsersController::more');

// Сообщения
App::route()->get('/messages', 'MessagesController::index');
App::route()->post('/messages', 'MessagesController::more');

// Реклама
App::route()->get('/advertiser', 'AdvertiserController::index');

// Настройки
App::route()->get('/settings', 'SettingsController::index');

// API
App::route()->get('/api/v1/users/getById/{user_id}', 'UsersController::getById');
App::route()->post('/api/v1/users/update', 'UsersController::update');
App::route()->get('/api/v1/users/refresh/{user_id}', 'UsersController::refresh');

// Авторизация
App::route()->get('/auth/admin/{code}', 'AuthController::admin');
App::route()->post('/auth/create', 'AuthController::create');
App::route()->get('/auth/{code}', 'AuthController::view');
App::route()->get('/login', 'AuthController::login');

// 404
App::route()->set404(function() {
    header('HTTP/1.1 404 Not Found');
    echo App::twig()->render('pages/404.html');
});
