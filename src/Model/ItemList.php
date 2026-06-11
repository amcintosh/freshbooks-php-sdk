<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use amcintosh\FreshBooks\Model\AccountingList;
use amcintosh\FreshBooks\Model\Item;

/**
 * Results of items list call containing list of items and pagination data.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/items
 */
class ItemList extends AccountingList
{
    public const RESPONSE_FIELD = 'items';

    public array $items;

    public function __construct(array $data = [])
    {
        parent::__construct($data);
        $this->items = $this->constructList($data[ItemList::RESPONSE_FIELD], Item::class);
    }
}
