<?php

namespace amcintosh\FreshBooks\Model;

use amcintosh\FreshBooks\Model\Caster\AccountingDateTimeImmutableCaster;
use DateTimeImmutable;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\Attributes\MapTo;
use Spatie\DataTransferObject\DataTransferObject;

/**
 * Bill vendor tax defaults.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/vendors
 */
class BillVendorTax extends DataTransferObject implements DataModel
{
    protected array $exceptKeys = ['createdAt', 'updatedAt'];

    /**
     * @var string Unique identifier for vendor.
     */
    #[MapFrom('vendorid')]
    #[MapTo('vendorid')]
    public ?int $vendorId;

    /**
     * @var int Unique identifier for vendor tax default.
     */
    #[MapFrom('taxid')]
    #[MapTo('taxid')]
    public ?int $taxId;

    /**
     * @var int Tax id in your FreshBooks business, you can get the tax id using the get taxes api.
     */
    #[MapFrom('system_taxid')]
    #[MapTo('system_taxid')]
    public ?int $systemTaxId;

    /**
     * @var bool If the tax is enabled for the vendor or not.
     */
    public ?bool $enabled;

    /**
     * @var string Read-only. Populated from related system tax.
     */
    public ?string $name;

    /**
     * @var string Read-only. Populated from related system tax.
     */
    public ?string $amount;

    /**
     * @var string Custom identifier for tax authority.
     */
    #[MapFrom('tax_authorityid')]
    #[MapTo('tax_authorityid')]
    public ?string $taxAuthorityId;

    /**
     * @var DateTimeImmutable Read-only. Time the resource was created.
     */
    #[MapFrom('created_at')]
    #[CastWith(AccountingDateTimeImmutableCaster::class, isUtc: true)]
    public ?DateTimeImmutable $createdAt;

    /**
     * @var DateTimeImmutable Read-only. Time the resource was updated.
     */
    #[MapFrom('updated_at')]
    #[CastWith(AccountingDateTimeImmutableCaster::class, isUtc: true)]
    public ?DateTimeImmutable $updatedAt;

    public function getContent(): array
    {
        $data = $this
            ->except('name')
            ->except('amount')
            ->except('taxId')
            ->except('createdAt')
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
