<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use DateTimeImmutable;
use amcintosh\FreshBooks\Model\DataModel;
use amcintosh\FreshBooks\Model\Money;
use amcintosh\FreshBooks\Util;

/**
 * Items are stored from invoice lines to make invoicing easier in the future.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/items
 */
class Item implements DataModel
{
    public const RESPONSE_FIELD = 'item';

    /**
     * @var int|null The unique identifier of this item within this business.
     */
    public ?int $id;

    /**
     * @var int|null Duplicate of id.
     */
    public ?int $itemId;

    /**
     * @var string|null Unique identifier of account the client exists on.
     */
    public ?string $accountingSystemId;

    /**
     * @var string|null Descriptive text for item.
     */
    public ?string $description;

    /**
     * @var string|null Decimal-string count of inventory.
     */
    public ?string $inventory;

    /**
     * @var string|null Descriptive name of item.
     */
    public ?string $name;

    /**
     * @var float|null Decimal-string quantity to multiply unit cost by.
     */
    public ?float $quantity;

    /**
     * @var string|null Id for a specific item or product, used in inventory management.
     */
    public ?string $sku;

    /**
     * @var int|null Id of the first tax to apply to this item.
     */
    public ?int $tax1;

    /**
     * @var int|null Id of the second tax to apply to this item.
     */
    public ?int $tax2;

    /**
     * @var Money|null Unit cost of the line item.
     *
     * Money object containing amount and currency code.
     */
    public ?Money $unitCost = null;

    /**
     * @var DateTimeImmutable|null The time of last modification.
     */
    public ?DateTimeImmutable $updated = null;

    /**
     * @var int|null The visibility state: active, deleted, or archived
     *
     * See [FreshBooks API - Active and Deleted Objects](https://www.freshbooks.com/api/active_deleted)
     */
    public ?int $visState;

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->itemId = $data['itemid'] ?? null;
        $this->accountingSystemId = $data['accounting_systemid'] ?? null;
        $this->description = $data['description'] ?? null;
        $this->inventory = $data['inventory'] ?? null;
        $this->name = $data['name'] ?? null;
        $this->quantity = isset($data['qty']) ? (float)$data['qty'] : null;
        $this->sku = $data['sku'] ?? null;
        $this->tax1 = $data['tax1'] ?? null;
        $this->tax2 = $data['tax2'] ?? null;

        if (isset($data['unit_cost']) && is_array($data['unit_cost'])) {
            $this->unitCost = new Money($data['unit_cost']['amount'], $data['unit_cost']['code']);
        }

        if (isset($data['updated'])) {
            $this->updated = Util::getAccountingDateTime($data['updated']);
        }

        $this->visState = $data['vis_state'] ?? null;
    }

    /**
     * Get the data as an array to POST or PUT to FreshBooks, removing any read-only fields.
     *
     * @return array
     */
    public function getContent(): array
    {
        $data = [];
        Util::convertContent($data, 'description', $this->description);
        Util::convertContent($data, 'inventory', $this->inventory);
        Util::convertContent($data, 'name', $this->name);
        Util::convertContent($data, 'qty', $this->quantity);
        Util::convertContent($data, 'sku', $this->sku);
        Util::convertContent($data, 'tax1', $this->tax1);
        Util::convertContent($data, 'tax2', $this->tax2);

        if (!is_null($this->unitCost)) {
            $data['unit_cost'] = $this->unitCost->getContent();
        }

        return $data;
    }
}
