<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use amcintosh\FreshBooks\Model\AccountingList;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Casters\ArrayCaster;

/**
 * Results of bills list call containing list of bills and pagination data.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/bills
 */
class BillList extends AccountingList
{
    public const RESPONSE_FIELD = 'bills';

    #[CastWith(ArrayCaster::class, itemType: Bill::class)]
    public array $bills;
}
