<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Casters\ArrayCaster;
use amcintosh\FreshBooks\Model\AccountingList;
use amcintosh\FreshBooks\Model\Task;

/**
 * Results of tasks list call containing list of tasks and pagination data.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/tasks
 */
class TaskList extends AccountingList
{
    public const RESPONSE_FIELD = 'tasks';

    #[CastWith(ArrayCaster::class, itemType: Task::class)]
    public array $tasks;
}
