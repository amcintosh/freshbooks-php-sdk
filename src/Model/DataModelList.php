<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use amcintosh\FreshBooks\Model\Pages;

/**
 * Parent class for list results to share pagination details.
 *
 * @package amcintosh\FreshBooks\Model
 */
abstract class DataModelList
{
    public int $page;

    public int $pages;

    public int $perPage;

    public int $total;

    abstract public function pages(): mixed;

    protected function constructList(array $data, string $responseClass): array
    {
        $list = [];
        foreach ($data as $item) {
            $list[] = new $responseClass($item);
        }
        return $list;
    }

}
