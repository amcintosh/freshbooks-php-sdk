<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use DateTimeImmutable;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\Attributes\MapTo;
use Spatie\DataTransferObject\Caster;
use Spatie\DataTransferObject\DataTransferObject;
use amcintosh\FreshBooks\Model\DataModelLegacy;
use amcintosh\FreshBooks\Model\Caster\AccountingDateTimeImmutableCaster;

/**
 * A client in the new FreshBooks is a resource representing an entity you send invoices to.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/clients
 */
class Client extends DataTransferObject implements DataModelLegacy
{
    public const RESPONSE_FIELD = 'client';

    /**
     * @var int The unique identifier of this client within this business.
     */
    public ?int $id;

    /**
     * @var string Unique identifier of account the client exists on.
     */
    #[MapFrom('accounting_systemid')]
    public ?string $accountingSystemId;

    /**
     * @var string Business phone number.
     */
    #[MapFrom('bus_phone')]
    #[MapTo('bus_phone')]
    public ?string $businessPhone;

    /**
     * @var string Description of industry client is in.
     */
    #[MapFrom('company_industry')]
    #[MapTo('company_industry')]
    public ?string $companyIndustry;

    /**
     * @var string Size of client's company.
     */
    #[MapFrom('company_size')]
    #[MapTo('company_size')]
    public ?string $companySize;

    /**
     * @var string 3-letter shortcode for client's preferred currency. Eg. USD, CAD, EUR
     */
    #[MapFrom('currency_code')]
    #[MapTo('currency_code')]
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
    #[MapFrom('fname')]
    #[MapTo('fname')]
    public ?string $firstName;

    /**
     * @var string Client's home phone number.
     */
    #[MapFrom('home_phone')]
    #[MapTo('home_phone')]
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
    #[MapFrom('last_activity')]
    public ?string $lastActivity;

    /**
     * @var string Client's last name.
     */
    #[MapFrom('lname')]
    #[MapTo('lname')]
    public ?string $lastName;

    /**
     * @var string Client's mobile phone number.
     *
     * Eg. "416-444-4444"
     */
    #[MapFrom('mob_phone')]
    #[MapTo('mob_phone')]
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
    #[MapFrom('p_city')]
    #[MapTo('p_city')]
    public ?string $billingCity;

    /**
     * @var string Billing address postal code.
     */
    #[MapFrom('p_code')]
    #[MapTo('p_code')]
    public ?string $billingCode;

    /**
     * @var string Billing address country.
     */
    #[MapFrom('p_country')]
    #[MapTo('p_country')]
    public ?string $billingCountry;

    /**
     * @var string Short form of province/state for billing address.
     */
    #[MapFrom('p_province')]
    #[MapTo('p_province')]
    public ?string $billingProvince;

    /**
     * @var string Billing address street.
     */
    #[MapFrom('p_street')]
    #[MapTo('p_street')]
    public ?string $billingStreet;

    /**
     * @var string Billing address, additional street info.
     */
    #[MapFrom('p_street2')]
    #[MapTo('p_street2')]
    public ?string $billingStreet2;

    /**
     * @var string Shipping address city.
     */
    #[MapFrom('s_city')]
    #[MapTo('s_city')]
    public ?string $shippingCity;

    /**
     * @var string Shipping address postal code.
     */
    #[MapFrom('s_code')]
    #[MapTo('s_code')]
    public ?string $shippingCode;

    /**
     * @var string Shipping address country.
     */
    #[MapFrom('s_country')]
    #[MapTo('s_country')]
    public ?string $shippingCountry;

    /**
     * @var string Short form of province/state for shipping address.
     */
    #[MapFrom('s_province')]
    #[MapTo('s_province')]
    public ?string $shippingProvince;

    /**
     * @var string Shipping address street.
     */
    #[MapFrom('s_street')]
    #[MapTo('s_street')]
    public ?string $shippingStreet;

    /**
     * @var string Shipping address, second street info.
     */
    #[MapFrom('s_street2')]
    #[MapTo('s_street2')]
    public ?string $shippingStreet2;

    /**
     * @var DateTimeImmutable The signup time of the client.
     */
    #[MapFrom('signup_date')]
    #[CastWith(AccountingDateTimeImmutableCaster::class, isUtc: true)]
    public ?DateTimeImmutable $signupDate;

    /**
     * @var DateTimeImmutable The time of last modification.
     */
    #[CastWith(AccountingDateTimeImmutableCaster::class)]
    public ?DateTimeImmutable $updated;

    /**
     * @var int Duplicate of id
     */
    #[MapFrom('userid')]
    public ?int $userId;

    /**
     * @var string The "Value Added Tax" name
     */
    #[MapFrom('vat_name')]
    #[MapTo('vat_name')]
    public ?string $vatName;

    /**
     * @var string The "Value Added Tax" number
     */
    #[MapFrom('vat_number')]
    #[MapTo('vat_number')]
    public ?string $vatNumber;

    /**
     * @var int The visibility state: active, deleted, or archived
     *
     * See [FreshBooks API - Active and Deleted Objects](https://www.freshbooks.com/api/active_deleted)
     */
    #[MapFrom('vis_state')]
    public ?int $visState;

    /**
     * Get the data as an array to POST or PUT to FreshBooks, removing any read-only fields.
     *
     * @return array
     */
    public function getContent(): array
    {
        $data = $this
            ->except('id')
            ->except('accountingSystemId')
            ->except('lastActivity')
            ->except('signupDate')
            ->except('updated')
            ->except('userId')
            ->except('visState')
            ->toArray();
        foreach ($data as $key => $value) {
            if (is_null($value)) {
                unset($data[$key]);
            }
        }
        return $data;
    }
}
