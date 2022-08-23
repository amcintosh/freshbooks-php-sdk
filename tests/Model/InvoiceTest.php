<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Tests\Model;

use DateTime;
use DateTimeZone;
use Spryker\DecimalObject\Decimal;
use PHPUnit\Framework\TestCase;
use amcintosh\FreshBooks\Model\Invoice;
use amcintosh\FreshBooks\Model\InvoiceStatus;
use amcintosh\FreshBooks\Model\Money;
use amcintosh\FreshBooks\Model\VisState;

final class InvoiceTest extends TestCase
{
    private $sampleInvoiceData = '{"invoice": {
        "accountid": "ACM123",
        "accounting_systemid": "ACM123",
        "address": "",
        "amount": {
            "amount": "41.94",
            "code": "CAD"
        },
        "auto_bill": false,
        "autobill_status": null,
        "basecampid": 0,
        "city": "Toronto",
        "code": "M5T 2B3",
        "country": "Canada",
        "create_date": "2021-04-16",
        "created_at": "2021-04-16 10:31:19",
        "currency_code": "CAD",
        "current_organization": "Gordon Shumway",
        "customerid": 12345,
        "date_paid": "2021-04-16",
        "deposit_amount": {
            "amount": "1.00",
            "code": "CAD"
        },
        "deposit_percentage": "1.5",
        "deposit_status": "none",
        "description": "Melmac melamine resin molded dinnerware",
        "discount_description": null,
        "discount_total": {
            "amount": "-4.40",
            "code": "CAD"
        },
        "discount_value": 10,
        "display_status": "sent",
        "dispute_status": null,
        "due_date": "2021-04-16",
        "due_offset_days": 0,
        "estimateid": 0,
        "ext_archive": 0,
        "fname": "Gordon",
        "fulfillment_date": "2021-04-16",
        "generation_date": "2021-04-16",
        "gmail": false,
        "id": 987654,
        "invoice_number": "ACM0002",
        "invoiceid": 987654,
        "language": "en",
        "last_order_status": null,
        "lines": [
            {
                "amount": {
                    "amount": "20.00",
                    "code": "CAD"
                },
                "basecampid": 0,
                "compounded_tax": false,
                "date": null,
                "description": "Melmac melamine resin molded dinnerware",
                "expenseid": 0,
                "invoiceid": 987654,
                "lineid": 1,
                "modern_project_id": null,
                "modern_time_entries": [],
                "name": "Bowls",
                "qty": "4",
                "retainer_id": null,
                "retainer_period_id": null,
                "taskno": 1,
                "taxAmount1": "13",
                "taxAmount2": "0",
                "taxName1": "HST1",
                "taxName2": null,
                "taxNumber1": "RT",
                "taxNumber2": null,
                "type": 0,
                "unit_cost": {
                    "amount": "5.00",
                    "code": "CAD"
                },
                "updated": "2021-04-16 10:31:19"
            },
            {
                "amount": {
                    "amount": "24.00",
                    "code": "CAD"
                },
                "basecampid": 0,
                "compounded_tax": false,
                "date": null,
                "description": "Melmac melamine resin molded mug",
                "expenseid": 0,
                "invoiceid": 987654,
                "lineid": 2,
                "modern_project_id": null,
                "modern_time_entries": [],
                "name": "Mugs",
                "qty": "6",
                "retainer_id": null,
                "retainer_period_id": null,
                "taskno": 2,
                "taxAmount1": "0",
                "taxAmount2": "0",
                "taxName1": "",
                "taxName2": "",
                "taxNumber1": null,
                "taxNumber2": null,
                "type": 0,
                "unit_cost": {
                    "amount": "4.00",
                    "code": "CAD"
                },
                "updated": "2021-04-16 10:31:19"
            }
        ],
        "lname": "Shumway",
        "net_paid_amount": {
            "amount": "7.60133524082021936099189318",
            "code": "CAD"
        },
        "notes": "Thanks for your business",
        "organization": "Gordon Shumway",
        "outstanding": {
            "amount": "21.94",
            "code": "CAD"
        },
        "ownerid": 1,
        "paid": {
            "amount": "20.00",
            "code": "CAD"
        },
        "parent": 0,
        "payment_details": "",
        "payment_status": "partial",
        "po_number": null,
        "presentation": {
            "date_format": "dd/mm/yyyy",
            "description_heading": null,
            "hours_heading": null,
            "image_banner_position_y": 0,
            "image_banner_src": "/uploads/images/abc123",
            "image_logo_src": "/uploads/images/abc124",
            "invoiceid": 987654,
            "item_heading": null,
            "label": null,
            "quantity_heading": null,
            "rate_heading": null,
            "task_heading": null,
            "theme_font_name": "modern",
            "theme_layout": "simple",
            "theme_primary_color": "#663399",
            "time_entry_notes_heading": null,
            "unit_cost_heading": null
        },
        "province": "Ontario",
        "return_uri": null,
        "sentid": 1,
        "show_attachments": false,
        "status": 2,
        "street": "123 Huron St.",
        "street2": "",
        "template": "clean-grouped",
        "terms": null,
        "updated": "2021-04-16 10:31:58",
        "uuid": "ab1c33f1-8827-4a46-b335-75a6a4149db8",
        "v3_status": "partial",
        "vat_name": "VAT Number",
        "vat_number": "123",
        "version": "2021-04-16 10:31:58.480684",
        "vis_state": 0
    }}';

    public function testInvoiceFromResponse(): void
    {
        $invoiceData = json_decode($this->sampleInvoiceData, true);

        $invoice = new Invoice($invoiceData[Invoice::RESPONSE_FIELD]);

        $this->assertSame(987654, $invoice->id);
        $this->assertSame(987654, $invoice->invoiceId);
        $this->assertSame('ACM123', $invoice->accountingSystemId);
        $this->assertSame('ACM123', $invoice->accountId);
        $this->assertSame('', $invoice->address);
        $this->assertEquals(Decimal::create(41.94), $invoice->amount->amount);
        $this->assertSame('CAD', $invoice->amount->code);
        $this->assertSame(false, $invoice->autoBill);
        $this->assertSame(null, $invoice->autoBillStatus);
        $this->assertSame('Toronto', $invoice->city);
        $this->assertSame('M5T 2B3', $invoice->code);
        $this->assertSame('Canada', $invoice->country);
        $this->assertSame(12345, $invoice->clientId);
        $this->assertSame('CAD', $invoice->currencyCode);
        $this->assertSame('Gordon Shumway', $invoice->currentOrganization);
        $this->assertEquals(new DateTime('2021-04-16T00:00:00Z'), $invoice->createDate);
        $this->assertEquals(new DateTime('2021-04-16T14:31:19Z'), $invoice->createdAt);
        $this->assertEquals(new DateTime('2021-04-16T00:00:00Z'), $invoice->datePaid);
        $this->assertEquals(Decimal::create('1.00'), $invoice->depositAmount->amount);
        $this->assertSame('CAD', $invoice->depositAmount->code);
        $this->assertSame(null, $invoice->discountDescription);
        $this->assertSame('1.5', $invoice->depositPercentage);
        $this->assertSame('none', $invoice->depositStatus);
        $this->assertSame('Melmac melamine resin molded dinnerware', $invoice->description);
        $this->assertEquals(Decimal::create('-4.40'), $invoice->discountTotal->amount);
        $this->assertSame('CAD', $invoice->discountTotal->code);
        $this->assertSame(10.0, $invoice->discountValue);
        $this->assertSame('sent', $invoice->displayStatus);
        $this->assertEquals(new DateTime('2021-04-16T00:00:00Z'), $invoice->dueDate);
        $this->assertSame(0, $invoice->dueOffsetDays);
        $this->assertSame(0, $invoice->estimateId);
        $this->assertSame('Gordon', $invoice->firstName);
        $this->assertEquals(new DateTime('2021-04-16T00:00:00Z'), $invoice->generationDate);
        $this->assertSame(false, $invoice->groundMail);
        $this->assertSame('ACM0002', $invoice->invoiceNumber);
        $this->assertSame('en', $invoice->language);
        $this->assertSame(null, $invoice->lastOrderStatus);
        $this->assertSame('Shumway', $invoice->lastName);
        $this->assertSame('Gordon Shumway', $invoice->organization);
        $this->assertEquals(Decimal::create('21.94'), $invoice->outstanding->amount);
        $this->assertSame('CAD', $invoice->outstanding->code);
        $this->assertSame(1, $invoice->ownerId);
        $this->assertSame('Thanks for your business', $invoice->notes);
        $this->assertEquals(Decimal::create('20.00'), $invoice->paid->amount);
        $this->assertSame('CAD', $invoice->paid->code);
        $this->assertSame(0, $invoice->parent);
        $this->assertSame('partial', $invoice->paymentStatus);
        $this->assertSame(null, $invoice->PONumber);
        $this->assertSame('Ontario', $invoice->province);
        $this->assertSame(1, $invoice->sentId);
        $this->assertSame(false, $invoice->showAttachments);
        $this->assertSame('123 Huron St.', $invoice->street);
        $this->assertSame('', $invoice->street2);
        $this->assertSame(2, $invoice->status);
        $this->assertSame(InvoiceStatus::SENT, $invoice->status);
        $this->assertSame(null, $invoice->terms);
        $this->assertEquals(new DateTime('2021-04-16T14:31:58Z'), $invoice->updated);
        $this->assertSame('partial', $invoice->v3Status);
        $this->assertSame('VAT Number', $invoice->VATName);
        $this->assertSame('123', $invoice->VATNumber);
        $this->assertSame(VisState::ACTIVE, $invoice->visState);

        // Lines
        $this->assertEquals(2, count($invoice->lines));
        $line = $invoice->lines[0];
        $this->assertSame(1, $line->lineId);
        $this->assertEquals(Decimal::create('20.00'), $line->amount->amount);
        $this->assertSame('CAD', $line->amount->code);
        $this->assertSame('Melmac melamine resin molded dinnerware', $line->description);
        $this->assertSame(0, $line->expenseId);
        $this->assertSame('Bowls', $line->name);
        $this->assertSame(4.0, $line->quantity);
        $this->assertSame('13', $line->taxAmount1);
        $this->assertSame('0', $line->taxAmount2);
        $this->assertSame('HST1', $line->taxName1);
        $this->assertSame(null, $line->taxName2);
        $this->assertSame('RT', $line->taxNumber1);
        $this->assertSame(null, $line->taxNumber2);
        $this->assertSame(0, $line->type);
        $this->assertEquals(Decimal::create('5.00'), $line->unitCost->amount);
        $this->assertSame('CAD', $line->unitCost->code);
        $this->assertEquals(new DateTime('2021-04-16T14:31:19Z'), $line->updated);

        // Presentation
        $presentation = $invoice->presentation;
        $this->assertSame(987654, $presentation->invoiceId);
        $this->assertSame('dd/mm/yyyy', $presentation->dateFormat);
        $this->assertSame('/uploads/images/abc123', $presentation->imageBannerSrc);
        $this->assertSame('/uploads/images/abc124', $presentation->imageLogoSrc);
        $this->assertSame('modern', $presentation->themeFontName);
        $this->assertSame('simple', $presentation->themeLayout);
        $this->assertSame('#663399', $presentation->themePrimaryColor);
    }

    public function testInvoiceGetContentExisting(): void
    {
        $invoiceData = json_decode($this->sampleInvoiceData, true);
        $invoice = new Invoice($invoiceData['invoice']);
        $this->assertSame([
            'invoiceid' => 987654,
            'address' => '',
            'auto_bill' => false,
            'city' => 'Toronto',
            'code' => 'M5T 2B3',
            'country' => 'Canada',
            'customerid' => 12345,
            'create_date' => '2021-04-16',
            'currency_code' => 'CAD',
            'deposit_amount' => [
                'amount' => '1.00',
                'code' => 'CAD'
            ],
            'deposit_percentage' => '1.5',
            'discount_value' => 10.0,
            'due_offset_days' => 0,
            'fname' => 'Gordon',
            'invoice_number' => 'ACM0002',
            'language' => 'en',
            'lname' => 'Shumway',
            'lines' => [
                [
                    'lineid' => 1,
                    'description' => 'Melmac melamine resin molded dinnerware',
                    'expenseid' => 0,
                    'name' => 'Bowls',
                    'qty' => 4.0,
                    'taxAmount1' => '13',
                    'taxAmount2' => '0',
                    'taxName1' => 'HST1',
                    'taxName2' => null,
                    'taxNumber1' => 'RT',
                    'taxNumber2' => null,
                    'type' => 0,
                    'unit_cost' => [
                        'amount' => '5.00',
                        'code' => 'CAD'
                    ]
                ],
                [
                    'lineid' => 2,
                    'description' => 'Melmac melamine resin molded mug',
                    'expenseid' => 0,
                    'name' => 'Mugs',
                    'qty' => 6.0,
                    'taxAmount1' => '0',
                    'taxAmount2' => '0',
                    'taxName1' => '',
                    'taxName2' => '',
                    'taxNumber1' => null,
                    'taxNumber2' => null,
                    'type' => 0,
                    'unit_cost' => [
                        'amount' => '4.00',
                        'code' => 'CAD'
                    ]
                ]
            ],
            'notes' => 'Thanks for your business',
            'parent' => 0,
            'presentation' => [
                'invoiceid' => 987654,
                'date_format' => 'dd/mm/yyyy',
                'image_banner_src' => '/uploads/images/abc123',
                'image_logo_src' => '/uploads/images/abc124',
                'theme_font_name' => 'modern',
                'theme_layout' => 'simple',
                'theme_primary_color' => '#663399',
            ],
            'province' => 'Ontario',
            'show_attachments' => false,
            'street' => '123 Huron St.',
            'street2' => '',
            'vat_name' => 'VAT Number',
            'vat_number' => '123',
            'generation_date' => '2021-04-16'
        ], $invoice->getContent());
    }

    public function testInvoiceGetContentNew(): void
    {
        $invoiceData = json_decode($this->sampleInvoiceData, true);
        unset($invoiceData['invoice']['id']);
        unset($invoiceData['invoice']['invoiceid']);
        $invoice = new Invoice($invoiceData['invoice']);
        $content = $invoice->getContent();
        ksort($content);

        $this->assertSame([
            'address' => '',
            'auto_bill' => false,
            'city' => 'Toronto',
            'code' => 'M5T 2B3',
            'country' => 'Canada',
            'create_date' => '2021-04-16',
            'currency_code' => 'CAD',
            'customerid' => 12345,
            'deposit_amount' => [
                'amount' => '1.00',
                'code' => 'CAD'
            ],
            'deposit_percentage' => '1.5',
            'deposit_status' => 'none',
            'discount_total' => [
                'amount' => '-4.40',
                'code' => 'CAD'
            ],
            'discount_value' => 10.0,
            'display_status' => 'sent',
            'due_offset_days' => 0,
            'estimateid' => 0,
            'fname' => 'Gordon',
            'generation_date' => '2021-04-16',
            'invoice_number' => 'ACM0002',
            'language' => 'en',
            'lines' => [
                [
                    'lineid' => 1,
                    'description' => 'Melmac melamine resin molded dinnerware',
                    'expenseid' => 0,
                    'name' => 'Bowls',
                    'qty' => 4.0,
                    'taxAmount1' => '13',
                    'taxAmount2' => '0',
                    'taxName1' => 'HST1',
                    'taxName2' => null,
                    'taxNumber1' => 'RT',
                    'taxNumber2' => null,
                    'type' => 0,
                    'unit_cost' => [
                        'amount' => '5.00',
                        'code' => 'CAD'
                    ]
                ],
                [
                    'lineid' => 2,
                    'description' => 'Melmac melamine resin molded mug',
                    'expenseid' => 0,
                    'name' => 'Mugs',
                    'qty' => 6.0,
                    'taxAmount1' => '0',
                    'taxAmount2' => '0',
                    'taxName1' => '',
                    'taxName2' => '',
                    'taxNumber1' => null,
                    'taxNumber2' => null,
                    'type' => 0,
                    'unit_cost' => [
                        'amount' => '4.00',
                        'code' => 'CAD'
                    ]
                ]
            ],
            'lname' => 'Shumway',
            'notes' => 'Thanks for your business',
            'ownerid' => 1,
            'parent' => 0,
            'payment_status' => 'partial',
            'presentation' => [
                'invoiceid' => 987654,
                'date_format' => 'dd/mm/yyyy',
                'image_banner_src' => '/uploads/images/abc123',
                'image_logo_src' => '/uploads/images/abc124',
                'theme_font_name' => 'modern',
                'theme_layout' => 'simple',
                'theme_primary_color' => '#663399',
            ],
            'province' => 'Ontario',
            'sentid' => 1,
            'show_attachments' => false,
            'status' => 2,
            'street' => '123 Huron St.',
            'street2' => '',
            'v3_status' => 'partial',
            'vat_name' => 'VAT Number',
            'vat_number' => '123'
        ], $content);
    }
}
