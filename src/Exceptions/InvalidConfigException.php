<?php

namespace Mainul12501\Laravel\Exceptions;

class InvalidConfigException extends FraudBDException
{
    /**
     * Create a new invalid config exception for missing API key.
     *
     * @return static
     */
    public static function missingApiKey(): self
    {
        return new static('FraudBD API key is not configured. Please set FRAUDBD_API_KEY in your .env file.');
    }

    /**
     * Create a new invalid config exception for missing username.
     *
     * @return static
     */
    public static function missingUsername(): self
    {
        return new static('FraudBD username is not configured. Please set FRAUDBD_USERNAME in your .env file.');
    }

    /**
     * Create a new invalid config exception for missing password.
     *
     * @return static
     */
    public static function missingPassword(): self
    {
        return new static('FraudBD password is not configured. Please set FRAUDBD_PASSWORD in your .env file.');
    }
}
