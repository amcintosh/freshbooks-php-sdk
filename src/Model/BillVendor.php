<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use amcintosh\FreshBooks\Model\Caster\AccountingDateTimeImmutableCaster;
use amcintosh\FreshBooks\Model\Caster\MoneyResponseCaster;
use DateTimeImmutable;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\Attributes\MapTo;
use Spatie\DataTransferObject\Casters\ArrayCaster;
use Spatie\DataTransferObject\DataTransferObject;

/**
 * A Vendor will work with your business to provide goods or services with a Bill to be paid at a later date.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/vendors
 */
class BillVendor extends DataTransferObject implements DataModel
{
    public const RESPONSE_FIELD = 'bill_vendor';

    /**
     * @var string Account number of the vendor.
     */
    #[MapFrom('account_number')]
    #[MapTo('account_number')]
    public ?string $accountNumber;

    /**
     * @var string City of vendor.
     */
    public ?string $city;

    /**
     * @var string Country of vendor.
     */
    public ?string $country;

    /**
     * @var DateTimeImmutable Read-only. Time the vendor was created.
     */
    #[MapFrom('created_at')]
    #[CastWith(AccountingDateTimeImmutableCaster::class, isUtc: true)]
    public ?DateTimeImmutable $createdAt;

    /**
     * @var string Default three-letter currency code for vendor.
     */
    #[MapFrom('currency_code')]
    #[MapTo('currency_code')]
    public ?string $currencyCode;

    /**
     * @var bool Set true if vendor is a 1099 contractor.
     */
    #[MapFrom('is_1099')]
    #[MapTo('is_1099')]
    public ?bool $is1099;

    /**
     * @var string Two-letter language code, e.g. "en".
     */
    public ?string $language;

    /**
     * @var string Note.
     */
    public ?string $note;

    /**
     * @var Money Read-only. Outstanding balance to be paid to the Vendor.
     */
    #[MapFrom('outstanding_balance')]
    #[CastWith(MoneyResponseCaster::class)]
    public ?Money $outstandingBalance;

    /**
     * @var Money Read-only. Overdue amount to be paid to the Vendor.
     */
    #[MapFrom('overdue_balance')]
    #[CastWith(MoneyResponseCaster::class)]
    public ?Money $overdueBalance;

    /**
     * @var string Phone number.
     */
    public ?string $phone;

    /**
     * @var string Postal code.
     */
    #[MapFrom('postal_code')]
    #[MapTo('postal_code')]
    public ?string $postalCode;

    /**
     * @var string Vendor primary email.
     */
    #[MapFrom('primary_contact_email')]
    #[MapTo('primary_contact_email')]
    public ?string $primaryContactEmail;

    /**
     * @var string Vendor primary first name.
     */
    #[MapFrom('primary_contact_first_name')]
    #[MapTo('primary_contact_first_name')]
    public ?string $primaryContactFirstName;

    /**
     * @var string Vendor primary last name.
     */
    #[MapFrom('primary_contact_last_name')]
    #[MapTo('primary_contact_last_name')]
    public ?string $primaryContactLastName;

    /**
     * @var string Province.
     */
    public ?string $province;

    /**
     * @var string Street address.
     */
    public ?string $street;

    /**
     * @var string Street address 2nd part.
     */
    public ?string $street2;

    /**
     * @var array Array of bill vendor tax defaults.
     */
    #[MapFrom('tax_defaults')]
    #[MapTo('tax_defaults')]
    #[CastWith(ArrayCaster::class, itemType: BillVendorTax::class)]
    public ?array $taxDefaults;

    /**
     * @var DateTimeImmutable Read-only. Time of last modification to resource.
     */
    #[MapFrom('updated_at')]
    #[CastWith(AccountingDateTimeImmutableCaster::class, isUtc: true)]
    public ?DateTimeImmutable $updatedAt;

    /**
     * @var string Vendor name.
     */
    #[MapFrom('vendor_name')]
    #[MapTo('vendor_name')]
    public ?string $vendorName;

    /**
     * @var string Unique identifier for vendor.
     */
    #[MapFrom('vendorid')]
    #[MapTo('vendorid')]
    public ?int $vendorId;

    /**
     * @var int Visibility state, possible values are 0, 1, 2.
     */
    #[MapFrom('vis_state')]
    #[MapTo('vis_state')]
    public ?int $visState;

    /**
     * @var string Vendor website address.
     */
    public ?string $website;

    /**
     * Get the data as an array to POST or PUT to FreshBooks, removing any read-only fields.
     *
     * @return array
     */
    public function getContent(): array
    {
        $data = $this
            ->except('createdAt')
            ->except('outstandingBalance')
            ->except('overdueBalance')
            ->except('updatedAt')
            ->toArray();
        foreach ($data as $key => $value) {
            if (is_null($value)) {
                unset($data[$key]);
            }
        }
        return $data;
    }
}
