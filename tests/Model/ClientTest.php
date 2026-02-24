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

        $client = new Client($clientData[Client::RESPONSE_FIELD]);

        $this->assertSame(12345, $client->id);
        $this->assertSame('ACM123', $client->accountingSystemId);
        $this->assertSame('416-867-5309', $client->businessPhone);
        $this->assertSame(null, $client->companyIndustry);
        $this->assertSame(null, $client->companySize);
        $this->assertSame('CAD', $client->currencyCode);
        $this->assertSame('gordon.shumway@AmericanCyanamid.com', $client->email);
        $this->assertSame('416-444-4444', $client->fax);
        $this->assertSame('Gordon', $client->firstName);
        $this->assertSame('416-444-4445', $client->homePhone);
        $this->assertSame('en', $client->language);
        $this->assertSame(null, $client->lastActivity);
        $this->assertSame('Shumway', $client->lastName);
        $this->assertSame('416-444-4446', $client->mobilePhone);
        $this->assertSame('I like cats', $client->note);
        $this->assertSame('American Cyanamid', $client->organization);
        $this->assertSame('Toronto', $client->billingCity);
        $this->assertSame('M5T 2B3', $client->billingCode);
        $this->assertSame('Canada', $client->billingCountry);
        $this->assertSame('ON', $client->billingProvince);
        $this->assertSame('123 Huron St', $client->billingStreet);
        $this->assertSame('', $client->billingStreet2);
        $this->assertSame('', $client->shippingCity);
        $this->assertSame('', $client->shippingCode);
        $this->assertSame('', $client->shippingCountry);
        $this->assertSame('', $client->shippingProvince);
        $this->assertSame('', $client->shippingStreet);
        $this->assertSame('', $client->shippingStreet2);
        $this->assertEquals(new DateTime('2020-10-31T15:25:34Z'), $client->signupDate);
        $this->assertEquals(new DateTime('2020-11-01T18:11:10Z'), $client->updated);
        $this->assertSame(12345, $client->userId);
        $this->assertSame(null, $client->vatName);
        $this->assertSame(null, $client->vatNumber);
        $this->assertSame(VisState::ACTIVE, $client->visState);
    }

    public function testClientToRequest(): void
    {
        $clientData = json_decode($this->sampleClientData, true);
        $client = new Client($clientData['client']);
        $this->assertSame([
            'bus_phone' => '416-867-5309',
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
            's_street2' => ''
        ], $client->getContent());
    }
}
