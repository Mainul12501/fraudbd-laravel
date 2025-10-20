<?php

namespace Mainul12501\Laravel\Exceptions;

class ApiException extends FraudBDException
{
    /**
     * Create a new API exception for failed requests.
     *
     * @param string $message
     * @param int $statusCode
     * @param mixed $response
     * @return static
     */
    public static function failedRequest(string $message, int $statusCode, $response = null): self
    {
        $errorMessage = "FraudBD API request failed: {$message} (Status: {$statusCode})";

        if ($response) {
            $errorMessage .= " Response: " . json_encode($response);
        }

        return new static($errorMessage, $statusCode);
    }

    /**
     * Create a new API exception for rate limiting.
     *
     * @return static
     */
    public static function rateLimitExceeded(): self
    {
        return new static('FraudBD API rate limit exceeded. Please try again later.', 429);
    }

    /**
     * Create a new API exception for authentication errors.
     *
     * @return static
     */
    public static function authenticationFailed(): self
    {
        return new static('FraudBD API authentication failed. Please check your credentials.', 401);
    }
}
