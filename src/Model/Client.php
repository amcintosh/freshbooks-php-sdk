<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use DateTimeImmutable;
use amcintosh\FreshBooks\Util;
use amcintosh\FreshBooks\Model\DataModel;
use amcintosh\FreshBooks\Model\Caster\AccountingDateTimeImmutableCaster;

/**
 * A client in the new FreshBooks is a resource representing an entity you send invoices to.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/clients
 */
class Client implements DataModel
{
    public const RESPONSE_FIELD = 'client';

    /**
     * @var int The unique identifier of this client within this business.
     */
    public ?int $id;

    /**
     * @var string Unique identifier of account the client exists on.
     */
    public ?string $accountingSystemId;

    /**
     * @var string Business phone number.
     */
    public ?string $businessPhone;

    /**
     * @var string Description of industry client is in.
     */
    public ?string $companyIndustry;

    /**
     * @var string Size of client's company.
     */
    public ?string $companySize;

    /**
     * @var string 3-letter shortcode for client's preferred currency. Eg. USD, CAD, EUR
     */
    public ?string $currencyCode;

    /**
     * @var string Client's email.
     */
    public ?string $email;

    /**
     * @var string Client's fax number.
     */
    public ?string $fax;

    /**
     * @var string Client's first name.
     */
    public ?string $firstName;

    /**
     * @var string Client's home phone number.
     */
    public ?string $homePhone;

    /**
     * @var string Shortcode indicating user language e.g. "en"
     */
    public ?string $language;

    /**
     * @var string The last client activity action.
     *
     * _Note:_ This returns as "null" in all calls unless a "last_activity"
     * include parameter is provided.
     */
    public ?string $lastActivity;

    /**
     * @var string Client's last name.
     */
    public ?string $lastName;

    /**
     * @var string Client's mobile phone number.
     *
     * Eg. "416-444-4444"
     */
    public ?string $mobilePhone;

    /**
     * @var string Notes kept by admin about client.
     */
    public ?string $note;

    /**
     * @var string Name for client's business.
     */
    public ?string $organization;

    /**
     * @var string Billing address city.
     */
    public ?string $billingCity;

    /**
     * @var string Billing address postal code.
     */
    public ?string $billingCode;

    /**
     * @var string Billing address country.
     */
    public ?string $billingCountry;

    /**
     * @var string Short form of province/state for billing address.
     */
    public ?string $billingProvince;

    /**
     * @var string Billing address street.
     */
    public ?string $billingStreet;

    /**
     * @var string Billing address, additional street info.
     */
    public ?string $billingStreet2;

    /**
     * @var string Shipping address city.
     */
    public ?string $shippingCity;

    /**
     * @var string Shipping address postal code.
     */
    public ?string $shippingCode;

    /**
     * @var string Shipping address country.
     */
    public ?string $shippingCountry;

    /**
     * @var string Short form of province/state for shipping address.
     */
    public ?string $shippingProvince;

    /**
     * @var string Shipping address street.
     */
    public ?string $shippingStreet;

    /**
     * @var string Shipping address, second street info.
     */
    public ?string $shippingStreet2;

    /**
     * @var DateTimeImmutable The signup time of the client.
     */
    public ?DateTimeImmutable $signupDate;

    /**
     * @var DateTimeImmutable The time of last modification.
     */
    public ?DateTimeImmutable $updated;

    /**
     * @var int Duplicate of id
     */
    public ?int $userId;

    /**
     * @var string The "Value Added Tax" name
     */
    public ?string $vatName;

    /**
     * @var string The "Value Added Tax" number
     */
    public ?string $vatNumber;

    /**
     * @var int The visibility state: active, deleted, or archived
     *
     * See [FreshBooks API - Active and Deleted Objects](https://www.freshbooks.com/api/active_deleted)
     */
    public ?int $visState;


    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->accountingSystemId = $data['accounting_systemid'] ?? null;
        $this->businessPhone = $data['bus_phone'] ?? null;
        $this->companyIndustry = $data['company_industry'] ?? null;
        $this->companySize = $data['company_size'] ?? null;
        $this->currencyCode = $data['currency_code'] ?? null;
        $this->email = $data['email'] ?? null;
        $this->fax = $data['fax'] ?? null;
        $this->firstName = $data['fname'] ?? null;
        $this->homePhone = $data['home_phone'] ?? null;
        $this->language = $data['language'] ?? null;
        $this->lastActivity = $data['last_activity'] ?? null;
        $this->lastName = $data['lname'] ?? null;
        $this->mobilePhone = $data['mob_phone'] ?? null;
        $this->note = $data['note'] ?? null;
        $this->organization = $data['organization'] ?? null;
        $this->billingCity = $data['p_city'] ?? null;
        $this->billingCode = $data['p_code'] ?? null;
        $this->billingCountry = $data['p_country'] ?? null;
        $this->billingProvince = $data['p_province'] ?? null;
        $this->billingStreet = $data['p_street'] ?? null;
        $this->billingStreet2 = $data['p_street2'] ?? null;
        $this->shippingCity = $data['s_city'] ?? null;
        $this->shippingCode = $data['s_code'] ?? null;
        $this->shippingCountry = $data['s_country'] ?? null;
        $this->shippingProvince = $data['s_province'] ?? null;
        $this->shippingStreet = $data['s_street'] ?? null;
        $this->shippingStreet2 = $data['s_street2'] ?? null;
        $this->userId = $data['userid'] ?? null;
        $this->vatName = $data['vat_name'] ?? null;
        $this->vatNumber = $data['vat_number'] ?? null;
        $this->visState = $data['vis_state'] ?? null;

        if (isset($data['signup_date'])) {
            $this->signupDate = Util::getAccountingDateTime($data['signup_date'], isUtc: true);
        }
        if (isset($data['updated'])) {
            $this->updated = Util::getAccountingDateTime($data['updated']);
        }
    }

    /**
     * Serialize the Client model to an array to POST or PUT to FreshBooks,
     * removing any read-only fields.
     *
     * @return array
     */
    public function getContent(): array
    {
        $data = array();
        Util::convertContent($data, "bus_phone", $this->businessPhone);
        Util::convertContent($data, "company_industry", $this->companyIndustry);
        Util::convertContent($data, "company_size", $this->companySize);
        Util::convertContent($data, "currency_code", $this->currencyCode);
        Util::convertContent($data, "email", $this->email);
        Util::convertContent($data, "fax", $this->fax);
        Util::convertContent($data, "fname", $this->firstName);
        Util::convertContent($data, "home_phone", $this->homePhone);
        Util::convertContent($data, "language", $this->language);
        Util::convertContent($data, "lname", $this->lastName);
        Util::convertContent($data, "mob_phone", $this->mobilePhone);
        Util::convertContent($data, "note", $this->note);
        Util::convertContent($data, "organization", $this->organization);
        Util::convertContent($data, "p_city", $this->billingCity);
        Util::convertContent($data, "p_code", $this->billingCode);
        Util::convertContent($data, "p_country", $this->billingCountry);
        Util::convertContent($data, "p_province", $this->billingProvince);
        Util::convertContent($data, "p_street", $this->billingStreet);
        Util::convertContent($data, "p_street2", $this->billingStreet2);
        Util::convertContent($data, "s_city", $this->shippingCity);
        Util::convertContent($data, "s_code", $this->shippingCode);
        Util::convertContent($data, "s_country", $this->shippingCountry);
        Util::convertContent($data, "s_province", $this->shippingProvince);
        Util::convertContent($data, "s_street", $this->shippingStreet);
        Util::convertContent($data, "s_street2", $this->shippingStreet2);
        Util::convertContent($data, "vat_name", $this->vatName);
        Util::convertContent($data, "vat_number", $this->vatNumber);
        return $data;
    }
}
