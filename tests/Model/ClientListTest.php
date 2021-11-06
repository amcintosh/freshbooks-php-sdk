<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Tests\Model;

use PHPUnit\Framework\TestCase;
use amcintosh\FreshBooks\Model\ClientList;

final class ClientListTest extends TestCase
{
    private $sampleClientData = '{
        "clients": [
            {
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
            },
            {
                "accounting_systemid": "ACM123",
                "allow_email_include_pdf": false,
                "allow_late_fees": true,
                "allow_late_notifications": true,
                "bus_phone": "",
                "company_industry": null,
                "company_size": null,
                "currency_code": "CAD",
                "direct_link_token": null,
                "email": "will.tanner@AmericanCyanamid.com",
                "fax": "",
                "fname": "Willie",
                "has_retainer": null,
                "home_phone": null,
                "id": 12346,
                "language": "en",
                "last_activity": null,
                "last_login": null,
                "level": 0,
                "lname": "Tanner",
                "mob_phone": "",
                "note": "Please don\'t",
                "notified": false,
                "num_logins": 0,
                "organization": "American Cyanamid",
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
                "signup_date": "2020-10-17 05:25:34",
                "statement_token": null,
                "subdomain": null,
                "updated": "2020-10-17 06:25:34",
                "userid": 12346,
                "username": "willie",
                "uuid": "135686c8-a635-46d9-a078-dfcac9761a7a",
                "vat_name": null,
                "vat_number": null,
                "vis_state": 0
            }
        ],
        "page": 1,
        "pages": 1,
        "per_page": 15,
        "total": 2
    }';

    public function testClientFromResponse(): void
    {
        $clientData = json_decode($this->sampleClientData, true);

        $clients = new ClientList($clientData);

        $this->assertEquals(1, $clients->page);
        $this->assertEquals(1, $clients->pages);
        $this->assertEquals(15, $clients->perPage);
        $this->assertEquals(2, $clients->total);

        $this->assertEquals(12345, $clients->clients[0]->id);
        $this->assertEquals('gordon.shumway@AmericanCyanamid.com', $clients->clients[0]->email);

        $this->assertEquals(12346, $clients->clients[1]->id);
        $this->assertEquals('will.tanner@AmericanCyanamid.com', $clients->clients[1]->email);
    }
}
