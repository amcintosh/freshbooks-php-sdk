<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use DateTimeImmutable;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\Attributes\MapTo;
use Spatie\DataTransferObject\Caster;
use Spatie\DataTransferObject\DataTransferObject;
use amcintosh\FreshBooks\Model\DataModelLegacy;
use amcintosh\FreshBooks\Model\Caster\AccountingDateTimeImmutableCaster;
use amcintosh\FreshBooks\Model\Caster\MoneyCaster;

/**
 * Tasks in Freshbooks represent services that your business offers to clients.
 * Tasks are used to keep track of invoicing details of the service such as name and hourly rate.
 * Tasks are automatically created for each project service and updates to tasks are reflected in the
 * corresponding service and vice versa.
 *
 * In general, when working with a project, use the services endpoint. When working with an invoice, use tasks.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/tasks
 */
class Task extends DataTransferObject implements DataModelLegacy
{
    public const RESPONSE_FIELD = 'task';

    /**
     * @var int The unique identifier of this task within this business.
     */
    public ?int $id;

    /**
     * @var int Duplicate of id.
     */
    #[MapFrom('taskid')]
    public ?int $taskId;

    /**
     * @var string Unique identifier of account the client exists on.
     */
    #[MapFrom('accounting_systemid')]
    public ?string $accountingSystemId;


    /**
     * @var bool Whether this task billable.
     */
    public ?bool $billable;

    /**
     * @var string Descriptive text for task.
     *
     * e.g. Piloting based on expectations of the executive
     */
    public ?string $description;

    /**
     * @var string Descriptive name of task.
     *
     * e.g. Piloting
     */
    public ?string $name;

    /**
     * @var Money The hourly amount rate charged for task.
     *
     * Money object containing amount and currency code.
     */
    #[CastWith(MoneyCaster::class)]
    public ?Money $rate;

    /**
     * @var int Id of the first tax to apply to this task.
     */
    public ?int $tax1;

    /**
     * @var int Id of the second tax to apply to this task.
     */
    public ?int $tax2;

    /**
     * @var DateTimeImmutable The time of last modification.
     */
    #[CastWith(AccountingDateTimeImmutableCaster::class)]
    public ?DateTimeImmutable $updated;

    /**
     * @var int The visibility state: active, deleted, or archived
     *
     * See [FreshBooks API - Active and Deleted Objects](https://www.freshbooks.com/api/active_deleted)
     */
    #[MapFrom('vis_state')]
    public ?int $visState;

    /**
     * Get the data as an array to POST or PUT to FreshBooks, removing any read-only fields.
     *
     * @return array
     */
    public function getContent(): array
    {
        $data = $this
            ->except('id')
            ->except('taskId')
            ->except('updated')
            ->except('visState')
            ->toArray();
        foreach ($data as $key => $value) {
            if (is_null($value)) {
                unset($data[$key]);
            }
        }
        return $data;
    }
}
