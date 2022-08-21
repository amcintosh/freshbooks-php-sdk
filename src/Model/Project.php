<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use DateTime;
use DateTimeImmutable;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\Attributes\MapTo;
use Spatie\DataTransferObject\Caster;
use Spatie\DataTransferObject\DataTransferObject;
use Spryker\DecimalObject\Decimal;
use amcintosh\FreshBooks\Model\DataModel;
use amcintosh\FreshBooks\Model\ProjectGroup;
use amcintosh\FreshBooks\Model\Caster\DateCaster;
use amcintosh\FreshBooks\Model\Caster\DecimalCaster;
use amcintosh\FreshBooks\Model\Caster\ISODateTimeImmutableCaster;

/**
 * Projects in FreshBooks are used to track business projects and related information
 * such as hourly rate, service(s) being offered, projected end date...etc
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/project
 */
class Project extends DataTransferObject implements DataModel
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
    #[CastWith(DecimalCaster::class)]
    #[MapFrom('billed_amount')]
    public ?Decimal $billedAmount;

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
    #[MapFrom('billed_status')]
    public ?string $billedStatus;

    /**
     * @var string The method by which the project is billed.
     *
     * Eg. By business hourly rate, team member's rate, different rates
     * by service provided, or a rate for the project.
     */
    #[MapFrom('billing_method')]
    #[MapTo('billing_method')]
    public ?string $billingMethod;

    /**
     * @var int Time budgeted for the project in seconds.
     */
    public ?int $budget;

    /**
     * @var int The id of the client this project is for.
     */
    #[MapFrom('client_id')]
    #[MapTo('client_id')]
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
    #[CastWith(ISODateTimeImmutableCaster::class, includeTimeZoneDesignator: false)]
    #[MapFrom('created_at')]
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
    #[MapFrom('due_date')]
    #[CastWith(DateCaster::class)]
    public ?DateTime $dueDate;

    /**
     * @var string String percentage markup to be applied to expenses fo this project.
     */
    #[MapFrom('expense_markup')]
    #[MapTo('expense_markup')]
    public ?string $expenseMarkup;

    /**
     * @var Spryker\DecimalObject\Decimal For projects that are of type "fixed_price" this is the price for the project.
     */
    #[CastWith(DecimalCaster::class)]
    #[MapFrom('fixed_price')]
    public ?Decimal $fixedPrice;

    #[MapFrom('group')]
    public ?ProjectGroup $group;

    /**
     * @var bool Clarifies that the project is internal to the business and has no client (client is the company).
     */
    public ?bool $internal;

    /**
     * @var int The time logged against the project in seconds.
     */
    #[MapFrom('logged_duration')]
    public ?int $loggedDuration;

    #[MapFrom('project_manager_id')]
    #[MapTo('project_manager_id')]
    public ?int $projectManagerId;

    /**
     * @var string The project type. Either a fixed price or hourly rate.
     *
     * The type of hourly rate used is set with `getBillingMethod()`.
     */
    #[MapFrom('project_type')]
    #[MapTo('project_type')]
    public ?string $projectType;

    /**
     * @var Spryker\DecimalObject\Decimal The hourly rate for project_rate hourly projects.
     */
    #[CastWith(DecimalCaster::class)]
    public ?Decimal $rate;

    #[MapFrom('retainer_id')]
    #[MapTo('retainer_id')]
    public ?int $retainerId;

    //@Key List<Service> services;

    /**
     * @var int The project title
     */
    public ?string $title;

    /**
     * @var DateTimeImmutable The time of last modification to the project.
     */
    #[CastWith(ISODateTimeImmutableCaster::class, includeTimeZoneDesignator: false)]
    #[MapFrom('updated_at')]
    public ?DateTimeImmutable $updatedAt;


    /**
     * Get the data as an array to POST or PUT to FreshBooks, removing any read-only fields.
     *
     * @return array
     */
    public function getContent(): array
    {
        $data = $this
            ->except('id')
            ->except('billedAmount')
            ->except('billedStatus')
            ->except('createdAt')
            ->except('createdAt')
            ->except('dueDate')
            ->except('fixedPrice')
            ->except('group')
            ->except('loggedDuration')
            ->except('rate')
            ->except('updatedAt')
            ->toArray();
        if (isset($this->dueDate)) {
            $data['due_date'] = $this->dueDate->format('Y-m-d');
        }
        if (isset($this->fixedPrice)) {
            $data['fixed_price'] = $this->fixedPrice->toString();
        }
        if (isset($this->rate)) {
            $data['rate'] = $this->rate->toString();
        }
        foreach ($data as $key => $value) {
            if (is_null($value)) {
                unset($data[$key]);
            }
        }
        return $data;
    }
}
