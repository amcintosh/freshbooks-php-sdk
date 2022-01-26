<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\Casters\ArrayCaster;
use Spatie\DataTransferObject\DataTransferObject;
use amcintosh\FreshBooks\Model\Expense;

/**
 * Results of expenses list call containing list of expenses and pagination data.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/expenses
 */
class ExpenseList extends DataTransferObject
{
    public const RESPONSE_FIELD = 'expenses';

    public int $page;

    public int $pages;

    #[MapFrom('per_page')]
    public int $perPage;

    public int $total;

    #[CastWith(ArrayCaster::class, itemType: Expense::class)]
    public array $expenses;
}
