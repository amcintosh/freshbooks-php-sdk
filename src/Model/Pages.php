<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

/**
 * List call pagination details.
 *
 * @package amcintosh\FreshBooks\Model
 */
class Pages
{
    public int $page;

    public int $pages;

    public int $perPage;

    public int $total;

    /**
     * __construct Create a pages object
     *
     * @param  int $page The current page of results
     * @param  int $pages The number of pages of results
     * @param  int $perPage The number of results in each page
     * @param  int $total The total number of results
     * @return void
     */
    public function __construct(int $page, int $pages, int $perPage, int $total)
    {
        $this->page = $page;
        $this->pages = $pages;
        $this->perPage = $perPage;
        $this->total = $total;
    }
}
