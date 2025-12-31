<?php

namespace App\Exceptions;

use Exception;

class ServiceException extends BaseException
{
    public function __construct(string $message = 'An error was encountered while trying to apply the rules required for the action. Please try again or contact support.', int $errorCode = 0, ?Exception $previous = null) {
        parent::__construct($message, $errorCode, $previous);
    }
}
