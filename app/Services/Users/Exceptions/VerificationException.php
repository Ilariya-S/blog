<?php
namespace App\Services\Users\Exceptions;

use Exception;
use Throwable;

class VerificationException extends Exception
{
    public function __construct(string $message = "Email verification failed.", int $code = 403, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}