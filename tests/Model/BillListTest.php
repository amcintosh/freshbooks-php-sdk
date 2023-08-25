<?php

namespace amcintosh\FreshBooks\Tests\Model;

use amcintosh\FreshBooks\Model\BillList;
use PHPUnit\Framework\TestCase;

class BillListTest extends TestCase
{
    private string $sampleBillsData = '{
        "bills":[
            {
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
            }
        ],
        "page":1,
        "pages":4,
        "per_page":1,
        "total":4
    }';

    public function testBillListFromResponse(): void
    {
        $billsData = json_decode($this->sampleBillsData, true);

        $bills = new BillList($billsData);

        $this->assertSame(1, $bills->pages()->page);
        $this->assertSame(4, $bills->pages()->pages);
        $this->assertSame(1, $bills->pages()->perPage);
        $this->assertSame(4, $bills->pages()->total);

        $this->assertSame('BN101', $bills->bills[0]->billNumber);
        $this->assertSame(2621, $bills->bills[0]->lines[0]->id);
        $this->assertSame(3696445, $bills->bills[0]->lines[0]->category->id);
    }
}
