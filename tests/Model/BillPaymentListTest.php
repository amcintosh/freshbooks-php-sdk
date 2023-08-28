<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Tests\Model;

use amcintosh\FreshBooks\Model\BillPaymentList;
use DateTime;
use PHPUnit\Framework\TestCase;
use Spryker\DecimalObject\Decimal;

class BillPaymentListTest extends TestCase
{
    private string $samplePaymentsData = '{
        "bill_payments": [
            {
                "amount": {
                    "amount": "23000.00",
                    "code": "USD"
                },
                "billid": 1626,
                "id": 1490,
                "matched_with_expense": false,
                "note": "Some note",
                "paid_date": "2021-06-16",
                "payment_type": "Cash",
                "vis_state": 0
            }
        ]
    }';

    public function testBillPaymentListFromResponse(): void
    {
        $paymentsData = json_decode($this->samplePaymentsData, true);

        $payments = new BillPaymentList($paymentsData);

        $this->assertEquals(Decimal::create('23000.00'), $payments->billsPayments[0]->amount->amount);
        $this->assertSame('USD', $payments->billsPayments[0]->amount->code);
        $this->assertSame(1626, $payments->billsPayments[0]->billId);
        $this->assertSame(1490, $payments->billsPayments[0]->id);
        $this->assertSame(false, $payments->billsPayments[0]->matchedWithExpense);
        $this->assertSame('Some note', $payments->billsPayments[0]->note);
        $this->assertEquals(new DateTime('2021-06-16'), $payments->billsPayments[0]->paidDate);
        $this->assertSame('Cash', $payments->billsPayments[0]->paymentType);
        $this->assertSame(0, $payments->billsPayments[0]->visState);
    }
}
