<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

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
    public array $expenses;

    public function __construct(array $data = [])
    {
        parent::__construct($data);
        $this->expenses = $this->constructList($data['expenses'], Expense::class);
    }
}
