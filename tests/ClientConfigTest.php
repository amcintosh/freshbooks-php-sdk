<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks;

use PHPUnit\Framework\TestCase;
use amcintosh\FreshBooks\ClientConfig;

final class ClientConfigTest extends TestCase
{
    public function testUserAgentDefault(): void
    {
        $version = '1.0.0';
        $clientId = 'my_client_id';

        $conf = new ClientConfig();
        $conf->clientId = $clientId;
        $conf->version = $version;

        $this->assertEquals("FreshBooks php sdk/{$version} client_id {$clientId}", $conf->getUserAgent());
    }

    public function testUserAgentIsSet(): void
    {
        $myAgent = 'my awesome user agent string';

        $conf = new ClientConfig(userAgent: $myAgent);
        $conf->clientId = 'my_client_id';

        $this->assertEquals($myAgent, $conf->getUserAgent());
    }

    /*public function testCannotBeCreatedFromInvalidEmailAddress(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Email::fromString('invalid');
    }

    public function testCanBeUsedAsString(): void
    {
        $this->assertEquals(
            'user@example.com',
            Email::fromString('user@example.com')
        );
    }*/
}
