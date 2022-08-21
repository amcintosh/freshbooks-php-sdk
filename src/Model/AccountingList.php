<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\DataTransferObject;
use amcintosh\FreshBooks\Model\Pages;

/**
 * Parent class for list results on acocunting endpoints to share pagination details.
 *
 * @package amcintosh\FreshBooks\Model
 */
class AccountingList extends DataTransferObject
{
    public int $page;

    public int $pages;

    #[MapFrom('per_page')]
    public int $perPage;

    public int $total;

    public function pages(): mixed
    {
        return new Pages($this->page, $this->pages, $this->perPage, $this->total);
    }
}
