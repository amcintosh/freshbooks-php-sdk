<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use DateTime;
use DateTimeImmutable;
use Spryker\DecimalObject\Decimal;
use amcintosh\FreshBooks\Model\DataModel;
use amcintosh\FreshBooks\Model\ProjectGroup;
use amcintosh\FreshBooks\Model\Service;
use amcintosh\FreshBooks\Util;

/**
 * Projects in FreshBooks are used to track business projects and related information
 * such as hourly rate, service(s) being offered, projected end date...etc
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/project
 */
class Project implements DataModel
{
    public const RESPONSE_FIELD = 'project';

    /**
     * @var int The unique identifier of this project within this business.
     */
    public ?int $id;


    /**
     * @var bool Whether the project is active or not.
     */
    public ?bool $active;

    /**
     * @var Spryker\DecimalObject\Decimal The amount that has been invoiced for this project.
     */
    public ?Decimal $billedAmount = null;

    /**
     * @var string Billing statuses for a project, computed from invoice totals that have been sent for that project.
     *
     * Values are:
     *
     * - **billed**: Invoice is created and in no other state
     * - **partially_billed**: Invoice is saved in draft status
     * - **unbilled**: Invoice has been sent
     *
     * @link https://www.freshbooks.com/api/project
     */
    public ?string $billedStatus;

    /**
     * @var string The method by which the project is billed.
     *
     * Eg. By business hourly rate, team member's rate, different rates
     * by service provided, or a rate for the project.
     */
    public ?string $billingMethod;

    /**
     * @var int Time budgeted for the project in seconds.
     */
    public ?int $budget;

    /**
     * @var int The id of the client this project is for.
     */
    public ?int $clientId;

    /**
     * @var bool If the project has been completed and is archived.
     *
     * Archived projects do not return in list results by default.
     */
    public ?bool $complete;

    /**
     * @var DateTimeImmutable The creation time of the project.
     */
    public ?DateTimeImmutable $createdAt;

    /**
     * @var string The description of the project.
     */
    public ?string $description;

    /**
     * @var DateTime Date the payment was made.
     *
     * The API returns this in YYYY-MM-DD format. It is converted to a DateTime.
     */
    public ?DateTime $dueDate;

    /**
     * @var string String percentage markup to be applied to expenses fo this project.
     */
    public ?string $expenseMarkup;

    /**
     * @var Spryker\DecimalObject\Decimal For projects that are of type "fixed_price" this is the price for the project.
     */
    public ?Decimal $fixedPrice = null;

    /**
     * @var ProjectGroup The project group this project belongs to.
     */
    public ?ProjectGroup $group;

    /**
     * @var bool Clarifies that the project is internal to the business and has no client (client is the company).
     */
    public ?bool $internal;

    /**
     * @var int The time logged against the project in seconds.
     */
    public ?int $loggedDuration;

    public ?int $projectManagerId;

    /**
     * @var string The project type. Either a fixed price or hourly rate.
     *
     * The type of hourly rate used is set with `getBillingMethod()`.
     */
    public ?string $projectType;

    /**
     * @var Spryker\DecimalObject\Decimal The hourly rate for project_rate hourly projects.
     */
    public ?Decimal $rate = null;

    public ?int $retainerId;

    /**
     * @var array The services that work in this project can be logged against and
     * will appear on invoices when the project is billed for.
     */
    public ?array $services;

    /**
     * @var int The project title
     */
    public ?string $title;

    /**
     * @var DateTimeImmutable The time of last modification to the project.
     */
    public ?DateTimeImmutable $updatedAt;

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->active = $data['active'] ?? null;
        if (isset($data['billed_amount'])) {
            $this->billedAmount = Decimal::create($data['billed_amount']);
        }
        $this->billedStatus = $data['billed_status'] ?? null;
        $this->billingMethod = $data['billing_method'] ?? null;
        $this->budget = $data['budget'] ?? null;
        $this->clientId = $data['client_id'] ?? null;
        $this->complete = $data['complete'] ?? null;
        if (isset($data['created_at'])) {
            $this->createdAt = Util::getProjectDateTimeFromNaiveUTC($data['created_at']);
        }
        $this->description = $data['description'] ?? null;
        if (isset($data['due_date'])) {
            $this->dueDate = Util::getDate($data['due_date']);
        }
        $this->expenseMarkup = $data['expense_markup'] ?? null;
        if (isset($data['fixed_price'])) {
            $this->fixedPrice = Decimal::create($data['fixed_price']);
        }
        if (isset($data['group'])) {
            $this->group = new ProjectGroup($data['group']);
        }
        $this->internal = $data['internal'] ?? null;
        $this->loggedDuration = $data['logged_duration'] ?? null;
        $this->projectManagerId = $data['project_manager_id'] ?? null;
        $this->projectType = $data['project_type'] ?? null;
        if (isset($data['rate'])) {
            $this->rate = Decimal::create($data['rate']);
        }
        $this->retainerId = $data['retainer_id'] ?? null;
        if (isset($data['services'])) {
            $this->services = array_map(function ($serviceData) {
                return new Service($serviceData);
            }, $data['services']);
        }
        $this->title = $data['title'] ?? null;
        if (isset($data['updated_at'])) {
            $this->updatedAt = Util::getProjectDateTimeFromNaiveUTC($data['updated_at']);
        }
    }

    /**
     * Get the data as an array to POST or PUT to FreshBooks, removing any read-only fields.
     *
     * @return array
     */
    public function getContent(): array
    {
        $data = array();
        Util::convertContent($data, 'active', $this->active);
        Util::convertContent($data, 'billing_method', $this->billingMethod);
        Util::convertContent($data, 'budget', $this->budget);
        Util::convertContent($data, 'client_id', $this->clientId);
        Util::convertContent($data, 'complete', $this->complete);
        Util::convertContent($data, 'description', $this->description);
        Util::convertContent($data, 'expense_markup', $this->expenseMarkup);
        Util::convertContent($data, 'fixed_price', $this->fixedPrice);
        Util::convertContent($data, 'internal', $this->internal);
        Util::convertContent($data, 'project_manager_id', $this->projectManagerId);
        Util::convertContent($data, 'project_type', $this->projectType);
        Util::convertContent($data, 'rate', $this->rate);
        Util::convertContent($data, 'retainer_id', $this->retainerId);
        Util::convertContent($data, 'title', $this->title);

        if (isset($this->dueDate)) {
            $data['due_date'] = $this->dueDate->format(Util::DATE_FORMAT);
        }

        return $data;
    }
}
