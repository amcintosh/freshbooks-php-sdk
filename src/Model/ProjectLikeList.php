<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\DataTransferObject;
use amcintosh\FreshBooks\Model\Pages;

/**
 * Parent class for list results on project-like endpoints to share pagination details.
 *
 * @package amcintosh\FreshBooks\Model
 */
class ProjectLikeList extends DataTransferObject
{
    public ListMeta $meta;

    public function pages(): mixed
    {
        return new Pages($this->meta->page, $this->meta->pages, $this->meta->perPage, $this->meta->total);
    }
}
