<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Tests\Model;

use DateTime;
use Spryker\DecimalObject\Decimal;
use PHPUnit\Framework\TestCase;
use amcintosh\FreshBooks\Model\Payment;
use amcintosh\FreshBooks\Model\VisState;

final class PaymentTest extends TestCase
{
    private $samplePaymentData = '{"payment":{
        "accounting_systemid": "ACM123",
        "amount": {
            "amount": "41.94",
            "code": "CAD"
        },
        "bulk_paymentid": null,
        "clientid": 12345,
        "creditid": null,
        "date": "2021-04-16",
        "from_credit": false,
        "gateway": null,
        "id": 235435,
        "invoiceid": 987654,
        "logid": 235435,
        "note": "Some note",
        "orderid": null,
        "overpaymentid": null,
        "send_client_notification": null,
        "transactionid": null,
        "type": "Check",
        "updated": "2021-04-17 05:29:31",
        "vis_state": 0
    }}';

    public function testPaymentFromResponse(): void
    {
        $paymentData = json_decode($this->samplePaymentData, true);

        $payment = new Payment($paymentData[Payment::RESPONSE_FIELD]);

        $this->assertSame(235435, $payment->id);
        $this->assertSame(235435, $payment->paymentId);
        $this->assertSame('ACM123', $payment->accountingSystemId);
        $this->assertEquals(Decimal::create('41.94'), $payment->amount->amount);
        $this->assertSame('CAD', $payment->amount->code);
        $this->assertSame(null, $payment->bulkPaymentId);
        $this->assertSame(12345, $payment->clientId);
        $this->assertSame(null, $payment->creditId);
        $this->assertEquals(new DateTime('2021-04-16T00:00:00Z'), $payment->date);
        $this->assertSame(false, $payment->fromCredit);
        $this->assertSame(null, $payment->gateway);
        $this->assertSame(987654, $payment->invoiceId);
        $this->assertSame('Some note', $payment->note);
        $this->assertSame(null, $payment->orderId);
        $this->assertSame(null, $payment->overpaymentId);
        $this->assertSame(null, $payment->sendClientNotification);
        $this->assertSame('Check', $payment->type);
        $this->assertEquals(new DateTime('2021-04-17T09:29:31Z'), $payment->updated);
        $this->assertSame(VisState::ACTIVE, $payment->visState);
    }

    public function testClientGetContent(): void
    {
        $paymentData = json_decode($this->samplePaymentData, true);
        $payment = new Payment($paymentData['payment']);
        $this->assertSame([
            'amount' => [
                'amount' => '41.94',
                'code' => 'CAD'
            ],
            'from_credit' => false,
            'invoiceid' => 987654,
            'note' => 'Some note',
            'type' => 'Check',
            'date' => '2021-04-16'
        ], $payment->getContent());
    }
}
