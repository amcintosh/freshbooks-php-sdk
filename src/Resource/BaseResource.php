<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Resource;

class BaseResource
{
    protected const GET = 'GET';
    protected const POST = 'POST';
    protected const PUT = 'PUT';
    protected const PATCH = 'PATCH';
    protected const DELETE = 'DELETE';

    protected function buildQueryString(?array $builders): string
    {
        $queryString = '';
        if (is_null($builders)) {
            return $queryString;
        }
        $builders = array_filter($builders);
        foreach ($builders as $builder) {
            $queryString .= $builder->build();
        }
        if ($queryString !== '') {
            $queryString = '?' . ltrim($queryString, '&');
        }
        return $queryString;
    }
}
