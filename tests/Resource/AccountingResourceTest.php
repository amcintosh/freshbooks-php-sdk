<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Tests\Resource;

use PHPUnit\Framework\TestCase;
use amcintosh\FreshBooks\Exception\FreshBooksException;
use amcintosh\FreshBooks\Model\Client;
use amcintosh\FreshBooks\Model\ClientList;
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

        $resource = new AccountingResource($mockHttpClient, 'users/clients', Client::class, ClientList::class);
        $client = $resource->get($this->accountId, $clientId);

        $this->assertEquals($clientId, $client->id);

        $request = $mockHttpClient->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/accounting/account/ACM123/users/clients/12345', $request->getRequestTarget());
    }

    public function testGetWrongSuccessContent(): void
    {
        $clientId = 12345;
        $mockHttpClient = $this->getMockHttpClient(200, ['foo' => 'bar']);

        $resource = new AccountingResource($mockHttpClient, 'users/clients', Client::class, ClientList::class);

        $this->expectException(FreshBooksException::class);
        $this->expectExceptionMessage('Returned an unexpected response');

        $resource->get($this->accountId, $clientId);
    }

    public function testGetWrongErrorContent(): void
    {
        $clientId = 12345;
        $mockHttpClient = $this->getMockHttpClient(400, ['foo' => 'bar']);

        $resource = new AccountingResource($mockHttpClient, 'users/clients', Client::class, ClientList::class);

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

        $resource = new AccountingResource($mockHttpClient, 'users/clients', Client::class, ClientList::class);

        $this->expectException(FreshBooksException::class);
        $this->expectExceptionMessage(
            'The server could not verify that you are authorized to access the URL requested.'
        );

        $resource->get($this->accountId, $clientId);
    }

    public function testList(): void
    {
        $clientId = 12345;
        $mockHttpClient = $this->getMockHttpClient(
            200,
            ['response' => ['result' => [
                'clients' => [['id' => $clientId]],
                'page' => 0,
                'per_page' => 15,
                'pages' => 1,
                'total' => 1
            ]]]
        );

        $resource = new AccountingResource($mockHttpClient, 'users/clients', Client::class, ClientList::class);
        $clients = $resource->list($this->accountId);

        $this->assertEquals($clientId, $clients->clients[0]->id);
        $this->assertEquals(0, $clients->page);
        $this->assertEquals(15, $clients->perPage);
        $this->assertEquals(1, $clients->pages);
        $this->assertEquals(1, $clients->total);

        $request = $mockHttpClient->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/accounting/account/ACM123/users/clients', $request->getRequestTarget());
    }

    public function testCreateByModel(): void
    {
        $clientId = 12345;
        $mockHttpClient = $this->getMockHttpClient(
            200,
            ['response' => ['result' => ['client' => ['id' => $clientId, 'organization' => 'FreshBooks']]]]
        );
        $model = new Client();
        $model->organization = 'FreshBooks';

        $resource = new AccountingResource($mockHttpClient, 'users/clients', Client::class, ClientList::class);
        $client = $resource->create($this->accountId, model: $model);

        $this->assertEquals($clientId, $client->id);
        $this->assertEquals('FreshBooks', $client->organization);

        $request = $mockHttpClient->getLastRequest();
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('/accounting/account/ACM123/users/clients', $request->getRequestTarget());
    }

    public function testCreateByData(): void
    {
        $clientId = 12345;
        $mockHttpClient = $this->getMockHttpClient(
            200,
            ['response' => ['result' => ['client' => ['id' => $clientId, 'organization' => 'FreshBooks']]]]
        );
        $model = new Client();
        $model->organization = 'FreshBooks';

        $resource = new AccountingResource($mockHttpClient, 'users/clients', Client::class, ClientList::class);
        $client = $resource->create($this->accountId, model: $model);

        $this->assertEquals($clientId, $client->id);
        $this->assertEquals('FreshBooks', $client->organization);

        $request = $mockHttpClient->getLastRequest();
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('/accounting/account/ACM123/users/clients', $request->getRequestTarget());
    }
}
