<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Builder;

use amcintosh\FreshBooks\Builder\BuilderInterface;

/**
 * Builder for including relationships, sub-resources, or additional data in the response.
 *
 * @package amcintosh\FreshBooks\Builder
 * @link https://www.freshbooks.com/api/parameters
 */
class IncludesBuilder implements BuilderInterface
{
    public array $includes;

    /**
     * Builder for including relationships, sub-resources, or additional data in the response.
     *
     * @return void
     */
    public function __construct()
    {
        $this->includes = [];
    }

    /**
     * Add an include key to the builder.
     *
     * @param  string $key The key for the resource or data to include
     * @return self The IncludesBuilder instance
     */
    public function include(string $key): self
    {
        $this->includes[] = $key;
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
        $queryString = '';
        foreach ($this->includes as $include) {
            if (is_null($resourceName) or in_array($resourceName, ['AccountingResource', 'EventsResource'], true)) {
                $queryString .= "&include[]={$include}";
            } else {
                $queryString .= "&{$include}=true";
            }
        }
        return $queryString;
    }
}
