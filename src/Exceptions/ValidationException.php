<?php

namespace Pedrokeilerbatistarojo\Smartfilter\Exceptions;

use Exception;
use Throwable;

class ValidationException extends Exception
{
    public array $errors = [];

    // Redefine the exception so message isn't optional
    public function __construct(array $errors, string $message = 'Validation error', int $code = 0, ?Throwable $previous = null)
    {

        $this->errors = $errors;

        // make sure everything is assigned properly
        parent::__construct($message, $code, $previous);
    }
}
