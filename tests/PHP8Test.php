<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks;

use PHPUnit\Framework\TestCase;
use amcintosh\FreshBooks\ClientConfig;

final class ClientConfigPHP8Test extends TestCase
{
    /**
     * @requires PHP >= 8.0
     */
    public function testUserAgentIsSetByParam(): void
    {
        $myAgent = 'my awesome user agent string';

        $conf = new ClientConfig(userAgent: $myAgent);
        $conf->clientId = 'my_client_id';

        $this->assertEquals($myAgent, $conf->getUserAgent());
    }
}
