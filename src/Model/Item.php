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
use amcintosh\FreshBooks\Model\Caster\MoneyCaster;

/**
 * Items are stored from invoice lines to make invoicing easier in the future.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/items
 */
class Item extends DataTransferObject implements DataModelLegacy
{
    public const RESPONSE_FIELD = 'item';

    /**
     * @var int The unique identifier of this item within this business.
     */
    public ?int $id;

    /**
     * @var int Duplicate of id.
     */
    #[MapFrom('itemid')]
    public ?int $itemId;

    /**
     * @var string Unique identifier of account the client exists on.
     */
    #[MapFrom('accounting_systemid')]
    public ?string $accountingSystemId;

    /**
     * @var string Descriptive text for item.
     */
    public ?string $description;

    /**
     * @var string Decimal-string count of inventory.
     */
    public ?string $inventory;

    /**
     * @var string Descriptive name of item.
     */
    public ?string $name;

    /**
     * @var int Decimal-string quantity to multiply unit cost by.
     */
    #[MapFrom('qty')]
    #[MapTo('qty')]
    public ?float $quantity;

    /**
     * @var string Id for a specific item or product, used in inventory management.
     */
    public ?string $sku;

    /**
     * @var int Id of the first tax to apply to this item.
     */
    public ?int $tax1;

    /**
     * @var int Id of the second tax to apply to this item.
     */
    public ?int $tax2;

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
            ->except('itemId')
            ->except('accountingSystemId')
            ->except('updated')
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
