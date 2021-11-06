<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Tests\Model;

use DateTime;
use DateTimeZone;
use PHPUnit\Framework\TestCase;
use amcintosh\FreshBooks\Model\Client;
use amcintosh\FreshBooks\Model\VisState;

final class ClientTest extends TestCase
{
    private $sampleClientData = '{"client": {
        "accounting_systemid": "ACM123",
        "allow_email_include_pdf": false,
        "allow_late_fees": true,
        "allow_late_notifications": true,
        "bus_phone": "416-867-5309",
        "company_industry": null,
        "company_size": null,
        "currency_code": "CAD",
        "direct_link_token": null,
        "email": "gordon.shumway@AmericanCyanamid.com",
        "fax": "416-444-4444",
        "fname": "Gordon",
        "grand_total_balance": [],
        "has_retainer": null,
        "home_phone": "416-444-4445",
        "id": 12345,
        "language": "en",
        "last_activity": null,
        "last_login": null,
        "level": 0,
        "lname": "Shumway",
        "mob_phone": "416-444-4446",
        "note": "I like cats",
        "notified": false,
        "num_logins": 0,
        "organization": "American Cyanamid",
        "test_amount": {
            "amount": 10,
            "code": "CAD"
        },
        "outstanding_balance": [
            {
                "amount": {
                    "amount": 10,
                    "code": "CAD"
                }
            },
            {
                "amount": {
                    "amount": 11,
                    "code": "CAD"
                }
            }
        ],
        "p_city": "Toronto",
        "p_code": "M5T 2B3",
        "p_country": "Canada",
        "p_province": "ON",
        "p_street": "123 Huron St",
        "p_street2": "",
        "pref_email": true,
        "pref_gmail": false,
        "retainer_id": null,
        "role": "client",
        "s_city": "",
        "s_code": "",
        "s_country": "",
        "s_province": "",
        "s_street": "",
        "s_street2": "",
        "signup_date": "2020-10-31 15:25:34",
        "statement_token": null,
        "subdomain": null,
        "updated": "2020-11-01 13:11:10",
        "userid": 12345,
        "username": "alf",
        "uuid": "ab1c33f1-8827-4a46-b335-75a6a4149db8",
        "vat_name": null,
        "vat_number": null,
        "vis_state": 0
    }}';

    public function testClientFromResponse(): void
    {
        $clientData = json_decode($this->sampleClientData, true);

        $client = new Client($clientData['client']);

        $this->assertEquals(12345, $client->id);
        $this->assertEquals('ACM123', $client->accountingSystemId);
        $this->assertEquals('416-867-5309', $client->businessPhone);
        $this->assertEquals('', $client->companyIndustry);
        $this->assertEquals('', $client->companySize);
        $this->assertEquals('CAD', $client->currencyCode);
        $this->assertEquals('gordon.shumway@AmericanCyanamid.com', $client->email);
        $this->assertEquals('416-444-4444', $client->fax);
        $this->assertEquals('Gordon', $client->firstName);
        $this->assertEquals('416-444-4445', $client->homePhone);
        $this->assertEquals('en', $client->language);
        $this->assertEquals('', $client->lastActivity);
        $this->assertEquals('Shumway', $client->lastName);
        $this->assertEquals('416-444-4446', $client->mobilePhone);
        $this->assertEquals('I like cats', $client->note);
        $this->assertEquals('American Cyanamid', $client->organization);
        $this->assertEquals('Toronto', $client->billingCity);
        $this->assertEquals('M5T 2B3', $client->billingCode);
        $this->assertEquals('Canada', $client->billingCountry);
        $this->assertEquals('ON', $client->billingProvince);
        $this->assertEquals('123 Huron St', $client->billingStreet);
        $this->assertEquals('', $client->billingStreet2);
        $this->assertEquals('', $client->shippingCity);
        $this->assertEquals('', $client->shippingCode);
        $this->assertEquals('', $client->shippingCountry);
        $this->assertEquals('', $client->shippingProvince);
        $this->assertEquals('', $client->shippingStreet);
        $this->assertEquals('', $client->shippingStreet2);
        $this->assertEquals(new DateTime('2020-10-31T15:25:34Z'), $client->signupDate);
        $this->assertEquals(new DateTime('2020-11-01T18:11:10Z'), $client->updated);
        $this->assertEquals(12345, $client->userId);
        $this->assertEquals('', $client->vatName);
        $this->assertEquals('', $client->vatNumber);
        $this->assertEquals(VisState::ACTIVE, $client->visState);
    }

    public function testClientGetContent(): void
    {
        $clientData = json_decode($this->sampleClientData, true);
        $client = new Client($clientData['client']);
        $this->assertEquals([
            'bus_phone' => '416-867-5309',
            'company_industry' => null,
            'company_size' => null,
            'currency_code' => 'CAD',
            'email' => 'gordon.shumway@AmericanCyanamid.com',
            'fax' => '416-444-4444',
            'fname' => 'Gordon',
            'home_phone' => '416-444-4445',
            'language' => 'en',
            'lname' => 'Shumway',
            'mob_phone' => '416-444-4446',
            'note' => 'I like cats',
            'organization' => 'American Cyanamid',
            'p_city' => 'Toronto',
            'p_code' => 'M5T 2B3',
            'p_country' => 'Canada',
            'p_province' => 'ON',
            'p_street' => '123 Huron St',
            'p_street2' => '',
            's_city' => '',
            's_code' => '',
            's_country' => '',
            's_province' => '',
            's_street' => '',
            's_street2' => '',
            'vat_name' => null,
            'vat_number' => null
        ], $client->getContent());
    }
}
