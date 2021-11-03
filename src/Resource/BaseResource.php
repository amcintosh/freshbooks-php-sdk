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

    public function __construct($model)
    {
        $this->model = $model;
    }

    protected function getModelName(): string
    {
        return strtolower((new \ReflectionClass($this->model))->getShortName());
    }
}
