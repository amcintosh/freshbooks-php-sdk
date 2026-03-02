<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use DateTimeImmutable;
use amcintosh\FreshBooks\Model\DataModel;
use amcintosh\FreshBooks\Util;

/**
 * System-wide taxes for invoices.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/taxes
 */
class Tax implements DataModel
{
    public const RESPONSE_FIELD = 'tax';

    /**
     * @var int Get the unique identifier of this tax within this business.
     */
    public ?int $id;

    /**
     * @var string Unique identifier of account the tax exists on.
     */
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
    public ?int $taxId;

    /**
     * @var DateTimeImmutable The time of last modification.
     */
    public ?DateTimeImmutable $updated;

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->accountingSystemId = $data['accounting_systemid'] ?? null;
        $this->amount = $data['amount'] ?? null;
        $this->name = $data['name'] ?? null;
        $this->number = $data['number'] ?? null;
        $this->taxId = $data['taxid'] ?? null;
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
        $data = array();
        Util::convertContent($data, 'amount', $this->amount);
        Util::convertContent($data, 'name', $this->name);
        Util::convertContent($data, 'number', $this->number);
        return $data;
    }
}
