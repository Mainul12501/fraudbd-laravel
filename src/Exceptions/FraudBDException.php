<?php

namespace Mainul12501\Laravel\Exceptions;

use Exception;

class FraudBDException extends Exception
{
    /**
     * Create a new FraudBD exception instance.
     *
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $message = "", int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
