<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\DataTransferObject;

/**
 * List Meta object for pagination details in project-like responses.
 *
 * @package amcintosh\FreshBooks\Model
 */
class ListMeta extends DataTransferObject
{
    public int $page;

    public int $pages;

    #[MapFrom('per_page')]
    public int $perPage;

    public int $total;
}
