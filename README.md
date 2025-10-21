# FraudBD Laravel Package

A Laravel package for integrating FraudBD API services to check courier information and detect fraud in e-commerce deliveries.

## Features

- Easy integration with FraudBD API
- Support for Laravel 10.x, 11.x and 12.x
- Check courier information for phone numbers
- Bulk courier information checking
- Process status tracking
- Comprehensive error handling
- Facade support for easy access
- Configuration via `.env` file

## Requirements

- PHP 8.1 or higher
- Laravel 10.x, 11.x or 12.x
- Guzzle HTTP client 7.x

## Installation

Install the package via Composer:

```bash
composer require mainul12501/fraudbd-laravel
```

### Publish Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --tag=fraudbd-config
```

This will create a `config/fraudbd.php` file in your application.

## Configuration

Add the following environment variables to your `.env` file:

```env
FRAUDBD_API_KEY=your-api-key-here
FRAUDBD_USERNAME=your-username-here
FRAUDBD_PASSWORD=your-password-here
FRAUDBD_BASE_URL=https://fraudbd.com
FRAUDBD_TIMEOUT=30
FRAUDBD_VERIFY_SSL=true
```

### Configuration Options

- `FRAUDBD_API_KEY`: Your FraudBD API key (required)
- `FRAUDBD_USERNAME`: Your FraudBD account username (required)
- `FRAUDBD_PASSWORD`: Your FraudBD account password (required)
- `FRAUDBD_BASE_URL`: The base URL for the FraudBD API (default: https://fraudbd.com)
- `FRAUDBD_TIMEOUT`: Request timeout in seconds (default: 30)
- `FRAUDBD_VERIFY_SSL`: Whether to verify SSL certificates (default: true)

## Usage

### Using Facade

The easiest way to use the package is through the `FraudBD` facade:

```php
use Mainul12501\Laravel\Facades\FraudBD;

// Check courier information for a phone number
$result = FraudBD::checkCourierInfo('01712345678');

// Check courier information for a specific courier
$result = FraudBD::checkCourierInfoByCourier('steadfast', '01712345678');

// Bulk check courier information
$result = FraudBD::bulkCheckCourierInfo([
    '01712345678',
    '01812345678',
    '01912345678',
]);

// Check process status
$result = FraudBD::checkProcessStatus('process-id-123');
```

### Using Dependency Injection

You can also inject the `FraudBD` class into your controllers or services:

```php
use Mainul12501\Laravel\FraudBD;

class OrderController extends Controller
{
    public function checkCourier(FraudBD $fraudbd)
    {
        try {
            $result = $fraudbd->checkCourierInfo('01712345678');

            return response()->json($result);
        } catch (\Mainul12501\Laravel\Exceptions\ApiException $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
```

### Using Helper

You can also use the helper function:

```php
$result = app('fraudbd')->checkCourierInfo('01712345678');
```

## Available Methods

### Check Courier Info

Check courier information for a specific phone number across all supported couriers:

```php
FraudBD::checkCourierInfo(string $phone, array $additionalData = []): array
```

**Example:**

```php
$result = FraudBD::checkCourierInfo('01712345678', [
    'additional_field' => 'value'
]);
```

### Check Courier Info by Courier

Check courier information for a specific courier service:

```php
FraudBD::checkCourierInfoByCourier(string $courierName, string $phone, array $additionalData = []): array
```

**Example:**

```php
$result = FraudBD::checkCourierInfoByCourier('steadfast', '01712345678');
```

**Supported Couriers:**
- steadfast
- pathao
- redx
- paperfly
- (and more - check FraudBD documentation)

### Bulk Check Courier Info

Check courier information for multiple phone numbers:

```php
FraudBD::bulkCheckCourierInfo(array $phones, array $additionalData = []): array
```

**Example:**

```php
$result = FraudBD::bulkCheckCourierInfo([
    '01712345678',
    '01812345678',
    '01912345678',
]);
```

### Check Process Status

Check the status of a processing request:

```php
FraudBD::checkProcessStatus(string $processId, array $additionalData = []): array
```

**Example:**

```php
$result = FraudBD::checkProcessStatus('process-id-123');
```

## Runtime Configuration

You can override configuration at runtime:

```php
use Mainul12501\Laravel\Facades\FraudBD;

// Set custom API key
FraudBD::setApiKey('custom-api-key');

// Set custom credentials
FraudBD::setCredentials('username', 'password');

// Set custom base URL
FraudBD::setBaseUrl('https://custom-api.fraudbd.com');

// Chain methods
FraudBD::setApiKey('custom-key')
       ->setCredentials('user', 'pass')
       ->checkCourierInfo('01712345678');
```

## Error Handling

The package throws specific exceptions for different error scenarios:

```php
use Mainul12501\Laravel\Exceptions\InvalidConfigException;
use Mainul12501\Laravel\Exceptions\ApiException;
use Mainul12501\Laravel\Exceptions\FraudBDException;

try {
    $result = FraudBD::checkCourierInfo('01712345678');
} catch (InvalidConfigException $e) {
    // Handle configuration errors (missing API key, username, or password)
    Log::error('FraudBD configuration error: ' . $e->getMessage());
} catch (ApiException $e) {
    // Handle API errors (rate limit, authentication failure, etc.)
    Log::error('FraudBD API error: ' . $e->getMessage());
} catch (FraudBDException $e) {
    // Handle general FraudBD errors
    Log::error('FraudBD error: ' . $e->getMessage());
}
```

### Exception Types

- `InvalidConfigException`: Thrown when configuration is missing or invalid
- `ApiException`: Thrown when API requests fail
- `FraudBDException`: Base exception class for all FraudBD exceptions

## Example Usage in Laravel

### Controller Example

```php
<?php

namespace App\Http\Controllers;

use Mainul12501\Laravel\Facades\FraudBD;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FraudCheckController extends Controller
{
    public function checkPhone(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => 'required|string',
        ]);

        try {
            $result = FraudBD::checkCourierInfo($request->phone);

            return response()->json([
                'success' => true,
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function bulkCheck(Request $request): JsonResponse
    {
        $request->validate([
            'phones' => 'required|array',
            'phones.*' => 'required|string',
        ]);

        try {
            $result = FraudBD::bulkCheckCourierInfo($request->phones);

            return response()->json([
                'success' => true,
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
```

### Service Example

```php
<?php

namespace App\Services;

use Mainul12501\Laravel\FraudBD;
use Illuminate\Support\Facades\Log;

class OrderValidationService
{
    public function __construct(
        protected FraudBD $fraudbd
    ) {}

    public function validateCustomerPhone(string $phone): bool
    {
        try {
            $result = $this->fraudbd->checkCourierInfo($phone);

            // Implement your validation logic based on the result
            // For example, check if the phone has suspicious activity
            return $this->isSafe($result);
        } catch (\Exception $e) {
            Log::error('Failed to validate customer phone', [
                'phone' => $phone,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    protected function isSafe(array $result): bool
    {
        // Implement your business logic here
        // Example: Check if fraud score is below threshold
        return true;
    }
}
```

## Testing

The package includes a comprehensive test suite. To run tests:

```bash
composer test
```

## API Documentation

For complete API documentation, visit: [https://fraudbd.com/api-documentation](https://fraudbd.com/api-documentation)

## Security

Always use HTTPS for API requests (enabled by default). Never commit your API credentials to version control.

If you discover any security-related issues, please email mainul125011@gmail.com instead of using the issue tracker.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

## Support

For support, please visit [FraudBD Documentation](https://fraudbd.com/api-documentation) or create an issue on GitHub.
