<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use DateTimeImmutable;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\Attributes\MapTo;
use Spatie\DataTransferObject\Caster;
use Spatie\DataTransferObject\DataTransferObject;
use amcintosh\FreshBooks\Model\Caster\AccountingDateTimeImmutableCaster;

/**
 * Expense Categories are used to group expenses together to aid in expense tracking.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/expense_categories
 */
class ExpenseCategory extends DataTransferObject
{
    public const RESPONSE_FIELD = 'category';

    /**
     * @var int The unique identifier of this expense category within this business.
     */
    public ?int $id;

    /**
     * @var int Duplicate of id
     */
    #[MapFrom('categoryid')]
    public ?int $categoryId;

    /**
     * @var string Name for this category, e.g. “Advertising”
     */
    public ?string $category;

    /**
     * @var DateTimeImmutable The time of category creation.
     */
    #[MapFrom('created_at')]
    #[CastWith(AccountingDateTimeImmutableCaster::class)]
    public ?DateTimeImmutable $createdAt;

    /**
     * @var bool Represents cost of goods sold
     */
    #[MapFrom('is_cogs')]
    public ?bool $isCogs;

    /**
     * @var bool Can this category be edited
     */
    #[MapFrom('is_editable')]
    public ?bool $isEditable;

    /**
     * @var int Category id of parent category
     */
    #[MapFrom('parentid')]
    public ?int $parentId;

    /**
     * @var bool
     */
    #[MapFrom('transaction_posted')]
    public ?bool $transactionPosted;

    /**
     * @var DateTimeImmutable The time of last modification.
     */
    #[MapFrom('updated_at')]
    #[CastWith(AccountingDateTimeImmutableCaster::class)]
    public ?DateTimeImmutable $updatedAt;

    /**
     * @var int The visibility state: active, deleted, or archived
     *
     * See [FreshBooks API - Active and Deleted Objects](https://www.freshbooks.com/api/active_deleted)
     */
    #[MapFrom('vis_state')]
    public ?int $visState;

    // Includes
}
