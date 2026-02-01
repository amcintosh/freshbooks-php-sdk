<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Builder;

use amcintosh\FreshBooks\Builder\BuilderInterface;

/**
 * Builder for making sorted list queries.
 *
 * @package amcintosh\FreshBooks\Builder
 * @link https://www.freshbooks.com/api/parameters
 */
class SortBuilder implements BuilderInterface
{
    public ?string $sortKey;
    public bool $isAscending;

    /**
     * Builder for including sort by field data in a list request.
     *
     * @return void
     */
    public function __construct()
    {
        $this->sortKey = null;
        $this->isAscending = true;
    }

    /**
     * Alias for ascending().
     *
     * @param  string $key The field for the resource list to be sorted by.
     * @return void
     */
    public function asc(string $key): void
    {
        $this->ascending($key);
    }

    /**
     * Add a sort by the field in ascending order.
     *
     * @param  string $key The field for the resource list to be sorted by.
     * @return void
     */
    public function ascending(string $key): void
    {
        $this->sortKey = $key;
        $this->isAscending = true;
    }

    /**
     * Alias for descending().
     *
     * @param  string $key The field for the resource list to be sorted by.
     * @return void
     */
    public function desc(string $key): void
    {
        $this->descending($key);
    }

    /**
     * Add a sort by the field in descending order.
     *
     * @param  string $key The field for the resource list to be sorted by.
     * @return void
     */
    public function descending(string $key): void
    {
        $this->sortKey = $key;
        $this->isAscending = false;
    }

    /**
     * Builds the query string parameters from the Builder.
     *
     * @param  string $resourceName The type of resource to generate the query string for.
     *               Eg. AccountingResource, ProjectsResource
     * @return string The built query string
     */
    public function build(?string $resourceName = null): string
    {
        if (is_null($this->sortKey)) {
            return '';
        }

        if (is_null($resourceName) || in_array($resourceName, ['AccountingResource', 'EventsResource'], true)) {
            $suffix = $this->isAscending ? '_asc' : '_desc';
            return '&sort=' . $this->sortKey . $suffix;
        }
        $negate = $this->isAscending ? '' : '-';
        return '&sort=' . $negate . $this->sortKey;
    }
}
