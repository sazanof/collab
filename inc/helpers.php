<?php

use CLB\Core\Exceptions\IncludeNotDefinedException;

if(!defined('INC_MODE')){
    throw new IncludeNotDefinedException();
}

/**
 * Get env value
 * @param $key
 * @param $default
 * @return mixed
 */
function env($key, $default = null): mixed
{
    if(!empty($_ENV) && isset($_ENV[$key])){
        return $_ENV[$key];
    } else if(!empty($default)){
        return $default;
    } else {
        return  '';
    }
}