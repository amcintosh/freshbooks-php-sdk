<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Tests\Resource;

use PHPUnit\Framework\TestCase;
use amcintosh\FreshBooks\Exception\FreshBooksException;
use amcintosh\FreshBooks\Model\Identity;
use amcintosh\FreshBooks\Resource\AuthResource;
use amcintosh\FreshBooks\Tests\Resource\BaseResourceTest;

final class AuthResourceTest extends TestCase
{
    use BaseResourceTest;

    public function testGetMeEndpoint(): void
    {
        $mockHttpClient = $this->getMockHttpClient(
            200,
            ['response' => ['identity_id' => 123]]
        );

        $resource = new AuthResource($mockHttpClient);
        $identity = $resource->getMeEndpoint();

        $this->assertSame(123, $identity->identityId);

        $request = $mockHttpClient->getLastRequest();
        $this->assertSame('GET', $request->getMethod());
        $this->assertSame('/auth/api/v1/users/me', $request->getRequestTarget());
    }

    public function testGetMeWrongSuccessContent(): void
    {
        $mockHttpClient = $this->getMockHttpClient(200, ['foo' => 'bar']);

        $resource = new AuthResource($mockHttpClient);

        $this->expectException(FreshBooksException::class);
        $this->expectExceptionMessage('Returned an unexpected response');

        $identity = $resource->getMeEndpoint();
    }

    public function testGetMeWrongErrorContent(): void
    {
        $mockHttpClient = $this->getMockHttpClient(400, ['foo' => 'bar']);

        $resource = new AuthResource($mockHttpClient);

        $this->expectException(FreshBooksException::class);
        $this->expectExceptionMessage('Returned an unexpected response');

        $identity = $resource->getMeEndpoint();
    }

    public function testGetMeNoPermission(): void
    {
        $clientId = 12345;
        $mockHttpClient = $this->getMockHttpClient(
            401,
            [
                'error' => 'unauthenticated',
                'error_description' => 'This action requires authentication to continue.'
            ]
        );

        $resource = new AuthResource($mockHttpClient);

        $this->expectException(FreshBooksException::class);
        $this->expectExceptionMessage(
            'This action requires authentication to continue.'
        );

        $identity = $resource->getMeEndpoint();
    }
}
