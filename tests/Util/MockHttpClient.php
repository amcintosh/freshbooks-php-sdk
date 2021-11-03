<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Tests\Util;

use Http\Mock\Client;
use Psr\Http\Message\StreamFactoryInterface;

final class MockHttpClient extends Client
{
    public function __construct($requestFactory, StreamFactoryInterface $streamFactory = null)
    {
        $this->requestFactory = $requestFactory;
        $this->streamFactory = $streamFactory;
    }

    public function send(string $method, $uri, array $headers = [], $body = null)
    {
        $requestInterface = $this->requestFactory->createRequest($method, $uri, $headers, $body);
        return $this->sendRequest($requestInterface);
    }
}
