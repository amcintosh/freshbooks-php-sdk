<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Tests\Model;

use DateTime;
use PHPUnit\Framework\TestCase;
use amcintosh\FreshBooks\Model\ExpenseCategory;
use amcintosh\FreshBooks\Model\VisState;

final class ExpenseCategoryTest extends TestCase
{
    private $sampleExpenseCategoryData = '{"category": {
        "category": "Accident Insurance",
        "categoryid": 123,
        "created_at": "2022-10-17 14:29:14",
        "id": 123,
        "is_cogs": false,
        "is_editable": false,
        "parentid": 122,
        "transaction_posted": false,
        "updated_at": "2022-10-17 14:29:14",
        "vis_state": 0
    }}';

    public function testExpenseCategoryFromResponse(): void
    {
        $expenseCategoryData = json_decode($this->sampleExpenseCategoryData, true);

        $expenseCategory = new ExpenseCategory($expenseCategoryData[ExpenseCategory::RESPONSE_FIELD]);

        $this->assertSame(123, $expenseCategory->id);
        $this->assertSame(123, $expenseCategory->categoryId);
        $this->assertSame('Accident Insurance', $expenseCategory->category);
        $this->assertEquals(new DateTime('2022-10-17T18:29:14Z'), $expenseCategory->createdAt);
        $this->assertFalse($expenseCategory->isCogs);
        $this->assertFalse($expenseCategory->isEditable);
        $this->assertSame(122, $expenseCategory->parentId);
        $this->assertFalse($expenseCategory->transactionPosted);
        $this->assertEquals(new DateTime('2022-10-17T18:29:14Z'), $expenseCategory->updatedAt);
        $this->assertSame(VisState::ACTIVE, $expenseCategory->visState);
    }
}
