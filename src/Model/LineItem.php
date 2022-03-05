<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use DateTimeImmutable;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\Attributes\MapTo;
use Spatie\DataTransferObject\Caster;
use Spatie\DataTransferObject\DataTransferObject;
use amcintosh\FreshBooks\Model\DataModel;
use amcintosh\FreshBooks\Model\Caster\AccountingDateTimeImmutableCaster;
use amcintosh\FreshBooks\Model\Caster\MoneyCaster;

/**
 * Invoice lines are used to determine the amount of an invoice, in addition to
 * being able to tie the invoice to rebilled expenses. The invoice line type
 * determines whether a line is an amount or whether it refers to an unbilled expense.
 *
 * _Note:_ When updating lines with a PUT request, the request payload must
 * contain all the lines of the invoice that you wish to remain.
 *
 * @package amcintosh\FreshBooks\Model
 */
class LineItem extends DataTransferObject implements DataModel
{
    protected array $exceptKeys = ['updated'];

    /**
     * @var int Unique-to-this-invoice line id.
     */
    #[MapFrom('lineid')]
    #[MapTo('lineid')]
    public ?int $lineId;

    /**
     * @var Money Amount total of a line item, calculated from unit cost, quantity and tax.
     *
     * Money object containing amount and currency code.
     */
    #[CastWith(MoneyCaster::class)]
    public ?Money $amount;

    /**
     * @var string Description for the line item.
     */
    public ?string $description;

    /**
     * @var int Id of unbilled expense
     *
     * Required when invoice line type is rebilling expense (type = `1`), otherwise should be excluded.
     */
    #[MapFrom('expenseid')]
    #[MapTo('expenseid')]
    public ?int $expenseId;

    /**
     * @var string Name for the line item.
     */
    public ?string $name;

    /**
     * @var int Quantity of the line unit, multiplied against unit_cost to get amount.
     */
    #[MapFrom('qty')]
    #[MapTo('qty')]
    public ?float $quantity;

    /**
     * @var string First tax amount, in percentage, up to 3 decimal places.
     */
    public ?string $taxAmount1;

    /**
     * @var string Second tax amount, in percentage, up to 3 decimal places.
     */
    public ?string $taxAmount2;

    /**
     * @var string Name for the first tax on the line item.
     */
    public ?string $taxName1;

    /**
     * @var string Name for the second tax on the line item.
     */
    public ?string $taxName2;

    /**
     * @var string First tax number on the line item.
     */
    public ?string $taxNumber1;

    /**
     * @var string Second tax number on the line item.
     */
    public ?string $taxNumber2;

    /**
     * @var int Line item type. Either `0` (normal) or `1` for a rebilling expense line.
     */
    public ?int $type;

    /**
     * @var Money Unit cost of the line item.
     *
     * Money object containing amount and currency code.
     */
    #[CastWith(MoneyCaster::class)]
    #[MapFrom('unit_cost')]
    #[MapTo('unit_cost')]
    public ?Money $unitCost;

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
        return $this
            ->except('amount')
            ->except('updated')
            ->toArray();
    }
}
