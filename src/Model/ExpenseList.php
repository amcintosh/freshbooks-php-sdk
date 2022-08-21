<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Casters\ArrayCaster;
use amcintosh\FreshBooks\Model\AccountingList;
use amcintosh\FreshBooks\Model\Expense;

/**
 * Results of expenses list call containing list of expenses and pagination data.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/expenses
 */
class ExpenseList extends AccountingList
{
    public const RESPONSE_FIELD = 'expenses';

    #[CastWith(ArrayCaster::class, itemType: Expense::class)]
    public array $expenses;
}
