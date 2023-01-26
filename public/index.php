<?php

const INC_MODE = true;

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once '../vendor/autoload.php';

use CLB\Application\Session;
use CLB\Core\Application;
use CLB\Core\Router\MainRouter;

Session::start();

// TODO add referer to route param to make work route only if referer is set & matches
// TODO move routes to routes.php or yaml
$router = new MainRouter();
$router->addRoutesFromAppInc();

// todo добавление и регистрация приложений и их роутов из apps

$application = new Application($router);
return $application->watch();

