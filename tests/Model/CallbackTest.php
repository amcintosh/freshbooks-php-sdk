<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Tests\Model;

use DateTime;
use PHPUnit\Framework\TestCase;
use amcintosh\FreshBooks\Model\Callback;

final class CallbackTest extends TestCase
{
    private $sampleCallbackData = '{"callback":{
        "callbackid": 123,
        "verified": true,
        "uri": "http://freshbooks.com/hook/123",
        "event": "invoice.create",
        "updated_at": "2017-08-23T11:45:09Z"}}';

    public function testCallbackFromResponse(): void
    {
        $callbackData = json_decode($this->sampleCallbackData, true);

        $callback = new Callback($callbackData[Callback::RESPONSE_FIELD]);

        $this->assertSame(123, $callback->callbackId);
        $this->assertSame('invoice.create', $callback->event);
        $this->assertEquals(new DateTime('2017-08-23T11:45:09Z'), $callback->updatedAt);
        $this->assertSame('http://freshbooks.com/hook/123', $callback->uri);
        $this->assertTrue($callback->verified);
    }

    public function testCallbackGetContent(): void
    {
        $callbackData = json_decode($this->sampleCallbackData, true);
        $callback = new Callback($callbackData['callback']);
        $this->assertSame([
            'event' => 'invoice.create',
            'uri' => 'http://freshbooks.com/hook/123'
        ], $callback->getContent());
    }
}
