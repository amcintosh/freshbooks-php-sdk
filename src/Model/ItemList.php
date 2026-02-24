<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Casters\ArrayCaster;
use amcintosh\FreshBooks\Model\AccountingListLegacy;
use amcintosh\FreshBooks\Model\Item;

/**
 * Results of items list call containing list of items and pagination data.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/items
 */
class ItemList extends AccountingListLegacy
{
    public const RESPONSE_FIELD = 'items';

    #[CastWith(ArrayCaster::class, itemType: Item::class)]
    public array $items;
}
