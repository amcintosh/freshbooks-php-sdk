<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model\Caster;

use amcintosh\FreshBooks\Model\ExpenseCategory;
use DateTimeImmutable;
use DateTimeZone;
use Spatie\DataTransferObject\Caster;

class ExpenseCategoryCaster implements Caster
{
    private const TIMEZONE = 'US/Eastern';
    private const FORMAT = 'Y-m-d H:i:s';

    /**
     * @param string|mixed $value
     *
     * @return ExpenseCategory
     */
    public function cast(mixed $value): ExpenseCategory
    {
        $category = new ExpenseCategory();
        $category->category = $value['category'];
        $category->categoryId = $value['categoryid'];
        $category->createdAt = $this->castDateTime($value['created_at']);
        $category->id = $value['id'];
        $category->isCogs = ($value['is_cogs'] === 'true');
        $category->isEditable = ($value['is_editable'] === 'true');
        $category->parentId = $value['parentid'];
        $category->updatedAt = $this->castDateTime($value['updated_at']);
        $category->visState = $value['vis_state'];
        return $category;
    }

    private function castDateTime(string $value): DateTimeImmutable|bool
    {
        $parsedDate = DateTimeImmutable::createFromFormat($this::FORMAT, $value, new DateTimeZone($this::TIMEZONE));
        return $parsedDate->setTimeZone(new DateTimeZone('UTC'));
    }
}
