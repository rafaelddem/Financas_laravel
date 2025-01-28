<?php

namespace App\Exceptions;

use App\Exceptions\BaseException;
use Exception;

class ServiceException extends BaseException
{
    public function __construct(string $message = 'An error was encountered while trying to apply the rules required for the action. Please try again or contact support.', ?int $code = null, ?Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
