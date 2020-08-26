<?php

require __DIR__ . '/../vendor/autoload.php';

use Aethletic\App\Container as App;
use Aethletic\App\Bootstrap;
use Botify\Bot;

session_start();

$app = App::self();

// autoload
Bootstrap::autoload([
  __DIR__ . '/controllers/*.php',
  __DIR__ . '/models/*.php',
  __DIR__ . '/helpers/*.php',
]);

// config
$app->set('config', true, function () {
  $config['bot'] = require __DIR__ . '/../config/bot.php';
  $config['web'] = require __DIR__ . '/../config/web.php';
  return $config;
});

$app->set('getConfig', true, function () use ($app) {
  return $app->config;
});

// route
$app->set('route', true, function () {
  return new \Bramus\Router\Router();
});

// databse
$app->set('db', true, function () use ($app) {
  $factory = new \Database\Connectors\ConnectionFactory();
  return $factory->make(array(
    'driver'    => $app->config['bot']['database.driver'],
    'path'      => $app->config['bot']['database.path'],
    'host'      => $app->config['bot']['database.host'],
    'database'  => $app->config['bot']['database.database'],
    'username'  => $app->config['bot']['database.username'],
    'password'  => $app->config['bot']['database.password'],
    'charset'   => $app->config['bot']['database.charset'],
    'collation' => $app->config['bot']['database.collation'],
    'lazy'      => $app->config['bot']['database.lazy'],
  ));
});

// cache
$cache_driver = $app->config['bot']['cache.driver'];

if ($cache_driver == 'memcached') {
  $app->set('memcache', true, function () use ($app) {
    $mem = new \Memcached();
    $mem->addServer($app->config['bot']['cache.host'], $app->config['bot']['cache.port']);
    return $mem;
  });
}

if ($cache_driver == 'redis') {
  $app->set('redis', true, function () use ($app) {
    $redis = new \Redis;
    $redis->connect($app->config['bot']['cache.host'], $app->config['bot']['cache.port']);
    return $redis;
  });
}

// template
$app->set('twig', true, function () {
  $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/views');
  $twig = new \Twig\Environment($loader, [
    'cache' => false,
    'auto_reload' => true,
    'debug' => true,
  ]);

  // sys info
  $twig->addGlobal('BASE_PATH', str_ireplace('/index.php', '', $_SERVER['DOCUMENT_URI']));
  $twig->addGlobal('ADMIN_CODE', isset($_SESSION['admin_code']) ? $_SESSION['admin_code'] : null);
  $twig->addGlobal('VIEW_CODE', isset($_SESSION['view_code']) ? $_SESSION['view_code'] : null);
  $twig->addGlobal('IS_ADMIN', Auth::isAdmin());
  $twig->addGlobal('CAN_VIEW', Auth::canView());

  // themes
  $cfg = App::getConfig();
  $twig->addGlobal('LOGO_TEXT', $cfg['web']['header.logo.text']);
  $twig->addGlobal('COLOR_WHITE', $cfg['web']['color.white']);
  $twig->addGlobal('COLOR_PRIMARY', $cfg['web']['color.primary']);
  $twig->addGlobal('COLOR_PRIMARY_DARK', $cfg['web']['color.primary.dark']);
  $twig->addGlobal('COLOR_PRIMARY_LIGHT', $cfg['web']['color.primary.light']);
  $twig->addGlobal('COLOR_BACKGROUND', $cfg['web']['color.background']);
  $twig->addGlobal('COLOR_NAVBAR', $cfg['web']['color.navbar']);
  $twig->addGlobal('COLOR_LOGO_BACKGROUND', $cfg['web']['color.logo.background']);
  $twig->addGlobal('COLOR_LOGO_TEXT', $cfg['web']['color.logo.text']);
  $twig->addGlobal('COLOR_PRIMARY_TEXT', $cfg['web']['color.primary.text']);
  $twig->addGlobal('COLOR_PRIMARY_LIGHT_TEXT', $cfg['web']['color.primary.light.text']);
  $twig->addGlobal('COLOR_BODY_TEXT', $cfg['web']['color.body.text']);
  $twig->addGlobal('COLOR_TEXT_LIGHT_GRAY', $cfg['web']['color.text.light.gray']);
  $twig->addGlobal('COLOR_SCROLL_BAR', $cfg['web']['color.scroll.bar']);
  $twig->addGlobal('COLOR_SCROLL_BAR_BACKGROUND', $cfg['web']['color.scroll.bar.background']);
  $twig->addGlobal('COLOR_PRE_PRINT', $cfg['web']['color.pre.print']);
  $twig->addGlobal('COLOR_LEFT_MENU_ITEM', $cfg['web']['color.left.menu.item']);
  $twig->addGlobal('COLOR_CARD_BACKGROUND', $cfg['web']['color.card.background']);
  $twig->addGlobal('COLOR_CARD_TEXT', $cfg['web']['color.card.text']);

  $twig->addGlobal('BOT_USERNAME', $cfg['bot']['bot.username']);
  $twig->addGlobal('AD_CONTACT', $cfg['web']['ad.contact']);

  $twig->addFilter(new \Twig\TwigFilter('hide_value', function ($string) {
    return hidePartOfString($string);
  }));

  return $twig;
});

$app->set('bot', true, function () use ($app) {
  return new Bot($app->config['bot']['bot.token'], $app->config['bot']);
});

// vars
$app->set('BASE_PATH', str_ireplace('/index.php', '', $_SERVER['DOCUMENT_URI']));
