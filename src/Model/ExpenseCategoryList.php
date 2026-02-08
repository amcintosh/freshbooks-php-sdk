<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Casters\ArrayCaster;
use amcintosh\FreshBooks\Model\AccountingListLegacy;
use amcintosh\FreshBooks\Model\ExpenseCategory;

/**
 * Results of expense category list call containing list of expense categories and pagination data.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/expense_categories
 */
class ExpenseCategoryList extends AccountingListLegacy
{
    public const RESPONSE_FIELD = 'categories';

    #[CastWith(ArrayCaster::class, itemType: ExpenseCategory::class)]
    public array $categories;
}
