<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks;

use DateTimeImmutable;

/**
 * Configuration object for FreshBooks API Client
 *
 * @package amcintosh\FreshBooks
 */
class FreshBooksClientConfig
{
    private const API_BASE_URL = "https://api.freshbooks.com";
    private const AUTH_BASE_URL = "https://auth.freshbooks.com";
    private const DEFAULT_TIMEOUT = 30;
    public string $apiBaseUrl;
    public string $authBaseUrl;
    public ?string $clientId;
    public ?string $clientSecret;
    public ?string $redirectUri;
    public ?string $accessToken;
    public ?string $refreshToken;
    public ?DateTimeImmutable $tokenExpiresAt;
    public ?string $userAgent;
    public bool $autoRetry;
    public int $retries;
    public int $timeout;
    public string $version;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct(
        ?string $clientSecret = null,
        ?string $redirectUri = null,
        ?string $accessToken = null,
        ?string $refreshToken = null,
        ?string $userAgent = null,
        bool $autoRetry = true,
        int $retries = 3,
        int $timeout = self::DEFAULT_TIMEOUT
    ) {
        $this->apiBaseUrl = self::API_BASE_URL;
        $this->authBaseUrl = self::AUTH_BASE_URL;
        $this->clientId = null;
        $this->clientSecret = $clientSecret;
        $this->redirectUri = $redirectUri;
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
        $this->tokenExpiresAt = null;
        $this->userAgent = $userAgent;
        $this->autoRetry = $autoRetry;
        $this->retries = $retries;
        $this->timeout = $timeout;
        $this->version = $this->getVersion();
    }

    /**
     * Get the library version.
     *
     * @return string
     */
    private function getVersion(): string
    {
        return trim(file_get_contents(dirname(__FILE__) . '/VERSION'));
    }

    /**
     * Get the userAgent for requests. If this is not set, the default will be set and returned.
     *
     * @return string
     */
    public function getUserAgent(): string
    {
        if (is_null($this->userAgent)) {
            $this->userAgent = "FreshBooks php sdk/{$this->version} client_id {$this->clientId}";
        }
        return $this->userAgent;
    }
}
