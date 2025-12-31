<?php

namespace App\Exceptions;

use Exception;

class BaseException extends Exception
{
    public function __construct(string $message = 'An error occurred while performing the action. Please try again or contact support.', int $errorCode = 0, ?Exception $previous = null) {
        if($errorCode == 0 && $previous != null) 
            $errorCode = $previous->getCode();

        parent::__construct($message, $errorCode, $previous);
    }
}
