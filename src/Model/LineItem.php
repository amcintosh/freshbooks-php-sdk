<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use DateTimeImmutable;
use amcintosh\FreshBooks\Model\DataModel;
use amcintosh\FreshBooks\Util;

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
class LineItem implements DataModel
{
    protected array $exceptKeys = ['amount', 'taxNumber1', 'taxNumber2', 'updated'];

    /**
     * @var int Unique-to-this-invoice line id.
     */
    public ?int $lineId;

    /**
     * @var Money Amount total of a line item, calculated from unit cost, quantity and tax.
     *
     * Money object containing amount and currency code.
     */
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
    public ?int $expenseId;

    /**
     * @var string Name for the line item.
     */
    public ?string $name;

    /**
     * @var float Quantity of the line unit, multiplied against unit_cost to get amount.
     */
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
    public ?Money $unitCost;

    /**
     * @var DateTimeImmutable The time of last modification.
     */
    public ?DateTimeImmutable $updated;

    public function __construct(array $data = [])
    {
        $this->lineId = $data['lineid'] ?? null;
        if (isset($data['amount'])) {
            $this->amount = new Money($data['amount']['amount'], $data['amount']['code']);
        }
        $this->description = $data['description'] ?? null;
        $this->expenseId = $data['expenseid'] ?? null;
        $this->name = $data['name'] ?? null;
        $this->quantity = floatval($data['qty'] ?? 0);
        $this->taxAmount1 = $data['taxAmount1'] ?? null;
        $this->taxAmount2 = $data['taxAmount2'] ?? null;
        $this->taxName1 = $data['taxName1'] ?? null;
        $this->taxName2 = $data['taxName2'] ?? null;
        $this->taxNumber1 = $data['taxNumber1'] ?? null;
        $this->taxNumber2 = $data['taxNumber2'] ?? null;
        $this->type = $data['type'] ?? null;
        if (isset($data['unit_cost'])) {
            $this->unitCost = new Money($data['unit_cost']['amount'], $data['unit_cost']['code']);
        }
        if (isset($data['updated'])) {
            $this->updated = Util::getAccountingDateTime($data['updated']);
        }
    }

    /**
     * Get the data as an array to POST or PUT to FreshBooks, removing any read-only fields.
     *
     * @return array
     */
    public function getContent(): array
    {
        $data = [
            'lineid' => $this->lineId,
            'description' => $this->description,
            'expenseid' => $this->expenseId,
            'name' => $this->name,
            'qty' => $this->quantity,
            'taxAmount1' => $this->taxAmount1,
            'taxAmount2' => $this->taxAmount2,
            'taxName1' => $this->taxName1,
            'taxName2' => $this->taxName2,
            'type' => $this->type,
        ];
        Util::convertContent($data, 'unit_cost', $this->unitCost);
        return $data;
    }
}
