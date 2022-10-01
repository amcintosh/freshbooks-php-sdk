<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Tests\Model;

use DateTime;
use Spryker\DecimalObject\Decimal;
use PHPUnit\Framework\TestCase;
use amcintosh\FreshBooks\Model\Expense;
use amcintosh\FreshBooks\Model\ExpenseStatus;
use amcintosh\FreshBooks\Model\VisState;

final class ExpenseTest extends TestCase
{
    private $sampleExpenseData = '{"expense": {
        "account_name": "",
        "accountid": null,
        "accounting_systemid": "ACM123",
        "amount": {
            "amount": "54.00",
            "code": "CAD"
        },
        "attachment": {
            "attachmentid": 7788,
            "id": 7788,
            "jwt": "someAwesomeJWT",
            "media_type": "image/jpeg"
        },
        "background_jobid": null,
        "bank_name": "ABC Bank",
        "bill_matches": [],
        "billable": false,
        "categoryid": 654654,
        "clientid": 0,
        "compounded_tax": false,
        "converse_projectid": null,
        "date": "2021-07-19",
        "expenseid": 23456,
        "ext_accountid": null,
        "ext_invoiceid": 0,
        "ext_systemid": 0,
        "from_bulk_import": false,
        "has_receipt": false,
        "id": 23456,
        "include_receipt": false,
        "invoiceid": null,
        "is_cogs": false,
        "isduplicate": false,
        "markup_percent": "0",
        "modern_projectid": null,
        "notes": "For lunch",
        "potential_bill_payment": false,
        "profileid": 6541,
        "projectid": 0,
        "staffid": 1,
        "status": 0,
        "taxAmount1": {
            "amount": "6.21",
            "code": "CAD"
        },
        "taxAmount2": null,
        "taxName1": "HST1",
        "taxName2": null,
        "taxPercent1": null,
        "taxPercent2": null,
        "transactionid": null,
        "updated": "2021-07-19 04:42:03",
        "vendor": "Serano Bakery",
        "vis_state": 0
    }}';

    public function testExpenseFromResponse(): void
    {
        $expenseData = json_decode($this->sampleExpenseData, true);

        $expense = new Expense($expenseData[Expense::RESPONSE_FIELD]);

        $this->assertSame(23456, $expense->id);
        $this->assertSame(23456, $expense->expenseId);
        $this->assertSame('ACM123', $expense->accountingSystemId);
        $this->assertSame(null, $expense->accountId);
        $this->assertSame('', $expense->accountName);
        $this->assertEquals(Decimal::create('54.00'), $expense->amount->amount);
        $this->assertSame('CAD', $expense->amount->code);
        $this->assertSame('ABC Bank', $expense->bankName);
        $this->assertSame(false, $expense->billable);
        $this->assertSame(654654, $expense->categoryId);
        $this->assertSame(0, $expense->clientId);
        $this->assertEquals(new DateTime('2021-07-19T00:00:00Z'), $expense->date);
        $this->assertSame(null, $expense->extAccountId);
        $this->assertSame(0, $expense->extInvoiceId);
        $this->assertSame(0, $expense->extSystemId);
        $this->assertSame(false, $expense->fromBulkImport);
        $this->assertSame(false, $expense->hasReceipt);
        $this->assertSame(false, $expense->includeReceipt);
        $this->assertSame(null, $expense->invoiceId);
        $this->assertSame(false, $expense->isCogs);
        $this->assertSame(false, $expense->isDuplicate);
        $this->assertSame('0', $expense->markupPercent);
        $this->assertSame(null, $expense->modernProjectId);
        $this->assertSame('For lunch', $expense->notes);
        $this->assertSame(false, $expense->potentialBillPayment);
        $this->assertSame(6541, $expense->profileId);
        $this->assertSame(0, $expense->projectId);
        $this->assertSame(1, $expense->staffId);
        $this->assertSame(0, $expense->status);
        $this->assertSame(ExpenseStatus::INTERNAL, $expense->status);
        $this->assertEquals(Decimal::create('6.21'), $expense->taxAmount1->amount);
        $this->assertEquals('CAD', $expense->taxAmount1->code);
        $this->assertSame(null, $expense->taxAmount2);
        $this->assertSame('HST1', $expense->taxName1);
        $this->assertSame(null, $expense->taxName2);
        $this->assertSame(null, $expense->taxPercent1);
        $this->assertSame(null, $expense->taxPercent2);
        $this->assertSame(null, $expense->transactionId);
        $this->assertEquals(new DateTime('2021-07-19T08:42:03Z'), $expense->updated);
        $this->assertSame('Serano Bakery', $expense->vendor);
        $this->assertSame(VisState::ACTIVE, $expense->visState);
        $this->assertSame(7788, $expense->attachment->id);
        $this->assertSame(7788, $expense->attachment->attachmentId);
        $this->assertSame('someAwesomeJWT', $expense->attachment->jwt);
        $this->assertSame('image/jpeg', $expense->attachment->mediaType);
    }

    public function testExpenseGetContent(): void
    {
        $expenseData = json_decode($this->sampleExpenseData, true);
        $expense = new Expense($expenseData['expense']);
        $this->assertSame([
            'account_name' => '',
            'amount' => [
                'amount' => '54.00',
                'code' => 'CAD',
            ],
            'bank_name' => 'ABC Bank',
            'billable' => false,
            'categoryid' => 654654,
            'clientid' => 0,
            'date' => '2021-07-19',
            'ext_invoiceid' => 0,
            'ext_systemid' => 0,
            'has_receipt' => false,
            'include_receipt' => false,
            'is_cogs' => false,
            'isduplicate' => false,
            'markup_percent' => '0',
            'notes' => 'For lunch',
            'potential_bill_payment' => false,
            'projectid' => 0,
            'staffid' => 1,
            'taxAmount1' => [
                'amount' => '6.21',
                'code' => 'CAD',
            ],
            'taxName1' => 'HST1',
            'vendor' => 'Serano Bakery',
            'attachment' => [
                'jwt' => 'someAwesomeJWT',
                'media_type' => 'image/jpeg'
            ]
        ], $expense->getContent());
    }
}
