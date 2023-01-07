<?php

namespace CLB\Core\Exceptions;

class EntityManagerNotDefinedException extends \Exception
{
    public function __construct(string $message = "Entity manager not defined", int $code = 11, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}