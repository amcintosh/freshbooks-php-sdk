<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Tests\Model;

use amcintosh\FreshBooks\Model\Bill;
use DateTime;
use PHPUnit\Framework\TestCase;
use Spryker\DecimalObject\Decimal;

class BillTest extends TestCase
{
    private string $sampleBillData = '{"bill":{
        "amount":{
            "amount":"1500.00",
            "code":"EUR"
        },
        "attachment":null,
        "bill_number":"BN101",
        "bill_payments":[],
        "created_at":"2021-01-28 15:06:32",
        "currency_code":"EUR",
        "due_date":"2021-02-27",
        "due_offset_days":30,
        "id":1141,
        "issue_date":"2021-01-28",
        "language":"en",
        "lines":[
            {
                "amount":{
                    "amount":"1500.00",
                    "code":"EUR"
                },
                "category":{
                    "category":"Equipment",
                    "categoryid":3696445,
                    "created_at":"2020-12-01 08:16:47",
                    "id":3696445,
                    "is_cogs":false,
                    "is_editable":false,
                    "parentid":3696439,
                    "updated_at":"2020-12-01 08:16:47",
                    "vis_state":0
                },
                "description":"Raw material",
                "id":2621,
                "list_index":1,
                "quantity":"15",
                "tax_amount1":null,
                "tax_amount2":null,
                "tax_authorityid1":null,
                "tax_authorityid2":null,
                "tax_name1":null,
                "tax_name2":null,
                "tax_percent1":null,
                "tax_percent2":null,
                "total_amount":{
                    "amount":"1500.00",
                    "code":"EUR"
                },
                "unit_cost":{
                    "amount":"100.00",
                    "code":"EUR"
                }
            }
        ],
        "outstanding":{
            "amount":"1500.00",
            "code":"EUR"
        },
        "overall_category":"Equipment",
        "overall_description":"Raw material",
        "paid":{
            "amount":"0.00",
            "code":"EUR"
        },
        "status":"unpaid",
        "tax_amount":{
            "amount":"0.00",
            "code":"EUR"
        },
        "total_amount":{
            "amount":"1500.00",
            "code":"EUR"
        },
        "updated_at":"2021-01-28 15:06:53",
        "vis_state":0
    }}';

    public function testBillFromResponse(): void
    {
        $billData = json_decode($this->sampleBillData, true);

        $bill = new Bill($billData[Bill::RESPONSE_FIELD]);

        $this->assertEquals(Decimal::create('1500.00'), $bill->amount->amount);
        $this->assertSame('EUR', $bill->amount->code);
        $this->assertSame(null, $bill->attachment);
        $this->assertSame('BN101', $bill->billNumber);
        $this->assertEquals([], $bill->billPayments);
        $this->assertEquals(new DateTime('2021-01-28T15:06:32Z'), $bill->createdAt);
        $this->assertSame('EUR', $bill->currencyCode);
        $this->assertEquals(new DateTime('2021-02-27'), $bill->dueDate);
        $this->assertSame(30, $bill->dueOffsetDays);
        $this->assertSame(1141, $bill->id);
        $this->assertEquals(new DateTime('2021-01-28'), $bill->issueDate);
        $this->assertSame('en', $bill->language);
        $this->assertEquals(Decimal::create('1500.00'), $bill->outstanding->amount);
        $this->assertSame('EUR', $bill->outstanding->code);
        $this->assertSame('Equipment', $bill->overallCategory);
        $this->assertSame('Raw material', $bill->overallDescription);
        $this->assertEquals(Decimal::create('0.00'), $bill->paid->amount);
        $this->assertSame('EUR', $bill->paid->code);
        $this->assertSame('unpaid', $bill->status);
        $this->assertEquals(Decimal::create('0.00'), $bill->taxAmount->amount);
        $this->assertSame('EUR', $bill->taxAmount->code);
        $this->assertEquals(Decimal::create('1500.00'), $bill->totalAmount->amount);
        $this->assertSame('EUR', $bill->totalAmount->code);
        $this->assertEquals(new DateTime('2021-01-28T15:06:53Z'), $bill->updatedAt);
        $this->assertSame(0, $bill->visState);

        // Lines
        $this->assertEquals(Decimal::create('1500.00'), $bill->lines[0]->amount->amount);
        $this->assertSame('EUR', $bill->lines[0]->amount->code);
        $this->assertSame('Equipment', $bill->lines[0]->category->category);
        $this->assertSame(3696445, $bill->lines[0]->category->categoryId);
        $this->assertEquals(new DateTime('2020-12-01T13:16:47Z'), $bill->lines[0]->category->createdAt);
        $this->assertSame(3696445, $bill->lines[0]->category->id);
        $this->assertSame(false, $bill->lines[0]->category->isCogs);
        $this->assertSame(false, $bill->lines[0]->category->isEditable);
        $this->assertSame(3696439, $bill->lines[0]->category->parentId);
        $this->assertEquals(new DateTime('2020-12-01T13:16:47Z'), $bill->lines[0]->category->updatedAt);
        $this->assertSame(0, $bill->lines[0]->category->visState);
        $this->assertSame('Raw material', $bill->lines[0]->description);
        $this->assertSame(2621, $bill->lines[0]->id);
        $this->assertSame(1, $bill->lines[0]->listIndex);
        $this->assertSame(15, $bill->lines[0]->quantity);
        $this->assertSame(null, $bill->lines[0]->taxAmount1);
        $this->assertSame(null, $bill->lines[0]->taxAmount2);
        $this->assertSame(null, $bill->lines[0]->taxAuthorityId1);
        $this->assertSame(null, $bill->lines[0]->taxAuthorityId2);
        $this->assertSame(null, $bill->lines[0]->taxName1);
        $this->assertSame(null, $bill->lines[0]->taxName2);
        $this->assertSame(null, $bill->lines[0]->taxPercent1);
        $this->assertSame(null, $bill->lines[0]->taxPercent2);
        $this->assertEquals(Decimal::create('1500.00'), $bill->lines[0]->totalAmount->amount);
        $this->assertSame('EUR', $bill->lines[0]->totalAmount->code);
        $this->assertEquals(Decimal::create('100.00'), $bill->lines[0]->unitCost->amount);
        $this->assertSame('EUR', $bill->lines[0]->unitCost->code);
    }
}
