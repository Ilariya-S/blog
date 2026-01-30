<?php
namespace App\Services\Users\Exceptions;

use Exception;
use Throwable;

class AuthorizationException extends Exception
{
    public function __construct(string $message = "Authorization failed.", int $code = 401, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}