<?php

namespace Mainul12501\Laravel;

use Mainul12501\Laravel\Exceptions\ApiException;
use Mainul12501\Laravel\Exceptions\InvalidConfigException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Config;

class FraudBD
{
    /**
     * The HTTP client instance.
     *
     * @var \GuzzleHttp\Client
     */
    protected Client $client;

    /**
     * The FraudBD API key.
     *
     * @var string
     */
    protected string $apiKey;

    /**
     * The FraudBD username.
     *
     * @var string
     */
    protected string $username;

    /**
     * The FraudBD password.
     *
     * @var string
     */
    protected string $password;

    /**
     * The base URL for the FraudBD API.
     *
     * @var string
     */
    protected string $baseUrl;

    /**
     * Create a new FraudBD instance.
     *
     * @param array|null $config
     * @throws InvalidConfigException
     */
    public function __construct(?array $config = null)
    {
        $this->configure($config);
        $this->validateConfig();
        $this->initializeClient();
    }

    /**
     * Configure the FraudBD client.
     *
     * @param array|null $config
     * @return void
     */
    protected function configure(?array $config): void
    {
        $this->apiKey = $config['api_key'] ?? Config::get('fraudbd.api_key');
        $this->username = $config['username'] ?? Config::get('fraudbd.username');
        $this->password = $config['password'] ?? Config::get('fraudbd.password');
        $this->baseUrl = $config['base_url'] ?? Config::get('fraudbd.base_url');
    }

    /**
     * Validate the configuration.
     *
     * @return void
     * @throws InvalidConfigException
     */
    protected function validateConfig(): void
    {
        if (empty($this->apiKey)) {
            throw InvalidConfigException::missingApiKey();
        }

        if (empty($this->username)) {
            throw InvalidConfigException::missingUsername();
        }

        if (empty($this->password)) {
            throw InvalidConfigException::missingPassword();
        }
    }

    /**
     * Initialize the HTTP client.
     *
     * @return void
     */
    protected function initializeClient(): void
    {
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'timeout' => Config::get('fraudbd.timeout', 30),
            'verify' => Config::get('fraudbd.verify_ssl', true),
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    /**
     * Get the authentication headers.
     *
     * @return array
     */
    protected function getAuthHeaders(): array
    {
        return [
            'X-API-Key' => $this->apiKey,
            'X-Username' => $this->username,
            'X-Password' => $this->password,
        ];
    }

    /**
     * Make a request to the FraudBD API.
     *
     * @param string $method
     * @param string $endpoint
     * @param array $data
     * @return array
     * @throws ApiException
     */
    protected function request(string $method, string $endpoint, array $data = []): array
    {
        try {
            $options = [
                'headers' => $this->getAuthHeaders(),
            ];

            if (!empty($data)) {
                $options['json'] = $data;
            }

            $response = $this->client->request($method, $endpoint, $options);
            $body = (string) $response->getBody();

            return json_decode($body, true) ?? [];

        } catch (GuzzleException $e) {
            $statusCode = $e->getCode();
            $message = $e->getMessage();

            if ($statusCode === 429) {
                throw ApiException::rateLimitExceeded();
            }

            if ($statusCode === 401) {
                throw ApiException::authenticationFailed();
            }

            throw ApiException::failedRequest($message, $statusCode);
        }
    }

    /**
     * Check courier information for a specific phone number.
     *
     * @param string $phone
     * @param array $additionalData
     * @return array
     * @throws ApiException
     */
    public function checkCourierInfo(string $phone, array $additionalData = []): array
    {
        $data = array_merge(['phone' => $phone], $additionalData);

        return $this->request('POST', '/api/check-courier-info', $data);
    }

    /**
     * Check courier information for a specific courier service.
     *
     * @param string $courierName
     * @param string $phone
     * @param array $additionalData
     * @return array
     * @throws ApiException
     */
    public function checkCourierInfoByCourier(string $courierName, string $phone, array $additionalData = []): array
    {
        $data = array_merge(['phone' => $phone], $additionalData);

        return $this->request('POST', "/api/check-courier-info/{$courierName}", $data);
    }

    /**
     * Bulk check courier information.
     *
     * @param array $phones
     * @param array $additionalData
     * @return array
     * @throws ApiException
     */
    public function bulkCheckCourierInfo(array $phones, array $additionalData = []): array
    {
        $data = array_merge(['phones' => $phones], $additionalData);

        return $this->request('POST', '/api/bulk/check-courier-info', $data);
    }

    /**
     * Check the status of a process.
     *
     * @param string $processId
     * @param array $additionalData
     * @return array
     * @throws ApiException
     */
    public function checkProcessStatus(string $processId, array $additionalData = []): array
    {
        $data = array_merge(['process_id' => $processId], $additionalData);

        return $this->request('POST', '/api/check-process-status', $data);
    }

    /**
     * Set a custom API key.
     *
     * @param string $apiKey
     * @return self
     */
    public function setApiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    /**
     * Set custom credentials.
     *
     * @param string $username
     * @param string $password
     * @return self
     */
    public function setCredentials(string $username, string $password): self
    {
        $this->username = $username;
        $this->password = $password;
        return $this;
    }

    /**
     * Set the base URL.
     *
     * @param string $baseUrl
     * @return self
     */
    public function setBaseUrl(string $baseUrl): self
    {
        $this->baseUrl = $baseUrl;
        $this->initializeClient();
        return $this;
    }
}
