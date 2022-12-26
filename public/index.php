<?php

const INC_MODE = true;

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once '../vendor/autoload.php';
require_once '../inc/helpers.php';

use CLB\Core\Application;
use CLB\Core\Router\MainRouter;

$router = new MainRouter();
$router->setRoute('/locales/{lang}', 'GET', [CLB\Core\Controllers\LocaleController::class, 'getTranslation'])->addDefaults([
    'public' => true
]);
$router->setRoute('/install', 'GET', [CLB\Core\Controllers\InstallController::class, 'install'])->addDefaults([
    'public' => true
]);

$router->setRoute('/', 'GET', [CLB\Core\Controllers\AppController::class, 'index']);
$router->setRoute('/login/{id}', 'POST', [CLB\Core\Controllers\LoginController::class, 'processLogin']);
$router->setRoute('/login', 'GET', [CLB\Core\Controllers\LoginController::class, 'getLogin']);

$application = new Application($router);

try {
    $application->watch();
} catch (\CLB\Core\Exceptions\NotFoundHttpException $e) {
    dump($e);
    die;
}
