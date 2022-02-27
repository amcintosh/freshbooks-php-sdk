<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\Casters\ArrayCaster;
use Spatie\DataTransferObject\DataTransferObject;
use amcintosh\FreshBooks\Model\Item;

/**
 * Results of items list call containing list of items and pagination data.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/items
 */
class ItemList extends DataTransferObject
{
    public const RESPONSE_FIELD = 'items';

    public int $page;

    public int $pages;

    #[MapFrom('per_page')]
    public int $perPage;

    public int $total;

    #[CastWith(ArrayCaster::class, itemType: Item::class)]
    public array $items;
}
