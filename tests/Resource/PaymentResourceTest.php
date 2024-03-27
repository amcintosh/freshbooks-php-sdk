<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Tests\Resource;

use PHPUnit\Framework\TestCase;
use amcintosh\FreshBooks\Exception\FreshBooksException;
use amcintosh\FreshBooks\Model\InvoicePaymentOptions;
use amcintosh\FreshBooks\Resource\PaymentResource;
use amcintosh\FreshBooks\Tests\Resource\BaseResourceTest;

final class PaymentResourceTest extends TestCase
{
    use BaseResourceTest;

    public string $accountId;

    protected function setUp(): void
    {
        $this->accountId = 'ACM123';
    }

    public function testDefaults(): void
    {
        $mockHttpClient = $this->getMockHttpClient(
            200,
            ['payment_options' => [
                'entity_id' => null,
                'gateway_name' => 'Stripe'
            ]]
        );

        $resource = new PaymentResource(
            $mockHttpClient,
            'invoice',
            InvoicePaymentOptions::class,
            subResourcePath: 'payment_options',
            defaultsPath: 'payment_options',
            staticPathParams: 'entity_type=invoice',
        );
        $paymentOption = $resource->defaults($this->accountId);

        $this->assertSame('Stripe', $paymentOption->gatewayName);
        $this->assertNull($paymentOption->entityId);

        $request = $mockHttpClient->getLastRequest();
        $this->assertSame('GET', $request->getMethod());
        $this->assertSame('/payments/account/ACM123/payment_options?entity_type=invoice', $request->getRequestTarget());
    }

    public function testGet(): void
    {
        $invoiceId = 12345;
        $mockHttpClient = $this->getMockHttpClient(
            200,
            ['payment_options' => [
                'entity_id' => $invoiceId,
                'gateway_name' => 'Stripe'
            ]]
        );

        $resource = new PaymentResource(
            $mockHttpClient,
            'invoice',
            InvoicePaymentOptions::class,
            subResourcePath: 'payment_options',
            defaultsPath: 'payment_options',
            staticPathParams: 'entity_type=invoice',
        );
        $paymentOption = $resource->get($this->accountId, $invoiceId);

        $this->assertSame('Stripe', $paymentOption->gatewayName);
        $this->assertSame("{$invoiceId}", $paymentOption->entityId);

        $request = $mockHttpClient->getLastRequest();
        $this->assertSame('GET', $request->getMethod());
        $this->assertSame('/payments/account/ACM123/invoice/12345/payment_options', $request->getRequestTarget());
    }

    public function testGetNotFoundError(): void
    {
        $invoiceId = 12345;
        $mockHttpClient = $this->getMockHttpClient(
            404,
            [
                'error_type' => 'not_found',
                'message' => 'Resource not found'
            ]
        );

        $resource = new PaymentResource(
            $mockHttpClient,
            'invoice',
            InvoicePaymentOptions::class,
            subResourcePath: 'payment_options',
            defaultsPath: 'payment_options',
            staticPathParams: 'entity_type=invoice',
        );

        try {
            $resource->get($this->accountId, $invoiceId);
            $this->fail('FreshBooksException was not thrown');
        } catch (FreshBooksException $e) {
            $this->assertSame('Resource not found', $e->getMessage());
            $this->assertSame(404, $e->getCode());
            $this->assertNull($e->getErrorCode());
            $this->assertNull($e->getErrorDetails());
        }
    }

    public function testCreateValidationError(): void
    {
        $invoiceId = 12345;
        $mockHttpClient = $this->getMockHttpClient(
            500,
            [
                'error_type' => 'internal',
                'message' => 'An error has occurred'
            ]
        );

        $resource = new PaymentResource(
            $mockHttpClient,
            'invoice',
            InvoicePaymentOptions::class,
            subResourcePath: 'payment_options',
            defaultsPath: 'payment_options',
            staticPathParams: 'entity_type=invoice',
        );

        try {
            $resource->create($this->accountId, $invoiceId, data: []);
            $this->fail('FreshBooksException was not thrown');
        } catch (FreshBooksException $e) {
            $this->assertSame('An error has occurred', $e->getMessage());
            $this->assertSame(500, $e->getCode());
            $this->assertNull($e->getErrorCode());
            $this->assertNull($e->getErrorDetails());
        }
    }
}
