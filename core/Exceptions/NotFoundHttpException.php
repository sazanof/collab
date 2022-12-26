<?php

namespace CLB\Core\Exceptions;

class NotFoundHttpException extends \Exception
{
    public function __construct($message = "", $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
