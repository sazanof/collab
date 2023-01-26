<?php
if(!defined('INC_MODE')){
    exit;
}
return [
    '/'=>[
        'action'=> [CLB\Core\Controllers\AppController::class, 'index'],
        'methods'=> ['GET']
    ],
    '/login/{id}'=>[
        'action'=> [CLB\Core\Controllers\LoginController::class, 'processLogin'],
        'methods'=> ['POST']
    ],
    '/login'=>[
        'action'=> [CLB\Core\Controllers\LoginController::class, 'getLogin'],
        'methods'=> ['GET']
    ],
    '/locales' => [
        'action'=> [CLB\Core\Controllers\LocaleController::class, 'getLocaleList'],
        'methods'=> ['GET'],
        'defaults'=>[
            'public'=>true
        ]
    ],
    '/locales/{lang}' => [
        'action'=> [CLB\Core\Controllers\LocaleController::class, 'getTranslation'],
        'methods'=> ['GET'],
        'defaults'=>[
            'public'=>true
        ]
    ],
    '/install/{step}' => [
        'action'=> [CLB\Core\Controllers\InstallController::class, 'install'],
        'methods'=> ['GET', 'POST'],
        'defaults'=>[
            'step'=>0,
            'public'=>true
        ]
    ],
];