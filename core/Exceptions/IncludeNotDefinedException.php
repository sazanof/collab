<?php

namespace CLB\Core\Exceptions;

class IncludeNotDefinedException extends \Exception
{
    public function __construct(string $message = "Error opening file", int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}