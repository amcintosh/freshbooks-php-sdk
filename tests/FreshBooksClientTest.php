<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Tests;

use PHPUnit\Framework\TestCase;
use amcintosh\FreshBooks\Exception\FreshBooksClientConfigException;
use amcintosh\FreshBooks\FreshBooksClientConfig;
use amcintosh\FreshBooks\Tests\Util\MockFreshBooksClient;

final class FreshBooksClientTest extends TestCase
{
    public function testGetHeaders(): void
    {
        $expectedHeaders = [
            'Authorization' => 'Bearer some_token',
            'User-Agent' => 'some_ua',
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];
        $conf = new FreshBooksClientConfig(accessToken: 'some_token', userAgent: 'some_ua');

        $client = new MockFreshBooksClient('some_client_id', $conf);
        $this->assertSame($expectedHeaders, $client->accessGetHeaders());
    }

    public function testGetAuthRequestUrl(): void
    {
        $conf = new FreshBooksClientConfig(redirectUri: "https://example.com");
        $client = new MockFreshBooksClient('some_client_id', $conf);

        $this->assertSame(
            'https://auth.freshbooks.com/oauth/authorize?' .
            'client_id=some_client_id&response_type=code&redirect_uri=https%3A%2F%2Fexample.com',
            $client->getAuthRequestUri()
        );
    }

    public function testGetAuthRequestUrlWithScopes(): void
    {
        $conf = new FreshBooksClientConfig(redirectUri: "https://example.com");
        $client = new MockFreshBooksClient('some_client_id', $conf);
        $scopes = ["some:scope", "another:scope"];

        $this->assertSame(
            'https://auth.freshbooks.com/oauth/authorize?' .
            'client_id=some_client_id&response_type=code&redirect_uri=https%3A%2F%2Fexample.com' .
            '&scope=some%3Ascope+another%3Ascope',
            $client->getAuthRequestUri($scopes)
        );
    }

    public function testGetAuthRequestUrlRedirectNotProvided(): void
    {
        $conf = new FreshBooksClientConfig();
        $client = new MockFreshBooksClient('some_client_id', $conf);

        $this->expectException(FreshBooksClientConfigException::class);
        $this->expectExceptionMessage('redirectUri must be configured');

        $client->getAuthRequestUri();
    }
}
