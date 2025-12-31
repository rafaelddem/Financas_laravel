<?php

namespace App\Exceptions;

use Exception;

class InactivationException extends BaseException
{
    public function __construct(string $message = 'Inactivation not permitted', int $errorCode = 0, ?Exception $previous = null) {
        parent::__construct($message, $errorCode, $previous);
    }
}
