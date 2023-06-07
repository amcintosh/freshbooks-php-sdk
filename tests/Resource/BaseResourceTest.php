<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Tests\Resource;

use GuzzleHttp\Psr7;
use Http\Discovery\Psr17FactoryDiscovery;
use amcintosh\FreshBooks\Tests\Util\MockHttpClient;

trait BaseResourceTest
{
    public function getMockHttpClient(int $status = 200, array $content = null): MockHttpClient
    {
        $mockHttpClient = new MockHttpClient(
            Psr17FactoryDiscovery::findRequestFactory(),
            Psr17FactoryDiscovery::findStreamFactory()
        );

        $response = $this->createMock('Psr\Http\Message\ResponseInterface');
        $response->method('getStatusCode')->will($this->returnValue($status));
        $response->method('getBody')->will($this->returnValue(Psr7\Utils::streamFor(json_encode($content))));
        $mockHttpClient->addResponse($response);
        return $mockHttpClient;
    }
}
