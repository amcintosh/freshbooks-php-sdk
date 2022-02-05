<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Tests\Builder;

use DateTime;
use DateTimeZone;
use PHPUnit\Framework\TestCase;
use amcintosh\FreshBooks\Builder\FilterBuilder;

final class FilterBuilderTest extends TestCase
{
    public function testBooleanTrue(): void
    {
        $builder = new FilterBuilder();
        $builder->boolean('active', true);

        $this->assertSame('&active=true', $builder->build('AccountingResource'));
    }

    public function testBooleanFalse(): void
    {
        $builder = new FilterBuilder();
        $builder->boolean('active', false);

        $this->assertSame('&active=false', $builder->build('AccountingResource'));
    }

    public function testEqualsAccounting(): void
    {
        $builder = new FilterBuilder();
        $builder->equals('username', 'Bob');

        $this->assertSame('&search[username]=Bob', $builder->build('AccountingResource'));
    }

    public function testEqualsAccountingDefault(): void
    {
        $builder = new FilterBuilder();
        $builder->equals('username', 'Bob');

        $this->assertSame('&search[username]=Bob', $builder->build());
    }

    public function testEqualsProject(): void
    {
        $builder = new FilterBuilder();
        $builder->equals('username', 'Bob');

        $this->assertSame('&username=Bob', $builder->build('ProjectResource'));
    }

    public function testInListPlural(): void
    {
        $builder = new FilterBuilder();
        $builder->inList('userids', [1, 2]);

        $this->assertSame('&search[userids][]=1&search[userids][]=2', $builder->build());
    }

    public function testInListPluralized(): void
    {
        $builder = new FilterBuilder();
        $builder->inList('userid', [1, 2]);

        $this->assertSame('&search[userids][]=1&search[userids][]=2', $builder->build());
    }

    public function testLike(): void
    {
        $builder = new FilterBuilder();
        $builder->like('user_like', 'leaf');

        $this->assertSame('&search[user_like]=leaf', $builder->build());
    }

    public function testBetweenMinMax(): void
    {
        $builder = new FilterBuilder();
        $builder->between('amount', 1, 10);

        $this->assertSame('&search[amount_min]=1&search[amount_max]=10', $builder->build());
    }

    public function testBetweenMinSpecified(): void
    {
        $builder = new FilterBuilder();
        $builder->between('amount', min: 1);

        $this->assertSame('&search[amount_min]=1', $builder->build());
    }

    public function testBetweenMaxSpecified(): void
    {
        $builder = new FilterBuilder();
        $builder->between('amount', max: 10);

        $this->assertSame('&search[amount_max]=10', $builder->build());
    }

    public function testBetweenDateString(): void
    {
        $builder = new FilterBuilder();
        $builder->between('start_date', min: '2020-10-17');

        $this->assertSame('&search[start_date]=2020-10-17', $builder->build());
    }

    public function testBetweenDate(): void
    {
        $builder = new FilterBuilder();
        $builder->between('start_date', min: new DateTime('2020-10-17'));

        $this->assertSame('&search[start_date]=2020-10-17', $builder->build());
    }

    public function testBetweenDateTime(): void
    {
        $builder = new FilterBuilder();
        $builder->between('start_date', min: new DateTime('2020-10-17T07:03:01.012345Z'));

        $this->assertSame('&search[start_date]=2020-10-17', $builder->build());
    }

    public function testDatetimeString(): void
    {
        $builder = new FilterBuilder();
        $builder->datetime('updated_since', '2020-10-17T07:03:01');

        $this->assertSame('&updated_since=2020-10-17T07:03:01', $builder->build());
    }

    public function testDatetimeDateTime(): void
    {
        $builder = new FilterBuilder();
        $builder->datetime('updated_since', new DateTime('2020-10-17T07:03:01.012345Z', new DateTimeZone('UTC')));

        $this->assertSame('&updated_since=2020-10-17T07:03:01', $builder->build());
    }
}
