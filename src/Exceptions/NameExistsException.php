<?php

namespace RSpeekenbrink\LaravelMenu\Exceptions;

use Throwable;

class NameExistsException extends \Exception
{
    public function __construct($name, $code = 0, Throwable $previous = null)
    {
        $message = "A MenuItem with the name '{$name}' already exists in the Menu";

        parent::__construct($message, $code, $previous);
    }
}
