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
}
