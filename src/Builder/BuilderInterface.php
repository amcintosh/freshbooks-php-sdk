<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Builder;

interface BuilderInterface
{
    /**
     * Builds the query string parameters from the Builder.
     *
     * @param  string $resourceName The type of resource to generate the query string for.
     *               Eg. AccountingResource, ProjectsResource
     * @return string The built query string
     */
    public function build(?string $resourceName = null): string;
}
