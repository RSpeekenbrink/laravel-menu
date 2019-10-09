<?php

namespace RSpeekenbrink\LaravelMenu\Exceptions;

use Throwable;

class MissingAssociatedMenuException extends \Exception
{
    public function __construct($message, $code = 0, Throwable $previous = null)
    {
        $message = 'An associated menu is missing while it\'s required. '.$message;

        parent::__construct($message, $code, $previous);
    }
}
