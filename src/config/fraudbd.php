<?php

return [

    /*
    |--------------------------------------------------------------------------
    | FraudBD API Key
    |--------------------------------------------------------------------------
    |
    | Your FraudBD API key. You can find this in your FraudBD account settings.
    | This key is required to authenticate all API requests.
    |
    */
    'api_key' => env('FRAUDBD_API_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | FraudBD Username
    |--------------------------------------------------------------------------
    |
    | Your FraudBD account username for API authentication.
    |
    */
    'username' => env('FRAUDBD_USERNAME', ''),

    /*
    |--------------------------------------------------------------------------
    | FraudBD Password
    |--------------------------------------------------------------------------
    |
    | Your FraudBD account password for API authentication.
    |
    */
    'password' => env('FRAUDBD_PASSWORD', ''),

    /*
    |--------------------------------------------------------------------------
    | FraudBD API Base URL
    |--------------------------------------------------------------------------
    |
    | The base URL for the FraudBD API. You typically don't need to change this.
    |
    */
    'base_url' => env('FRAUDBD_BASE_URL', 'https://fraudbd.com'),

    /*
    |--------------------------------------------------------------------------
    | Request Timeout
    |--------------------------------------------------------------------------
    |
    | The timeout for API requests in seconds.
    |
    */
    'timeout' => env('FRAUDBD_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | Verify SSL
    |--------------------------------------------------------------------------
    |
    | Whether to verify SSL certificates. Set to false only for local development.
    |
    */
    'verify_ssl' => env('FRAUDBD_VERIFY_SSL', true),

];
