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
use amcintosh\FreshBooks\Model\DataModelLegacy;
use amcintosh\FreshBooks\Model\ProjectGroup;
use amcintosh\FreshBooks\Model\Caster\DateCaster;
use amcintosh\FreshBooks\Model\Caster\DecimalCaster;
use amcintosh\FreshBooks\Model\Caster\ISODateTimeImmutableCaster;

/**
 * Services represent things that a business offers to clients. Services are added to projects
 * to to allow tracking of time entries by type of work.
 *
 * Services keep track of details such as hourly rate. See `ServiceRate` for adding a rate to a
 * service.
 *
 * Services automatically get converted to tasks for inclusion on invoices.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/services
 */
class Service extends DataTransferObject
{
    /**
     * @var int The unique identifier of this service.
     */
    public ?int $id;

    /**
     * @var int The unique id for business.
     */
    #[MapFrom('business_id')]
    #[MapTo('business_id')]
    public ?int $businessId;


    /**
     * @var string The descriptive name of service.
     */
    public ?string $name;

    /**
     * @var bool Whether the service is billable to clients or not.
     */
    public ?bool $billable;

    /**
     * @var int The visibility state: active, deleted, or archived
     *
     * See [FreshBooks API - Active and Deleted Objects](https://www.freshbooks.com/api/active_deleted)
     */
    #[MapFrom('vis_state')]
    public ?int $visState;
}
