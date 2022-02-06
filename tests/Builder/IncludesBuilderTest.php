<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Tests\Builder;

use DateTime;
use PHPUnit\Framework\TestCase;
use amcintosh\FreshBooks\Builder\IncludesBuilder;

final class IncludesBuilderTest extends TestCase
{
    public function testIncludeBuildAccounting(): void
    {
        $builder = new IncludesBuilder();
        $builder->include("late_reminders");

        $this->assertSame('&include[]=late_reminders', $builder->build('AccountingResource'));
    }

    public function testIncludeBuildAccountingDefault(): void
    {
        $builder = new IncludesBuilder();
        $builder->include("late_reminders");

        $this->assertSame('&include[]=late_reminders', $builder->build());
    }

    public function testIncludeBuildEvents(): void
    {
        $builder = new IncludesBuilder();
        $builder->include("late_reminders");

        $this->assertSame('&include[]=late_reminders', $builder->build('EventsResource'));
    }

    public function testIncludeBuildProject(): void
    {
        $builder = new IncludesBuilder();
        $builder->include("include_overdue_fees");

        $this->assertSame('&include_overdue_fees=true', $builder->build('ProjectsResource'));
    }

    public function testIncludeBuildChainable(): void
    {
        $builder = new IncludesBuilder();
        $builder->include("late_reminders")->include('late_fees');

        $this->assertSame('&include[]=late_reminders&include[]=late_fees', $builder->build());
    }
}
