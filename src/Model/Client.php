<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use DateTimeImmutable;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Attributes\DefaultCast;
use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\Caster;
use Spatie\DataTransferObject\DataTransferObject;
use amcintosh\FreshBooks\Model\Caster\AccountingDateTimeImmutableCaster;

class Client extends DataTransferObject
{
    /**
     * @var int The unique identifier of this client within this business.
     */
    public ?int $id;

    /**
     * @var string Unique identifier of business client exists on.
     */
    #[MapFrom('accounting_systemid')]
    public ?string $accountingSystemId;

    /**
     * @var string Business phone number.
     */
    #[MapFrom('bus_phone')]
    public ?string $businessPhone;

    /**
     * @var string Description of industry client is in.
     */
    #[MapFrom('company_industry')]
    public ?string $companyIndustry;

    // @Key("company_size") String companySize;
    // @Key("currency_code") String currencyCode;
    // @Key String email;
    // @Key String fax;

    /**
     * @var string Client's first name.
     */
    #[MapFrom('fname')]
    public ?string $firstName;

    /**
     * @var string Client's home phone number.
     */
    #[MapFrom('home_phone')]
    public ?string $homePhone;

    /**
     * @var string Shortcode indicating user language e.g. "en"
     */
    public ?string $language;

    // @Key("last_activity") String lastActivity;
    // @Key("lname") String lastName;
    // @Key("mob_phone") String mobilePhone;
    // @Key String note;

    /**
     * @var string Name for client's business.
     */
    public ?string $organization;

    // @Key("p_city") String billingCity;
    // @Key("p_code") String billingCode;
    // @Key("p_country") String billingCountry;
    // @Key("p_province") String billingProvince;
    // @Key("p_street") String billingStreet;
    // @Key("p_street2") String billingStreet2;
    // @Key("s_city") String shippingCity;
    // @Key("s_code") String shippingCode;
    // @Key("s_country") String shippingCountry;
    // @Key("s_province") String shippingProvince;
    // @Key("s_street") String shippingStreet;
    // @Key("s_street2") String shippingStreet2;

    /**
     * @var DateTimeImmutable The signup time of the client.
     */
    #[MapFrom('signup_date')]
    #[CastWith(AccountingDateTimeImmutableCaster::class, isUtc: true)]
    public ?DateTimeImmutable $signupDate;

    /**
     * @var DateTimeImmutable The time of last modification to the client.
     */
    #[CastWith(AccountingDateTimeImmutableCaster::class)]
    public ?DateTimeImmutable $updated;

    // @Key("userid") Long userId;
    // @Key("vat_name") String vatName;
    // @Key("vat_number") String vatNumber;
    // @Key("vis_state") int visState;
}
