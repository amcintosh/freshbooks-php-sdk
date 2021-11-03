<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Tests\Resource;

use PHPUnit\Framework\TestCase;
use amcintosh\FreshBooks\Exception\FreshBooksException;
use amcintosh\FreshBooks\Model\Client;
use amcintosh\FreshBooks\Resource\AccountingResource;
use amcintosh\FreshBooks\Tests\Resource\BaseResourceTest;

final class AccountingResourceTest extends TestCase
{
    use BaseResourceTest;

    protected function setUp(): void
    {
        $this->accountId = 'ACM123';
    }

    public function testGet(): void
    {
        $clientId = 12345;
        $mockHttpClient = $this->getMockHttpClient(
            200,
            ['response' => ['result' => ['client' => ['id' => $clientId]]]]
        );

        $resource = new AccountingResource($mockHttpClient, 'users/clients', Client::class);
        $client = $resource->get($this->accountId, $clientId);

        $this->assertEquals($clientId, $client->id);
    }

    public function testGetWrongSuccessContent(): void
    {
        $clientId = 12345;
        $mockHttpClient = $this->getMockHttpClient(200, ['foo' => 'bar']);

        $resource = new AccountingResource($mockHttpClient, 'users/clients', Client::class);

        $this->expectException(FreshBooksException::class);
        $this->expectExceptionMessage('Returned an unexpected response');

        $resource->get($this->accountId, $clientId);
    }

    public function testGetWrongErrorContent(): void
    {
        $clientId = 12345;
        $mockHttpClient = $this->getMockHttpClient(400, ['foo' => 'bar']);

        $resource = new AccountingResource($mockHttpClient, 'users/clients', Client::class);

        $this->expectException(FreshBooksException::class);
        $this->expectExceptionMessage('Returned an unexpected response');

        $resource->get($this->accountId, $clientId);
    }

    public function testGetNoPermission(): void
    {
        $clientId = 12345;
        $mockHttpClient = $this->getMockHttpClient(
            401,
            ['response' => ['errors' => [[
                'message' => 'The server could not verify that you are authorized to access the URL requested.',
                'errno' => 1003
            ]]]]
        );

        $resource = new AccountingResource($mockHttpClient, 'users/clients', Client::class);

        $this->expectException(FreshBooksException::class);
        $this->expectExceptionMessage(
            'The server could not verify that you are authorized to access the URL requested.'
        );

        $resource->get($this->accountId, $clientId);
    }
}
