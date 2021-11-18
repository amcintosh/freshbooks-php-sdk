<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\Casters\ArrayCaster;
use Spatie\DataTransferObject\DataTransferObject;
use amcintosh\FreshBooks\Model\Invoice;

/**
 * Results of invoices list call containing list of invoices and pagination data.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/invoices
 */
class InvoiceList extends DataTransferObject
{
    public const RESPONSE_FIELD = 'invoices';

    public int $page;

    public int $pages;

    #[MapFrom('per_page')]
    public int $perPage;

    public int $total;

    #[CastWith(ArrayCaster::class, itemType: Invoice::class)]
    public array $invoices;
}
