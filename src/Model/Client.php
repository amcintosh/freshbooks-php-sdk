<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use Spatie\DataTransferObject\DataTransferObject;

class Client extends DataTransferObject
{
    public int $id;

    public string $organization;
}
