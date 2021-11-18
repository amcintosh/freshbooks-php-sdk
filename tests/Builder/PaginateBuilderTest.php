<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Tests\Builder;

use PHPUnit\Framework\TestCase;
use amcintosh\FreshBooks\Builder\PaginateBuilder;

final class PaginateBuilderTest extends TestCase
{
    public function testPaginatorBuild(): void
    {
        $paginator = new PaginateBuilder(1, 3);

        $this->assertSame('&page=1&per_page=3', $paginator->build(''));
    }

    public function testPaginatorMethods(): void
    {
        $paginator = new PaginateBuilder(1, 3);
        $paginator = $paginator->page(2)->perPage(4);
        $this->assertSame('&page=2&per_page=4', $paginator->build(''));
    }

    public function testPaginatorMinimumPage(): void
    {
        $paginator = new PaginateBuilder(0, 3);

        $this->assertSame('&page=1&per_page=3', $paginator->build(''));

        $paginator = $paginator->page(-1);

        $this->assertSame('&page=1&per_page=3', $paginator->build(''));
    }

    public function testPaginatorMaximumPerPage(): void
    {
        $paginator = new PaginateBuilder(1, 200);

        $this->assertSame('&page=1&per_page=100', $paginator->build(''));

        $paginator = $paginator->perPage(500);

        $this->assertSame('&page=1&per_page=100', $paginator->build(''));
    }
}
