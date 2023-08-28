<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Tests\Model;

use amcintosh\FreshBooks\Model\BillVendor;
use DateTime;
use PHPUnit\Framework\TestCase;
use Spryker\DecimalObject\Decimal;

class BillVendorTest extends TestCase
{
    private string $sampleVendorData = '{"bill_vendor": {
        "account_number": "45454545",
        "city": "San Francisco",
        "country": "United States",
        "created_at": "2021-06-17 12:08:25",
        "currency_code": "USD",
        "is_1099": false,
        "language": "en",
        "note": "Some note",
        "outstanding_balance": [
            {
                "amount": {
                    "amount": "50113.00",
                    "code": "USD"
                }
            }
        ],
        "overdue_balance": [
            {
                 "amount": {
                      "amount": "50113.00",
                      "code": "USD"
                 }
            }
        ],
        "phone": "4158859378",
        "postal_code": "92225",
        "primary_contact_email": "someone@ikea.com",
        "primary_contact_first_name": "Jimmy",
        "primary_contact_last_name": "McNamara",
        "province": "California",
        "street": "332 Carlton Ave.",
        "street2": "PO 123",
        "tax_defaults": [
            {
                 "amount": "6",
                 "created_at": "2021-06-16 09:49:46",
                 "enabled": true,
                 "name": "GST1",
                 "system_taxid": 5307,
                 "tax_authorityid": null,
                 "taxid": 95,
                 "updated_at": "2021-06-16 09:49:46",
                 "vendorid": 1563
            },
            {
                 "amount": "7",
                 "created_at": "2021-06-16 09:49:46",
                 "enabled": true,
                 "name": "PST",
                 "system_taxid": 6047,
                 "tax_authorityid": null,
                 "taxid": 96,
                 "updated_at": "2021-06-16 09:49:46",
                 "vendorid": 1563
            }
        ],
        "updated_at": "2021-06-17 12:08:25",
        "vendor_name": "IKEA",
        "vendorid": 1563,
        "vis_state": 0,
        "website": "ikea.com"
    }}';

    public function testBillVendorFromResponse(): void
    {
        $vendorData = json_decode($this->sampleVendorData, true);

        $vendor = new BillVendor($vendorData[BillVendor::RESPONSE_FIELD]);

        $this->assertSame('45454545', $vendor->accountNumber);
        $this->assertSame('San Francisco', $vendor->city);
        $this->assertSame('United States', $vendor->country);
        $this->assertEquals(new DateTime('2021-06-17T12:08:25Z'), $vendor->createdAt);
        $this->assertSame('USD', $vendor->currencyCode);
        $this->assertSame(false, $vendor->is1099);
        $this->assertSame('en', $vendor->language);
        $this->assertSame('Some note', $vendor->note);

        $this->assertEquals(Decimal::create('50113.00'), $vendor->outstandingBalance->amount);
        $this->assertSame('USD', $vendor->outstandingBalance->code);

        $this->assertEquals(Decimal::create('50113.00'), $vendor->overdueBalance->amount);
        $this->assertSame('USD', $vendor->overdueBalance->code);

        $this->assertSame('4158859378', $vendor->phone);
        $this->assertSame('92225', $vendor->postalCode);
        $this->assertSame('someone@ikea.com', $vendor->primaryContactEmail);
        $this->assertSame('Jimmy', $vendor->primaryContactFirstName);
        $this->assertSame('McNamara', $vendor->primaryContactLastName);
        $this->assertSame('California', $vendor->province);
        $this->assertSame('332 Carlton Ave.', $vendor->street);
        $this->assertSame('PO 123', $vendor->street2);

        $this->assertSame('6', $vendor->taxDefaults[0]->amount);
        $this->assertEquals(new DateTime('2021-06-16T09:49:46Z'), $vendor->taxDefaults[0]->createdAt);
        $this->assertSame(true, $vendor->taxDefaults[0]->enabled);
        $this->assertSame('GST1', $vendor->taxDefaults[0]->name);
        $this->assertSame(5307, $vendor->taxDefaults[0]->systemTaxId);
        $this->assertSame(null, $vendor->taxDefaults[0]->taxAuthorityId);
        $this->assertSame(95, $vendor->taxDefaults[0]->taxId);
        $this->assertEquals(new DateTime('2021-06-16T09:49:46Z'), $vendor->taxDefaults[0]->updatedAt);
        $this->assertSame(1563, $vendor->taxDefaults[0]->vendorId);

        $this->assertSame('7', $vendor->taxDefaults[1]->amount);
        $this->assertEquals(new DateTime('2021-06-16T09:49:46Z'), $vendor->taxDefaults[1]->createdAt);
        $this->assertSame(true, $vendor->taxDefaults[1]->enabled);
        $this->assertSame('PST', $vendor->taxDefaults[1]->name);
        $this->assertSame(6047, $vendor->taxDefaults[1]->systemTaxId);
        $this->assertSame(null, $vendor->taxDefaults[1]->taxAuthorityId);
        $this->assertSame(96, $vendor->taxDefaults[1]->taxId);
        $this->assertEquals(new DateTime('2021-06-16T09:49:46Z'), $vendor->taxDefaults[1]->updatedAt);
        $this->assertSame(1563, $vendor->taxDefaults[1]->vendorId);

        $this->assertEquals(new DateTime('2021-06-17T12:08:25Z'), $vendor->updatedAt);
        $this->assertSame('IKEA', $vendor->vendorName);
        $this->assertSame(1563, $vendor->vendorId);
        $this->assertSame(0, $vendor->visState);
        $this->assertSame('ikea.com', $vendor->website);
    }

    public function testVendorGetContent(): void
    {
        $vendorData = json_decode($this->sampleVendorData, true);

        $vendor = new BillVendor($vendorData['bill_vendor']);

        $this->assertSame([
            'account_number' => '45454545',
            'city' => 'San Francisco',
            'country' => 'United States',
            'currency_code' => 'USD',
            'is_1099' => false,
            'language' => 'en',
            'note' => 'Some note',
            'phone' => '4158859378',
            'postal_code' => '92225',
            'primary_contact_email' => 'someone@ikea.com',
            'primary_contact_first_name' => 'Jimmy',
            'primary_contact_last_name' => 'McNamara',
            'province' => 'California',
            'street' => '332 Carlton Ave.',
            'street2' => 'PO 123',
            'tax_defaults' => [
                [
                    'vendorid' => 1563,
                    'taxid' => 95,
                    'system_taxid' => 5307,
                    'enabled' => true,
                    'name' => 'GST1',
                    'amount' => '6',
                    'tax_authorityid' => null,
                ],
                [
                    'vendorid' => 1563,
                    'taxid' => 96,
                    'system_taxid' => 6047,
                    'enabled' => true,
                    'name' => 'PST',
                    'amount' => '7',
                    'tax_authorityid' => null,
                ]
            ],
            'vendor_name' => 'IKEA',
            'vendorid' => 1563,
            'vis_state' => 0,
            'website' => 'ikea.com',
        ], $vendor->getContent());
    }
}
