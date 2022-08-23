<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model\Caster;

use Spatie\DataTransferObject\Caster;
use Spryker\DecimalObject\Decimal;

class DecimalCaster implements Caster
{
    /**
     * @param string|mixed $value
     *
     * @return Decimal
     */
    public function cast(mixed $value): Decimal
    {
        return Decimal::create($value);
    }
}
