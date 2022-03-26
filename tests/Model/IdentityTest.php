<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Tests\Model;

use DateTime;
use DateTimeZone;
use PHPUnit\Framework\TestCase;
use amcintosh\FreshBooks\Model\Identity;

final class IdentityTest extends TestCase
{
    private $sampleMeData = '{
        "id": 12345,
        "profile": {
            "setup_complete": true,
            "first_name": "Simon",
            "last_name": "Kovalic",
            "phone_number": null,
            "address": null,
            "professions": [
                {
                    "id": 52414,
                    "business_id": null,
                    "title": "Intelligence",
                    "company": "Commonwealth of Independent Systems",
                    "designation": null
                }
            ],
            "has_password": true
        },
        "identity_id": 12345,
        "identity_uuid": "a_uuid",
        "first_name": "Simon",
        "last_name": "Kovalic",
        "email": "skovalic@cis.com",
        "language": "en",
        "confirmed_at": "2017-05-23T05:57:24Z",
        "created_at": "2017-05-23T05:42:42Z",
        "unconfirmed_email": null,
        "setup_complete": true,
        "phone_numbers": [
            {
                "title": "",
                "phone_number": null
            }
        ],
        "addresses": [
            null
        ],
        "profession": {
            "id": 65432,
            "business_id": null,
            "title": "Intelligence",
            "company": "Commonwealth of Independent Systems",
            "designation": null
        },
        "links": {
            "me": "/service/auth/api/v1/users?id=12345",
            "roles": "/service/auth/api/v1/users/role/12345"
        },
        "permissions": {
            "ABC123": {},
            "ABC124": {}
        },
        "groups": [
            {
                "id": 12345,
                "group_id": 7654,
                "role": "owner",
                "identity_id": 12345,
                "identity_uuid": "a_uuid1",
                "business_id": 439000,
                "active": true
            },
            {
                "id": 12346,
                "group_id": 7656,
                "role": "business_employee",
                "identity_id": 12345,
                "identity_uuid": "a_uuid2",
                "business_id": 438000,
                "active": true
            }
        ],
        "subscription_statuses": {
            "ABC123": "active",
            "ABC124": "cancelled"
        },
        "integrations": {},
        "business_memberships": [
            {
                "id": 140130,
                "role": "owner",
                "unacknowledged_change": false,
                "business": {
                    "id": 439000,
                    "business_uuid": "a_uuid1",
                    "name": "Commonwealth of Independent Systems",
                    "account_id": "ABC123",
                    "date_format": "dd/mm/yyyy",
                    "address": {
                        "id": 76433,
                        "street": "123 Somewhere St. Apt. 705",
                        "city": "Salaam",
                        "province": "Terra Nova",
                        "country": "Commonwealth of Independent Systems",
                        "postal_code": "TER 123"
                    },
                    "phone_number": {
                        "id": 666111,
                        "phone_number": "8675309"
                    },
                    "business_clients": [
                        {
                            "id": 5432,
                            "business_id": 6543,
                            "account_id": "XYZ321",
                            "userid": 65342,
                            "client_business": {
                                "business_id": 438000
                            },
                            "account_business": {
                                "account_business_id": 7933,
                                "account_id": "XYZ321"
                            }
                        }
                    ]
                }
            },
            {
                "id": 140135,
                "role": "business_employee",
                "unacknowledged_change": false,
                "business": {
                    "id": 438000,
                    "business_uuid": "a_uuid2",
                    "name": "Bayern Corporation",
                    "account_id": "ABC124",
                    "date_format": "mm/dd/yyyy",
                    "address": {
                        "id": 273196,
                        "street": "5th Ring Street",
                        "city": "Bergfestung",
                        "province": "Bayern",
                        "country": "Bayern Corporation",
                        "postal_code": "E1E 1E1"
                    },
                    "phone_number": {
                        "id": 666112,
                        "phone_number": "123-456-789"
                    },
                    "business_clients": [
                        {
                            "id": 6302,
                            "business_id": 438000,
                            "account_id": "ABC123",
                            "userid": 543,
                            "client_business": {
                                "business_id": 438000
                            },
                            "account_business": {
                                "account_business_id": 467000,
                                "account_id": "MOO432"
                            }
                        }
                    ]
                }
            }
        ],
        "identity_origin": null,
        "roles": [
            {
                "id": 61270,
                "role": "admin",
                "systemid": 654654,
                "userid": 1,
                "created_at": "2018-08-15T17:57:02Z",
                "links": {
                    "destroy": "/service/auth/api/v1/users/role/61270"
                },
                "accountid": "ABC123"
            },
            {
                "id": 60280,
                "role": "client",
                "systemid": 866543,
                "userid": 12,
                "created_at": "2018-11-14T00:09:52Z",
                "links": {
                    "destroy": "/service/auth/api/v1/users/role/60280"
                },
                "accountid": "GAF54"
            },
            {
                "id": 60390,
                "role": "business_employee",
                "systemid": 654656,
                "userid": 2,
                "created_at": "2017-05-18T13:06:10Z",
                "links": {
                    "destroy": "/service/auth/api/v1/users/role/60390"
                },
                "accountid": "ABC124"
            }
        ]
    }';

    public function testIdentityFromMeResponse(): void
    {
        $meData = json_decode($this->sampleMeData, true);

        $identity = new Identity($meData);

        $this->assertSame(12345, $identity->identityId);
        $this->assertSame('a_uuid', $identity->identityUUID);
        $this->assertSame('Simon', $identity->firstName);
        $this->assertSame('Kovalic', $identity->lastName);
        $this->assertSame('skovalic@cis.com', $identity->email);
        $this->assertSame('en', $identity->language);
        $this->assertEquals(new DateTime('2017-05-23T05:57:24Z'), $identity->confirmedAt);
        $this->assertEquals(new DateTime('2017-05-23T05:42:42Z'), $identity->createdAt);

        $this->assertEquals(2, count($identity->businessMemberships));
        $businessMembership = $identity->businessMemberships[0];
        $this->assertEquals(140130, $businessMembership->id);
        $this->assertEquals('owner', $businessMembership->role);

        $business = $businessMembership->business;
        $this->assertEquals(439000, $business->id);
        $this->assertEquals('a_uuid1', $business->businessUUID);
        $this->assertEquals('Commonwealth of Independent Systems', $business->name);
        $this->assertEquals('ABC123', $business->accountId);
        $this->assertEquals('dd/mm/yyyy', $business->dateFormat);
        $this->assertEquals(76433, $business->address->id);
        $this->assertEquals('123 Somewhere St. Apt. 705', $business->address->street);
        $this->assertEquals('Salaam', $business->address->city);
        $this->assertEquals('Terra Nova', $business->address->province);
        $this->assertEquals('Commonwealth of Independent Systems', $business->address->country);
        $this->assertEquals('TER 123', $business->address->postalCode);
        $this->assertEquals(666111, $business->phoneNumber->id);
        $this->assertEquals('8675309', $business->phoneNumber->phoneNumber);
    }
}
