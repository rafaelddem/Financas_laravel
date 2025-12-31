<?php

namespace App\Exceptions;

use Exception;

class ActivationException extends BaseException
{
    public function __construct(string $message = 'Activation not permitted', int $errorCode = 0, ?Exception $previous = null) {
        parent::__construct($message, $errorCode, $previous);
    }
}
