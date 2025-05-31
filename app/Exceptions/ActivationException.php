<?php

namespace App\Exceptions;

use Exception;

class ActivationException extends Exception
{
    public function __construct(string $message = 'Activation not permitted', int $errorCode = 0) {
        parent::__construct($message, $errorCode);
    }
}
