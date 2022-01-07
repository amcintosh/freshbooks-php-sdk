<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model\Caster;

use Spatie\DataTransferObject\Caster;
use Spryker\DecimalObject\Decimal;
use amcintosh\FreshBooks\Model\Money;

class MoneyCaster implements Caster
{
    /**
     * @param string|mixed $value
     *
     * @return Money
     */
    public function cast(mixed $value): Money
    {
        return new Money(
            amount: Decimal::create($value['amount']),
            code: $value['code']
        );
    }
}
