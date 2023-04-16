<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Builder;

use amcintosh\FreshBooks\Builder\BuilderInterface;

/**
 * Builder for making paginated list queries.
 *
 * @package amcintosh\FreshBooks\Builder
 * @link https://www.freshbooks.com/api/parameters
 */
class PaginateBuilder implements BuilderInterface
{
    private const MAX_PER_PAGE = 100;
    private const MIN_PAGE = 1;

    public int $page;
    public int $perPage;

    /**
     * Builder for making paginated list queries.
     * Has two attributes, `page` and `perPage`. When a `PaginateBuilder` object is passed
     * to a `list()` call, the call will fetch only the `perPage` number of results and will
     * fetch the results offset by `page`.
     *
     * @param  int $page The page of results to return in the API call
     * @param  int $perPage The number of results to return in each API call
     * @return void
     */
    public function __construct(int $page, int $perPage)
    {
        $this->page = max($page, self::MIN_PAGE);
        $this->perPage = min($perPage, self::MAX_PER_PAGE);
    }

    /**
     * Set the page you wish to fetch in a list call. Can be chained.
     *
     * @param  int $page The page of results to return in the API call
     * @return self The PaginateBuilder instance
     */
    public function page(int $page): self
    {
        $this->page = max($page, self::MIN_PAGE);
        return $this;
    }

    /**
     * Set the number of results you wish to fetch in a page of a list call. Can be chained.
     *
     * The page size is capped at 100.
     *
     * @param  int $perPage The number of results to return in each API call
     * @return self The PaginateBuilder instance
     */
    public function perPage(int $perPage): self
    {
        $this->perPage = min($perPage, self::MAX_PER_PAGE);
        return $this;
    }

    /**
     * Builds the query string parameters from the Builder.
     *
     * @param  string $resourceName The type of resource to generate the query string for.
     *               Eg. AccountingResource, ProjectsResource
     * @return string The built query string
     */
    public function build(string $resourceName = null): string
    {
        return "&page={$this->page}&per_page={$this->perPage}";
    }
}
