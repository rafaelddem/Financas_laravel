<?php

namespace App\Exceptions;

use Exception;

class BaseException extends Exception
{
    public function __construct(string $message = 'An error occurred while performing the action. Please try again or contact support.', ?int $code = null, ?Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
