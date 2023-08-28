<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Tests\Model;

use amcintosh\FreshBooks\Model\BillVendorList;
use PHPUnit\Framework\TestCase;

class BillVendorListTest extends TestCase
{
    private $sampleVendorsData = '{
        "bill_vendors": [
            {
                "account_number": "45454545",
                "city": "San Francisco",
                "country": "United States",
                "created_at": "2021-06-17 12:08:25",
                "currency_code": "USD",
                "is_1099": false,
                "language": "en",
                "note": null,
                "outstanding_balance": [],
                "overdue_balance": [],
                "phone": "4158859378",
                "postal_code": null,
                "primary_contact_email": "someone@ikea.com",
                "primary_contact_first_name": "Jimmy",
                "primary_contact_last_name": "McNamara",
                "province": "California",
                "street": "332 Carlton Ave.",
                "street2": null,
                "tax_defaults": [],
                "updated_at": "2021-06-17 12:08:25",
                "vendor_name": "IKEA",
                "vendorid": 1563,
                "vis_state": 0,
                "website": "ikea.com"
            },
            {
                "account_number": "",
                "city": null,
                "country": null,
                "created_at": "2021-06-16 09:49:46",
                "currency_code": "USD",
                "is_1099": false,
                "language": "en",
                "note": null,
                "outstanding_balance": [
                    {
                        "amount": {
                            "amount": "53885.00",
                            "code": "USD"
                        }
                    }
                ],
                "overdue_balance": [],
                "phone": null,
                "postal_code": null,
                "primary_contact_email": "someone@freshbooks.com",
                "primary_contact_first_name": "Rahul",
                "primary_contact_last_name": "Varma",
                "province": null,
                "street": null,
                "street2": null,
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
                        "vendorid": 1562
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
                        "vendorid": 1562
                    }
                ],
                "updated_at": "2021-06-16 09:49:46",
                "vendor_name": "Bread Supplier",
                "vendorid": 1562,
                "vis_state": 0,
                "website": ""
            },
            {
                "account_number": "",
                "city": "",
                "country": null,
                "created_at": "2021-01-18 13:28:30",
                "currency_code": "EUR",
                "is_1099": false,
                "language": "en",
                "note": null,
                "outstanding_balance": [
                    {
                        "amount": {
                            "amount": "50113.00",
                            "code": "EUR"
                        }
                    }
                ],
                "overdue_balance": [
                    {
                        "amount": {
                            "amount": "50113.00",
                            "code": "EUR"
                        }
                    }
                ],
                "phone": "+",
                "postal_code": "",
                "primary_contact_email": "apiteam@gmail.com",
                "primary_contact_first_name": "teak",
                "primary_contact_last_name": "Wood",
                "province": "",
                "street": "",
                "street2": null,
                "tax_defaults": [],
                "updated_at": "2021-01-18 13:35:39",
                "vendor_name": "Teak Provider",
                "vendorid": 817,
                "vis_state": 0,
                "website": null
            }
        ],
        "page": 1,
        "pages": 1,
        "per_page": 15,
        "total": 3
    }';

    public function testBillVendorListFromResponse(): void
    {
        $vendorsData = json_decode($this->sampleVendorsData, true);

        $vendors = new BillVendorList($vendorsData);

        $this->assertSame(1, $vendors->pages()->page);
        $this->assertSame(1, $vendors->pages()->pages);
        $this->assertSame(15, $vendors->pages()->perPage);
        $this->assertSame(3, $vendors->pages()->total);

        $this->assertSame(1563, $vendors->billVendors[0]->vendorId);
        $this->assertSame('someone@ikea.com', $vendors->billVendors[0]->primaryContactEmail);

        $this->assertSame(1562, $vendors->billVendors[1]->vendorId);
        $this->assertSame('someone@freshbooks.com', $vendors->billVendors[1]->primaryContactEmail);

        $this->assertSame(817, $vendors->billVendors[2]->vendorId);
        $this->assertSame('apiteam@gmail.com', $vendors->billVendors[2]->primaryContactEmail);
    }
}
