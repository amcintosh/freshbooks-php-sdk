<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Tests\Util;

use Http\Mock\Client;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;

final class MockHttpClient extends Client
{
    private $requestFactory;
    private ?StreamFactoryInterface $streamFactory;

    public function __construct($requestFactory, ?StreamFactoryInterface $streamFactory = null)
    {
        $this->requestFactory = $requestFactory;
        $this->streamFactory = $streamFactory;
    }

    public function send(string $method, $uri, array $headers = [], $body = null): ResponseInterface
    {
        $requestInterface = $this->requestFactory->createRequest($method, $uri, $headers, $body);
        return $this->sendRequest($requestInterface);
    }
}
