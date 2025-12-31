<?php

namespace App\Exceptions;

use Exception;

class RepositoryException extends BaseException
{
    public function __construct(string $message = 'An error occurred while trying to perform the action on the database. Please try again or contact support.', int $errorCode = 0, ?Exception $previous = null) {
        parent::__construct($message, $errorCode, $previous);
    }
}
