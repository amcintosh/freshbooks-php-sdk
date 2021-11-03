<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Tests\Model;

use DateTime;
use DateTimeZone;
use PHPUnit\Framework\TestCase;
use amcintosh\FreshBooks\Model\Client;

final class ClientTest extends TestCase
{
    private $sampleClientData = '{"client": {
        "accounting_systemid": "ACM123",
        "allow_email_include_pdf": false,
        "allow_late_fees": true,
        "allow_late_notifications": true,
        "bus_phone": "",
        "company_industry": null,
        "company_size": null,
        "currency_code": "CAD",
        "direct_link_token": null,
        "email": "gordon.shumway@AmericanCyanamid.com",
        "fax": "",
        "fname": "Gordon",
        "grand_total_balance": [],
        "has_retainer": null,
        "home_phone": null,
        "id": 12345,
        "language": "en",
        "last_activity": null,
        "last_login": null,
        "level": 0,
        "lname": "Shumway",
        "mob_phone": "",
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
        "p_city": "Torono",
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

    public function testGet(): void
    {
        $clientData = json_decode($this->sampleClientData, true);

        $client = new Client($clientData['client']);

        $this->assertEquals(12345, $client->id);
        $this->assertEquals(new DateTime('2020-10-31T15:25:34Z'), $client->signupDate);
        $this->assertEquals(new DateTime('2020-11-01T18:11:10Z'), $client->updated);
    }
}
