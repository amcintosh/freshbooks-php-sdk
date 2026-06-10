<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use amcintosh\FreshBooks\Model\AccountingList;
use amcintosh\FreshBooks\Model\ExpenseCategory;

/**
 * Results of expense category list call containing list of expense categories and pagination data.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/expense_categories
 */
class ExpenseCategoryList extends AccountingList
{
    public array $categories;

    public function __construct(array $data = [])
    {
        parent::__construct($data);
        $this->categories = $this->constructList($data['categories'], ExpenseCategory::class);
    }
}
