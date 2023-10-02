<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Tests\Resource;

use PHPUnit\Framework\TestCase;
use amcintosh\FreshBooks\Exception\FreshBooksException;
use amcintosh\FreshBooks\Model\Callback;
use amcintosh\FreshBooks\Model\CallbackList;
use amcintosh\FreshBooks\Resource\EventsResource;
use amcintosh\FreshBooks\Tests\Resource\BaseResourceTest;

final class EventsResourceTest extends TestCase
{
    use BaseResourceTest;

    public string $accountId;

    protected function setUp(): void
    {
        $this->accountId = 'ACM123';
    }

    public function testGet(): void
    {
        $callbackId = 12345;
        $mockHttpClient = $this->getMockHttpClient(
            200,
            ['response' => ['result' => ['callback' => ['callbackid' => $callbackId]]]]
        );

        $resource = new EventsResource($mockHttpClient, 'events/callbacks', Callback::class, CallbackList::class);
        $callback = $resource->get($this->accountId, $callbackId);

        $this->assertSame($callbackId, $callback->callbackId);

        $request = $mockHttpClient->getLastRequest();
        $this->assertSame('GET', $request->getMethod());
        $this->assertSame('/events/account/ACM123/events/callbacks/12345', $request->getRequestTarget());
    }

    public function testGetNotFoundError(): void
    {
        $callbackId = 12345;
        $mockHttpClient = $this->getMockHttpClient(
            404,
            [
                'code' => 5,
                'message' => 'Requested resource could not be found.',
                'details' => [
                    [
                        '@type' => 'type.googleapis.com/google.rpc.Help',
                        'links' => [
                            'description' => 'API Documentation',
                            'url' => 'https://www.freshbooks.com/api/webhooks',
                        ]
                    ]
                ]
            ]
        );

        $resource = new EventsResource($mockHttpClient, 'events/callbacks', Callback::class, CallbackList::class);

        try {
            $resource->get($this->accountId, $callbackId);
            $this->fail('FreshBooksException was not thrown');
        } catch (FreshBooksException $e) {
            $this->assertSame('Requested resource could not be found.', $e->getMessage());
            $this->assertSame(404, $e->getCode());
            $this->assertNull($e->getErrorCode());
            $this->assertSame([], $e->getErrorDetails());
        }
    }

    public function testCreateValidationError(): void
    {
        $callbackId = 12345;
        $mockHttpClient = $this->getMockHttpClient(
            400,
            [
                'code' => 3,
                'message' => 'Invalid data in this request.',
                'details' => [
                    [
                        '@type' => 'type.googleapis.com/google.rpc.BadRequest',
                        'fieldViolations' => [
                            [
                                'field' => 'event',
                                'description' => 'Value error, Unrecognized event.'
                            ]
                        ]
                    ],
                    [
                        '@type' => 'type.googleapis.com/google.rpc.Help',
                        'links' => [
                            'description' => 'API Documentation',
                            'url' => 'https://www.freshbooks.com/api/webhooks',
                        ]
                    ]
                ]
            ]
        );

        $resource = new EventsResource($mockHttpClient, 'events/callbacks', Callback::class, CallbackList::class);

        try {
            $resource->create($this->accountId, data: []);
            $this->fail('FreshBooksException was not thrown');
        } catch (FreshBooksException $e) {
            $this->assertSame('Invalid data in this request.', $e->getMessage());
            $this->assertSame(400, $e->getCode());
            $this->assertNull($e->getErrorCode());
            $this->assertSame([[
                'field' => 'event',
                'description' => 'Value error, Unrecognized event.'
            ]], $e->getErrorDetails());
        }
    }
}
