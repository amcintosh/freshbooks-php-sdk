<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Casters\ArrayCaster;
use amcintosh\FreshBooks\Model\AccountingList;
use amcintosh\FreshBooks\Model\Tax;

/**
 * Results of taxes list call containing list of taxes and pagination data.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/taxes
 */
class TaxList extends AccountingList
{
    public const RESPONSE_FIELD = 'taxes';

    #[CastWith(ArrayCaster::class, itemType: Tax::class)]
    public array $taxes;
}
