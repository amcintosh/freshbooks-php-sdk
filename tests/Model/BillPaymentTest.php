<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Tests\Model;

use amcintosh\FreshBooks\Model\BillPayment;
use DateTime;
use PHPUnit\Framework\TestCase;
use Spryker\DecimalObject\Decimal;

class BillPaymentTest extends TestCase
{
    private string $sampleBillPaymentData = '{"bill_payment": {
        "amount": {
            "amount": "4000.00",
            "code": "USD"
        },
        "billid": 1626,
        "id": 1490,
        "matched_with_expense": false,
        "note": "Test Payment via API",
        "paid_date": "2021-06-16",
        "payment_type": "Check",
        "vis_state": 0
    }}';

    public function testBillPaymentFromResponse(): void
    {
        $paymentData = json_decode($this->sampleBillPaymentData, true);

        $payment = new BillPayment($paymentData[BillPayment::RESPONSE_FIELD]);

        $this->assertEquals(Decimal::create('4000.00'), $payment->amount->amount);
        $this->assertSame('USD', $payment->amount->code);
        $this->assertSame(1626, $payment->billId);
        $this->assertSame(1490, $payment->id);
        $this->assertSame(false, $payment->matchedWithExpense);
        $this->assertSame('Test Payment via API', $payment->note);
        $this->assertEquals(new DateTime('2021-06-16'), $payment->paidDate);
        $this->assertSame('Check', $payment->paymentType);
        $this->assertSame(0, $payment->visState);
    }
}
