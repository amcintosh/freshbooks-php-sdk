<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Tests;

use PHPUnit\Framework\TestCase;
use amcintosh\FreshBooks\FreshBooksClientConfig;

final class FreshBooksClientConfigTest extends TestCase
{
    public function testUserAgentDefault(): void
    {
        $version = '1.0.0';
        $clientId = 'my_client_id';

        $conf = new FreshBooksClientConfig();
        $conf->clientId = $clientId;
        $conf->version = $version;

        $this->assertEquals("FreshBooks php sdk/{$version} client_id {$clientId}", $conf->getUserAgent());
    }

    public function testUserAgentIsSet(): void
    {
        $myAgent = 'my awesome user agent string';

        $conf = new FreshBooksClientConfig(userAgent: $myAgent);
        $conf->clientId = 'my_client_id';

        $this->assertEquals($myAgent, $conf->getUserAgent());
    }
}
