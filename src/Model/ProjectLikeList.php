<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use amcintosh\FreshBooks\Model\DataModelList;
use amcintosh\FreshBooks\Model\Pages;

/**
 * Parent class for list results on project-like endpoints to share pagination details.
 *
 * @package amcintosh\FreshBooks\Model
 */
class ProjectLikeList extends DataModelList
{
    public ListMeta $meta;

    public function pages(): mixed
    {
        return new Pages($this->meta->page, $this->meta->pages, $this->meta->perPage, $this->meta->total);
    }

    public function __construct(array $data = [])
    {
        if (isset($data['meta'])) {
            $this->meta = new ListMeta($data['meta']);
        } else {
            $this->meta = new ListMeta([]);
        }
    }
}
