<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Casters\ArrayCaster;
use amcintosh\FreshBooks\Model\AccountingListLegacy;
use amcintosh\FreshBooks\Model\Invoice;

/**
 * Results of invoices list call containing list of invoices and pagination data.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/invoices
 */
class InvoiceList extends AccountingListLegacy
{
    public const RESPONSE_FIELD = 'invoices';

    #[CastWith(ArrayCaster::class, itemType: Invoice::class)]
    public array $invoices;
}
