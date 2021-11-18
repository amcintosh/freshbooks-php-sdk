<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Tests;

use PHPUnit\Framework\TestCase;
use amcintosh\FreshBooks\Tests\Util\MockFreshBooksClient;
use amcintosh\FreshBooks\FreshBooksClientConfig;

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
}
