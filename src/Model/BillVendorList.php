<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\Attributes\MapTo;
use Spatie\DataTransferObject\Casters\ArrayCaster;

/**
 * Results of bill vendors list call containing list of vendors and pagination data.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/vendors
 */
class BillVendorList extends AccountingList
{
    public const RESPONSE_FIELD = 'bill_vendors';

    #[MapFrom('bill_vendors')]
    #[MapTo('bill_vendors')]
    #[CastWith(ArrayCaster::class, itemType: BillVendor::class)]
    public array $billVendors;
}
