<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Tests\Util;

use Http\Client\Common\HttpMethodsClient;
use amcintosh\FreshBooks\FreshBooksClient;

final class MockFreshBooksClient extends FreshBooksClient
{

    public function accessCreateHttpClient(): HttpMethodsClient
    {
        return $this->createHttpClient();
    }

    public function accessGetHeaders(): array
    {
        return $this->getHeaders();
    }
}
