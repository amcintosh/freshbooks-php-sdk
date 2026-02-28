<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Tests\Model;

use Spryker\DecimalObject\Decimal;
use PHPUnit\Framework\TestCase;
use amcintosh\FreshBooks\Model\Money;

final class MoneyTest extends TestCase
{
    public function testMoneyConstructor(): void
    {
        $money = new Money('19.99', 'CAD');

        $this->assertEquals(Decimal::create(19.99), $money->amount);
        $this->assertSame('CAD', $money->code);

        $money = new Money(20, 'CAD');

        $this->assertEquals(Decimal::create(20), $money->amount);
        $this->assertSame('CAD', $money->code);
    }

    public function testMoneyParseArray(): void
    {
        $money = new Money(19.99, 'CAD');

        $expected = [
            'amount' => '19.99',
            'code' => 'CAD'
        ];
        $this->assertEquals($expected, $money->getContent());
    }
}
