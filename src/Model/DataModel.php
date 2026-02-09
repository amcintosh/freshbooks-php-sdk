<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

interface DataModel
{
    public function getContent(): array;
}
