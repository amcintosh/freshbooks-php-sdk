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
                'errno' => 404,
                'message' => 'Requested resource could not be found.'
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
                'errno' => 3,
                'message' => 'The request was well-formed but was unable to be followed due to semantic errors.',
                'details' => [
                    'event: Value error, Unrecognized event.'
                ]
            ]
        );

        $resource = new EventsResource($mockHttpClient, 'events/callbacks', Callback::class, CallbackList::class);

        try {
            $resource->create($this->accountId, data: []);
            $this->fail('FreshBooksException was not thrown');
        } catch (FreshBooksException $e) {
            $this->assertSame('The request was well-formed but was unable to be followed due '
                . 'to semantic errors.', $e->getMessage());
            $this->assertSame(400, $e->getCode());
            $this->assertNull($e->getErrorCode());
            $this->assertSame(['event: Value error, Unrecognized event.'], $e->getErrorDetails());
        }
    }

    public function testVerify(): void
    {
        $callbackId = 12345;
        $mockHttpClient = $this->getMockHttpClient(
            200,
            ['response' => ['result' => ['callback' => [
                'callbackid' => $callbackId,
                'verified' => true
            ]]]]
        );

        $resource = new EventsResource($mockHttpClient, 'events/callbacks', Callback::class, CallbackList::class);
        $callback = $resource->verify($this->accountId, $callbackId, 'some_verifier');

        $this->assertSame($callbackId, $callback->callbackId);

        $request = $mockHttpClient->getLastRequest();
        $this->assertSame('PUT', $request->getMethod());
        $this->assertSame('/events/account/ACM123/events/callbacks/12345', $request->getRequestTarget());
    }

    public function testResend(): void
    {
        $callbackId = 12345;
        $mockHttpClient = $this->getMockHttpClient(
            200,
            ['response' => ['result' => ['callback' => [
                'callbackid' => $callbackId,
                'verified' => true
            ]]]]
        );

        $resource = new EventsResource($mockHttpClient, 'events/callbacks', Callback::class, CallbackList::class);
        $callback = $resource->resendVerification($this->accountId, $callbackId);

        $this->assertSame($callbackId, $callback->callbackId);

        $request = $mockHttpClient->getLastRequest();
        $this->assertSame('PUT', $request->getMethod());
        $this->assertSame('/events/account/ACM123/events/callbacks/12345', $request->getRequestTarget());
    }
}
