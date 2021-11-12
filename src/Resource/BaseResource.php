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

    public function __construct(string $singleModel, string $listModel)
    {
        $this->singleModel = $singleModel;
        $this->listModel = $listModel;
    }

    protected function buildQueryString(?array $builders): string
    {
        $queryString = '';
        if (is_null($builders)) {
            return $queryString;
        }
        foreach ($builders as $builder) {
            $queryString .= $builder->build();
        }
        if ($queryString !== '') {
            $queryString = '?' . ltrim($queryString, '&');
        }
        return $queryString;
    }
}
