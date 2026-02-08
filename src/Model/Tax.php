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
 * System-wide taxes for invoices.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/taxes
 */
class Tax extends DataTransferObject implements DataModelLegacy
{
    public const RESPONSE_FIELD = 'tax';

    /**
     * @var int Get the unique identifier of this tax within this business.
     */
    public ?int $id;

    /**
     * @var string Unique identifier of account the tax exists on.
     */
    #[MapFrom('accounting_systemid')]
    public ?string $accountingSystemId;

    /**
     * @var string Percentage value of tax.
     */
    public ?string $amount;

    /**
     * @var string Identifiable name for the tax.
     *
     * Eg. "GST"
     */
    public ?string $name;

    /**
     * @var string An external number that identifies your tax submission.
     */
    public ?string $number;

    /**
     * @var int Duplicate of id.
     */
    #[MapFrom('taxid')]
    public ?int $taxId;

    /**
     * @var DateTimeImmutable The time of last modification.
     */
    #[CastWith(AccountingDateTimeImmutableCaster::class)]
    public ?DateTimeImmutable $updated;

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
            ->except('taxId')
            ->except('updated')
            ->toArray();
        foreach ($data as $key => $value) {
            if (is_null($value)) {
                unset($data[$key]);
            }
        }
        return $data;
    }
}
