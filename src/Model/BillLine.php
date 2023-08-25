<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use amcintosh\FreshBooks\Model\Caster\ExpenseCategoryCaster;
use amcintosh\FreshBooks\Model\Caster\MoneyCaster;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\Attributes\MapTo;
use Spatie\DataTransferObject\DataTransferObject;

/**
 * Bill lines are used to determine the amount of a bill.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/bills
 */
class BillLine extends DataTransferObject
{
    protected array $exceptKeys = ['id', 'category', 'listIndex', 'amount', 'totalAmount', 'taxAmount1', 'taxAmount2'];

    /**
     * @var int Id of related expense category.
     */
    #[MapFrom('categoryid')]
    #[MapTo('categoryid')]
    public ?int $categoryId;

    /**
     * @var ExpenseCategory Read-only. Related expense category.
     */
    #[CastWith(ExpenseCategoryCaster::class)]
    public ?ExpenseCategory $category;

    /**
     * @var int Read-only. Line id.
     */
    public ?int $id;

    /**
     * @var int Read-only. Line number on the Bill.
     */
    #[MapFrom('list_index')]
    public ?int $listIndex;

    /**
     * @var string Description for the line item.
     */
    public ?string $description;

    /**
     * @var int Quantity of the line unit.
     */
    public ?int $quantity;

    /**
     * @var Money Unit cost of the line item.
     */
    #[MapFrom('unit_cost')]
    #[MapTo('unit_cost')]
    #[CastWith(MoneyCaster::class)]
    public ?Money $unitCost;

    /**
     * @var Money Read-only. Total amount calculated from quantity and unit_cote.
     */
    #[CastWith(MoneyCaster::class)]
    public ?Money $amount;

    /**
     * @var Money Read-only. Calculated total amount of the bill line.
     */
    #[MapFrom('total_amount')]
    #[CastWith(MoneyCaster::class)]
    public ?Money $totalAmount;

    /**
     * @var string Name for the first tax on the bill line.
     */
    #[MapFrom('tax_name1')]
    #[MapTo('tax_name1')]
    public ?string $taxName1;

    /**
     * @var string Name for the second tax on the bill line.
     */
    #[MapFrom('tax_name2')]
    #[MapTo('tax_name2')]
    public ?string $taxName2;

    /**
     * @var int Percentage of first tax to 2 decimal places.
     */
    #[MapFrom('tax_percent1')]
    #[MapTo('tax_percent1')]
    public ?int $taxPercent1;

    /**
     * @var int Percentage of second tax to 2 decimal places.
     */
    #[MapFrom('tax_percent2')]
    #[MapTo('tax_percent2')]
    public ?int $taxPercent2;

    /**
     * @var string Id of first tax authority.
     */
    #[MapFrom('tax_authorityid1')]
    #[MapTo('tax_authorityid1')]
    public ?string $taxAuthorityId1;

    /**
     * @var string Id of second tax authority.
     */
    #[MapFrom('tax_authorityid2')]
    #[MapTo('tax_authorityid2')]
    public ?string $taxAuthorityId2;

    /**
     * @var Money Read-only. Calculated from tax details.
     */
    #[MapFrom('tax_amount1')]
    #[CastWith(MoneyCaster::class)]
    public ?Money $taxAmount1;

    /**
     * @var Money Read-only. Calculated from tax details.
     */
    #[MapFrom('tax_amount2')]
    #[CastWith(MoneyCaster::class)]
    public ?Money $taxAmount2;
}
