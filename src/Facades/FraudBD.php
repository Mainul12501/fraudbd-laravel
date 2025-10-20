<?php

namespace Mainul12501\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array checkCourierInfo(string $phone, array $additionalData = [])
 * @method static array checkCourierInfoByCourier(string $courierName, string $phone, array $additionalData = [])
 * @method static array bulkCheckCourierInfo(array $phones, array $additionalData = [])
 * @method static array checkProcessStatus(string $processId, array $additionalData = [])
 * @method static self setApiKey(string $apiKey)
 * @method static self setCredentials(string $username, string $password)
 * @method static self setBaseUrl(string $baseUrl)
 *
 * @see \Mainul12501\Laravel\FraudBD
 */
class FraudBD extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'fraudbd';
    }
}
