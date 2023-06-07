<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Tests\Resource;

use PHPUnit\Framework\TestCase;
use amcintosh\FreshBooks\Exception\FreshBooksException;
use amcintosh\FreshBooks\Exception\FreshBooksNotImplementedException;
use amcintosh\FreshBooks\Builder\FilterBuilder;
use amcintosh\FreshBooks\Builder\IncludesBuilder;
use amcintosh\FreshBooks\Builder\PaginateBuilder;
use amcintosh\FreshBooks\Model\Client;
use amcintosh\FreshBooks\Model\ClientList;
use amcintosh\FreshBooks\Model\VisState;
use amcintosh\FreshBooks\Resource\AccountingResource;
use amcintosh\FreshBooks\Tests\Resource\BaseResourceTest;

final class AccountingResourceTest extends TestCase
{
    use BaseResourceTest;

    public string $accountId;

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

        $this->assertSame($clientId, $client->id);

        $request = $mockHttpClient->getLastRequest();
        $this->assertSame('GET', $request->getMethod());
        $this->assertSame('/accounting/account/ACM123/users/clients/12345', $request->getRequestTarget());
    }

    public function testGetWithIncludes(): void
    {
        $clientId = 12345;
        $mockHttpClient = $this->getMockHttpClient(
            200,
            ['response' => ['result' => ['client' => ['id' => $clientId]]]]
        );

        $resource = new AccountingResource($mockHttpClient, 'users/clients', Client::class, ClientList::class);
        $includes = (new IncludesBuilder())->include('late_reminders')->include('late_fees');
        $client = $resource->get($this->accountId, $clientId, $includes);

        $this->assertSame($clientId, $client->id);

        $request = $mockHttpClient->getLastRequest();
        $this->assertSame('GET', $request->getMethod());
        $this->assertSame(
            '/accounting/account/ACM123/users/clients/12345?include%5B%5D=late_reminders&include%5B%5D=late_fees',
            $request->getRequestTarget()
        );
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
        $this->expectExceptionMessage('Unknown error');

        $resource->get($this->accountId, $clientId);
    }

    public function testGetNotFoundOldError(): void
    {
        $clientId = 12345;
        $mockHttpClient = $this->getMockHttpClient(
            404,
            ['response' => ['errors' => [[
                'errno' => 1012,
                'field' => 'userid',
                'message' => 'Client not found.',
                'object' => 'client',
                'value' => '12345'
            ]]]]
        );

        $resource = new AccountingResource($mockHttpClient, 'users/clients', Client::class, ClientList::class);

        try {
            $resource->get($this->accountId, $clientId);
            $this->fail('FreshBooksException was not thrown');
        } catch (FreshBooksException $e) {
            $this->assertSame('Client not found.', $e->getMessage());
            $this->assertSame(404, $e->getCode());
            $this->assertSame(1012, $e->getErrorCode());
            $this->assertSame(
                [
                    [
                        'errno' => 1012,
                        'field' => 'userid',
                        'message' => 'Client not found.',
                        'object' => 'client',
                        'value' => '12345'
                    ]
                ],
                $e->getErrorDetails()
            );
        }
    }

    public function testGetNotFoundNewError(): void
    {
        $clientId = 12345;
        $mockHttpClient = $this->getMockHttpClient(
            404,
            [
                'code' => 5,
                'message' => 'Request failed with status_code: 404',
                'details' => [
                    [
                        '@type' => 'type.googleapis.com/google.rpc.ErrorInfo',
                        'reason' => '1012',
                        'domain' => 'accounting.api.freshbooks.com',
                        'metadata' => [
                            'object' => 'client',
                            'message' => 'Client not found.',
                            'value' => '12345',
                            'field' => 'userid'
                        ]
                    ]
                ]
            ]
        );

        $resource = new AccountingResource($mockHttpClient, 'users/clients', Client::class, ClientList::class);

        try {
            $resource->get($this->accountId, $clientId);
            $this->fail('FreshBooksException was not thrown');
        } catch (FreshBooksException $e) {
            $this->assertSame('Client not found.', $e->getMessage());
            $this->assertSame(404, $e->getCode());
            $this->assertSame(1012, $e->getErrorCode());
            $this->assertSame(
                [
                    [
                        'object' => 'client',
                        'message' => 'Client not found.',
                        'value' => '12345',
                        'field' => 'userid'
                    ]
                ],
                $e->getErrorDetails()
            );
        }
    }

    public function testList(): void
    {
        $clientId = 12345;
        $mockHttpClient = $this->getMockHttpClient(
            200,
            ['response' => ['result' => [
                'clients' => [['id' => $clientId]],
                'page' => 1,
                'per_page' => 15,
                'pages' => 1,
                'total' => 1
            ]]]
        );

        $resource = new AccountingResource($mockHttpClient, 'users/clients', Client::class, ClientList::class);
        $clients = $resource->list($this->accountId);

        $this->assertSame($clientId, $clients->clients[0]->id);
        $this->assertSame(1, $clients->page);
        $this->assertSame(15, $clients->perPage);
        $this->assertSame(1, $clients->pages);
        $this->assertSame(1, $clients->total);

        $request = $mockHttpClient->getLastRequest();
        $this->assertSame('GET', $request->getMethod());
        $this->assertSame('/accounting/account/ACM123/users/clients', $request->getRequestTarget());
    }

    public function testListNoRecords(): void
    {
        $mockHttpClient = $this->getMockHttpClient(
            200,
            ['response' => ['result' => [
                'clients' => [],
                'page' => 1,
                'per_page' => 15,
                'pages' => 0,
                'total' => 0
            ]]]
        );

        $resource = new AccountingResource($mockHttpClient, 'users/clients', Client::class, ClientList::class);
        $clients = $resource->list($this->accountId);

        $this->assertSame([], $clients->clients);
        $this->assertSame(1, $clients->page);
        $this->assertSame(15, $clients->perPage);
        $this->assertSame(0, $clients->pages);
        $this->assertSame(0, $clients->total);

        $request = $mockHttpClient->getLastRequest();
        $this->assertSame('GET', $request->getMethod());
        $this->assertSame('/accounting/account/ACM123/users/clients', $request->getRequestTarget());
    }


    public function testListPaged(): void
    {
        $mockHttpClient = $this->getMockHttpClient(
            200,
            ['response' => ['result' => [
                'clients' => [['id' => 12345]],
                'page' => 1,
                'per_page' => 1,
                'pages' => 2,
                'total' => 2
            ]]]
        );

        $resource = new AccountingResource($mockHttpClient, 'users/clients', Client::class, ClientList::class);
        $pages = new PaginateBuilder(1, 2);
        $clients = $resource->list($this->accountId, [$pages]);

        $request = $mockHttpClient->getLastRequest();
        $this->assertSame('GET', $request->getMethod());
        $this->assertSame('/accounting/account/ACM123/users/clients?page=1&per_page=2', $request->getRequestTarget());
    }

    public function testListFiltered(): void
    {
        $mockHttpClient = $this->getMockHttpClient(
            200,
            ['response' => ['result' => [
                'clients' => [['id' => 12345]],
                'page' => 1,
                'per_page' => 15,
                'pages' => 1,
                'total' => 1
            ]]]
        );

        $resource = new AccountingResource($mockHttpClient, 'users/clients', Client::class, ClientList::class);
        $filters = (new FilterBuilder())->equals('clientid', 12345);
        $clients = $resource->list($this->accountId, [$filters]);

        $request = $mockHttpClient->getLastRequest();
        $this->assertSame('GET', $request->getMethod());
        $this->assertSame(
            '/accounting/account/ACM123/users/clients?search%5Bclientid%5D=12345',
            $request->getRequestTarget()
        );
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

        $this->assertSame($clientId, $client->id);
        $this->assertSame('FreshBooks', $client->organization);

        $request = $mockHttpClient->getLastRequest();
        $this->assertSame('POST', $request->getMethod());
        $this->assertSame('/accounting/account/ACM123/users/clients', $request->getRequestTarget());
    }

    public function testCreateByData(): void
    {
        $clientId = 12345;
        $mockHttpClient = $this->getMockHttpClient(
            200,
            ['response' => ['result' => ['client' => ['id' => $clientId, 'organization' => 'FreshBooks']]]]
        );
        $data = array('organization' => 'FreshBooks');

        $resource = new AccountingResource($mockHttpClient, 'users/clients', Client::class, ClientList::class);
        $client = $resource->create($this->accountId, data: $data);

        $this->assertSame($clientId, $client->id);
        $this->assertSame('FreshBooks', $client->organization);

        $request = $mockHttpClient->getLastRequest();
        $this->assertSame('POST', $request->getMethod());
        $this->assertSame('/accounting/account/ACM123/users/clients', $request->getRequestTarget());
    }

    public function testCreateWithIncludes(): void
    {
        $clientId = 12345;
        $mockHttpClient = $this->getMockHttpClient(
            200,
            ['response' => ['result' => ['client' => ['id' => $clientId, 'organization' => 'FreshBooks']]]]
        );
        $model = new Client();
        $model->organization = 'FreshBooks';

        $resource = new AccountingResource($mockHttpClient, 'users/clients', Client::class, ClientList::class);
        $includes = (new IncludesBuilder())->include('late_reminders');
        $client = $resource->create($this->accountId, model: $model, includes: $includes);

        $this->assertSame($clientId, $client->id);
        $this->assertSame('FreshBooks', $client->organization);

        $request = $mockHttpClient->getLastRequest();
        $this->assertSame('POST', $request->getMethod());
        $this->assertSame(
            '/accounting/account/ACM123/users/clients?include%5B%5D=late_reminders',
            $request->getRequestTarget()
        );
    }

    public function testUpdateByModel(): void
    {
        $clientId = 12345;
        $mockHttpClient = $this->getMockHttpClient(
            200,
            ['response' => ['result' => ['client' => ['id' => $clientId, 'organization' => 'FreshBooks']]]]
        );
        $model = new Client();
        $model->organization = 'FreshBooks';

        $resource = new AccountingResource($mockHttpClient, 'users/clients', Client::class, ClientList::class);
        $client = $resource->update($this->accountId, $clientId, model: $model);

        $this->assertSame($clientId, $client->id);
        $this->assertSame('FreshBooks', $client->organization);

        $request = $mockHttpClient->getLastRequest();
        $this->assertSame('PUT', $request->getMethod());
        $this->assertSame('/accounting/account/ACM123/users/clients/12345', $request->getRequestTarget());
    }

    public function testUpdateByData(): void
    {
        $clientId = 12345;
        $mockHttpClient = $this->getMockHttpClient(
            200,
            ['response' => ['result' => ['client' => ['id' => $clientId, 'organization' => 'FreshBooks']]]]
        );
        $data = array('organization' => 'FreshBooks');

        $resource = new AccountingResource($mockHttpClient, 'users/clients', Client::class, ClientList::class);
        $client = $resource->update($this->accountId, $clientId, data: $data);

        $this->assertSame($clientId, $client->id);
        $this->assertSame('FreshBooks', $client->organization);

        $request = $mockHttpClient->getLastRequest();
        $this->assertSame('PUT', $request->getMethod());
        $this->assertSame('/accounting/account/ACM123/users/clients/12345', $request->getRequestTarget());
    }

    public function testUpdateWithIncludes(): void
    {
        $clientId = 12345;
        $mockHttpClient = $this->getMockHttpClient(
            200,
            ['response' => ['result' => ['client' => ['id' => $clientId, 'organization' => 'FreshBooks']]]]
        );
        $model = new Client();
        $model->organization = 'FreshBooks';

        $resource = new AccountingResource($mockHttpClient, 'users/clients', Client::class, ClientList::class);
        $includes = (new IncludesBuilder())->include('late_reminders');
        $client = $resource->update($this->accountId, $clientId, model: $model, includes: $includes);

        $this->assertSame($clientId, $client->id);
        $this->assertSame('FreshBooks', $client->organization);

        $request = $mockHttpClient->getLastRequest();
        $this->assertSame('PUT', $request->getMethod());
        $this->assertSame(
            '/accounting/account/ACM123/users/clients/12345?include%5B%5D=late_reminders',
            $request->getRequestTarget()
        );
    }

    public function testDeleteViaUpdate(): void
    {
        $clientId = 12345;
        $mockHttpClient = $this->getMockHttpClient(
            200,
            ['response' => ['result' => ['client' => ['id' => $clientId, 'vis_state' => 1]]]]
        );
        $resource = new AccountingResource($mockHttpClient, 'users/clients', Client::class, ClientList::class);
        $client = $resource->delete($this->accountId, $clientId);

        $this->assertSame($clientId, $client->id);
        $this->assertSame(VisState::DELETED, $client->visState);

        $request = $mockHttpClient->getLastRequest();
        $this->assertSame('PUT', $request->getMethod());
        $this->assertSame('/accounting/account/ACM123/users/clients/12345', $request->getRequestTarget());
    }

    public function testDeleteViaDelete(): void
    {
        $clientId = 12345;
        $mockHttpClient = $this->getMockHttpClient(
            200,
            ['response' => []]
        );
        $resource = new AccountingResource(
            $mockHttpClient,
            'users/clients',
            Client::class,
            ClientList::class,
            deleteViaUpdate: false
        );
        $client = $resource->delete($this->accountId, $clientId);

        $this->assertSame(null, $client);
        $request = $mockHttpClient->getLastRequest();
        $this->assertSame('DELETE', $request->getMethod());
        $this->assertSame('/accounting/account/ACM123/users/clients/12345', $request->getRequestTarget());
    }

    public function testResourceMissingCalls(): void
    {
        $clientId = 12345;
        $mockHttpClient = $this->getMockHttpClient(
            200,
            ['response' => ['result' => ['client' => ['id' => $clientId]]]]
        );

        $resource = new AccountingResource(
            $mockHttpClient,
            'users/clients',
            Client::class,
            ClientList::class,
            missingEndpoints: ['get', 'update', 'delete']
        );

        $this->expectException(FreshBooksNotImplementedException::class);
        $this->expectExceptionMessage("The method 'get' does not exist for users/clients");

        $resource->get($this->accountId, $clientId);
    }
}
