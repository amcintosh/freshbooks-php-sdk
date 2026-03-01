<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use DateTimeImmutable;
use amcintosh\FreshBooks\Model\DataModel;
use amcintosh\FreshBooks\Util;

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
class Task implements DataModel
{
    public const RESPONSE_FIELD = 'task';

    /**
     * @var int The unique identifier of this task within this business.
     */
    public ?int $id;

    /**
     * @var int Duplicate of id.
     */
    public ?int $taskId;

    /**
     * @var string Unique identifier of account the client exists on.
     */
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
    public ?Money $rate = null;

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
    public ?DateTimeImmutable $updated = null;

    /**
     * @var int The visibility state: active, deleted, or archived
     *
     * See [FreshBooks API - Active and Deleted Objects](https://www.freshbooks.com/api/active_deleted)
     */
    public ?int $visState;

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->taskId = $data['taskid'] ?? null;
        $this->accountingSystemId = $data['accounting_systemid'] ?? null;
        $this->billable = $data['billable'] ?? null;
        $this->description = $data['description'] ?? null;
        $this->name = $data['name'] ?? null;
        if (isset($data['rate'])) {
            $this->rate = new Money($data['rate']['amount'], $data['rate']['code']);
        }
        $this->tax1 = $data['tax1'] ?? null;
        $this->tax2 = $data['tax2'] ?? null;
        if (isset($data['updated'])) {
            $this->updated = Util::getAccountingDateTime($data['updated']);
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
        $data = array();
        Util::convertContent($data, 'billable', $this->billable);
        Util::convertContent($data, 'description', $this->description);
        Util::convertContent($data, 'name', $this->name);
        Util::convertContent($data, 'rate', $this->rate);
        Util::convertContent($data, 'tax1', $this->tax1);
        Util::convertContent($data, 'tax2', $this->tax2);

        return $data;
    }
}
