<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use Spatie\DataTransferObject\DataTransferObject;
use Spryker\DecimalObject\Decimal;

/**
 * Monetary amount represented by a decimal value and a currency code.
 *
 * @package amcintosh\FreshBooks\Model
 */
class Money extends DataTransferObject
{
    /**
     * @var Spryker\DecimalObject\Decimal Monetary amount with decimal places appropriate to the currency.
     */
    public Decimal $amount;

    /**
     * @var string The three-letter currency code
     *
     * Eg. USD, CAD, EUR, GBP
     */
    public string $code;


    /**
     * __construct Create a money object
     *
     * @param  mixed $amount The amount of money. to be converted into a `Decimal` type. Eg. 19.99, '19.99'
     * @param  mixed $code The three-letter currency code. Eg. USD, CAD, EUR, GBP
     * @return void
     */
    public function __construct(mixed $amount, string $code)
    {
        $this->amount = Decimal::create($amount);
        $this->code = $code;
    }

    protected function parseArray(array $array): array
    {
        foreach ($array as $key => $value) {
            if ($value instanceof Decimal) {
                $array[$key] = $value->toString();
            }
        }
        return $array;
    }
}
