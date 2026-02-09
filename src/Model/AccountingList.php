<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use amcintosh\FreshBooks\Model\DataModelList;
use amcintosh\FreshBooks\Model\Pages;

/**
 * Parent class for list results on accounting endpoints to share pagination details.
 *
 * @package amcintosh\FreshBooks\Model
 */
class AccountingList extends DataModelList
{
    public function pages(): mixed
    {
        return new Pages($this->page, $this->pages, $this->perPage, $this->total);
    }

    public function __construct(array $data = [])
    {
        $this->page = $data['page'] ?? null;
        $this->pages = $data['pages'] ?? null;
        $this->perPage = $data['per_page'] ?? null;
        $this->total = $data['total'] ?? null;
    }
}
