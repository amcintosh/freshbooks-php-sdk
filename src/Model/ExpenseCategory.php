<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use DateTimeImmutable;
use amcintosh\FreshBooks\Model\DataModel;
use amcintosh\FreshBooks\Util;

/**
 * Expense Categories are used to group expenses together to aid in expense tracking.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/expense_categories
 */
class ExpenseCategory implements DataModel
{
    public const RESPONSE_FIELD = 'category';

    /**
     * @var int The unique identifier of this expense category within this business.
     */
    public ?int $id;

    /**
     * @var int Duplicate of id
     */
    public ?int $categoryId;

    /**
     * @var string Name for this category, e.g. “Advertising”
     */
    public ?string $category;

    /**
     * @var DateTimeImmutable The time of category creation.
     */
    public ?DateTimeImmutable $createdAt = null;

    /**
     * @var bool Represents cost of goods sold
     */
    public ?bool $isCogs;

    /**
     * @var bool Can this category be edited
     */
    public ?bool $isEditable;

    /**
     * @var int Category id of parent category
     */
    public ?int $parentId;

    /**
     * @var bool
     */
    public ?bool $transactionPosted;

    /**
     * @var DateTimeImmutable The time of last modification.
     */
    public ?DateTimeImmutable $updatedAt = null;

    /**
     * @var int The visibility state: active, deleted, or archived
     *
     * See [FreshBooks API - Active and Deleted Objects](https://www.freshbooks.com/api/active_deleted)
     */
    public ?int $visState;

    // Includes

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->categoryId = $data['categoryid'] ?? null;
        $this->category = $data['category'] ?? null;
        if (isset($data['created_at'])) {
            $this->createdAt = Util::getAccountingDateTime($data['created_at']);
        }
        $this->isCogs = $data['is_cogs'] ?? null;
        $this->isEditable = $data['is_editable'] ?? null;
        $this->parentId = $data['parentid'] ?? null;
        $this->transactionPosted = $data['transaction_posted'] ?? null;
        if (isset($data['updated_at'])) {
            $this->updatedAt = Util::getAccountingDateTime($data['updated_at']);
        }
        $this->visState = $data['vis_state'] ?? null;
    }

    /**
     * Get the data as an array to POST or PUT to FreshBooks, removing any read-only fields.
     *
     * @return array
     */
    public function getContent(): array
    {
        return array();
    }
}
