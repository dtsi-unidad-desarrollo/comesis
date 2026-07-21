<?php

namespace App\Exceptions;

use Illuminate\Session\TokenMismatchException;
use Throwable;

class PageExpiredException extends TokenMismatchException
{
    /**
     * Create a new page expired exception instance.
     */
    public function __construct(string $message = 'La página ha expirado.', int $code = 419, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function getStatusCode(): int
    {
        return 419;
    }
}
