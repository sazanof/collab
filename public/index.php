<?php

const INC_MODE = true;

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once '../vendor/autoload.php';
use CLB\Core\Application;
use CLB\Core\Router\MainRouter;
// TODO add referer to route param to make work route only if referer is set & matches
// TODO move routes to routes.php or yaml
$router = new MainRouter();
$router->setRoute('/locales/{lang}', ['GET'], [CLB\Core\Controllers\LocaleController::class, 'getTranslation'])->addDefaults([
    'public' => true
]);
$router->setRoute('/locales', ['GET'], [CLB\Core\Controllers\LocaleController::class, 'getLocaleList'])->addDefaults([
    'public' => true
]);
$router->setRoute('/install/{step}', ['GET', 'POST'], [CLB\Core\Controllers\InstallController::class, 'install'])->addDefaults([
    'step' => 0,
    'public' => true
]);

$router->setRoute('/', ['GET'], [CLB\Core\Controllers\AppController::class, 'index']);
$router->setRoute('/login/{id}', ['POST'], [CLB\Core\Controllers\LoginController::class, 'processLogin']);
$router->setRoute('/login', ['GET'], [CLB\Core\Controllers\LoginController::class, 'getLogin']);

$application = new Application($router);
return $application->watch();

