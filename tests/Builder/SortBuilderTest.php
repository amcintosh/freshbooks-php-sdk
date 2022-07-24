<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Tests\Builder;

use DateTime;
use PHPUnit\Framework\TestCase;
use amcintosh\FreshBooks\Builder\SortBuilder;

final class SortBuilderTest extends TestCase
{
    public function testSortBuildAccountingAscending(): void
    {
        $builder = new SortBuilder();
        $builder->ascending("invoice_date");

        $this->assertSame('&sort=invoice_date_asc', $builder->build('AccountingResource'));
    }

    public function testSortBuildAccountingAsc(): void
    {
        $builder = new SortBuilder();
        $builder->asc("invoice_date");

        $this->assertSame('&sort=invoice_date_asc', $builder->build('AccountingResource'));
    }

    public function testSortBuildAccountingDescending(): void
    {
        $builder = new SortBuilder();
        $builder->descending("invoice_date");

        $this->assertSame('&sort=invoice_date_desc', $builder->build('AccountingResource'));
    }

    public function testSortBuildAccountingDesc(): void
    {
        $builder = new SortBuilder();
        $builder->desc("invoice_date");

        $this->assertSame('&sort=invoice_date_desc', $builder->build('AccountingResource'));
    }

    public function testSortBuildProjectAscending(): void
    {
        $builder = new SortBuilder();
        $builder->ascending("due_date");

        $this->assertSame('&sort=due_date', $builder->build('ProjectsResource'));
    }

    public function testSortBuildProjectAsc(): void
    {
        $builder = new SortBuilder();
        $builder->asc("due_date");

        $this->assertSame('&sort=due_date', $builder->build('ProjectsResource'));
    }

    public function testSortBuildProjectDescending(): void
    {
        $builder = new SortBuilder();
        $builder->descending("due_date");

        $this->assertSame('&sort=-due_date', $builder->build('ProjectsResource'));
    }

    public function testSortBuildProjectDesc(): void
    {
        $builder = new SortBuilder();
        $builder->desc("due_date");

        $this->assertSame('&sort=-due_date', $builder->build('ProjectsResource'));
    }
}
