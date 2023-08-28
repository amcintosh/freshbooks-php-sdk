<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model\Caster;

use Spatie\DataTransferObject\Caster;
use Spryker\DecimalObject\Decimal;
use amcintosh\FreshBooks\Model\Money;

class MoneyResponseCaster implements Caster
{
    /**
     * @param string|mixed $value
     *
     * @return Money
     */
    public function cast(mixed $value): Money|null
    {
        if (empty($value) || !isset($value[0]['amount'])) {
            return null;
        }

        return new Money(
            amount: Decimal::create($value[0]['amount']['amount']),
            code: $value[0]['amount']['code']
        );
    }
}
