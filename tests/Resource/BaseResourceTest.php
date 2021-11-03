<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Tests\Resource;

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
        $mockContent = $this->getMockBuilder(\stdclass::class)->setMethods(['getContents'])->getMock();
        $mockContent->method('getContents')->will($this->returnValue(json_encode($content)));

        $response = $this->createMock('Psr\Http\Message\ResponseInterface');
        $response->method('getStatusCode')->will($this->returnValue($status));
        $response->method('getBody')->will($this->returnValue($mockContent));
        $mockHttpClient->addResponse($response);
        return $mockHttpClient;
    }
}
