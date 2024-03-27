<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Tests\Model;

use PHPUnit\Framework\TestCase;
use amcintosh\FreshBooks\Model\InvoicePaymentOptions;

final class InvoicePaymentOptionsTest extends TestCase
{
    private $samplePaymentOptionsData = '{"payment_options":{
        "gateway_name": "Stripe",
        "has_credit_card": true,
        "has_ach_transfer": true,
        "has_bacs_debit": false,
        "has_sepa_debit": false,
        "has_acss_debit": false,
        "stripe_acss_payment_options": null,
        "has_paypal_smart_checkout": false,
        "allow_partial_payments": false,
        "entity_type": "invoice",
        "entity_id": "12345",
        "gateway_info": {
            "id": "abcdef",
            "account_id": "210000012",
            "country": "CA",
            "user_publishable_key": null,
            "currencies": [
                "CAD"
            ],
            "bank_transfer_enabled": false,
            "gateway_name": "fbpay",
            "can_process_payments": true
        }
    }}';

    public function testInvoicePaymentOptionsFromResponse(): void
    {
        $paymentOptionsData = json_decode($this->samplePaymentOptionsData, true);
        $paymentOptions = new InvoicePaymentOptions($paymentOptionsData[InvoicePaymentOptions::RESPONSE_FIELD]);

        $this->assertSame('12345', $paymentOptions->entityId);
        $this->assertSame('invoice', $paymentOptions->entityType);
        $this->assertSame('Stripe', $paymentOptions->gatewayName);
        $this->assertTrue($paymentOptions->hasCreditCard);
        $this->assertTrue($paymentOptions->hasAchTransfer);
        $this->assertFalse($paymentOptions->hasAcssDebit);
        $this->assertFalse($paymentOptions->hasBacsDebit);
        $this->assertFalse($paymentOptions->hasSepaDebit);
        $this->assertFalse($paymentOptions->hasPaypalSmartCheckout);
        $this->assertFalse($paymentOptions->allowPartialPayments);
    }

    public function testInvoicePaymentOptionsGetContent(): void
    {
        $paymentOptionsData = json_decode($this->samplePaymentOptionsData, true);
        $paymentOptions = new InvoicePaymentOptions($paymentOptionsData['payment_options']);
        $this->assertSame([
            'entity_id' => '12345',
            'entity_type' => 'invoice',
            'gateway_name' => 'Stripe',
            'has_credit_card' => true,
            'has_ach_transfer' => true,
            'allow_partial_payments' => false
        ], $paymentOptions->getContent());
    }
}
