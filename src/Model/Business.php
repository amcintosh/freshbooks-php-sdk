<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use DateTimeImmutable;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\Attributes\MapTo;
use Spatie\DataTransferObject\Caster;
use Spatie\DataTransferObject\DataTransferObject;
use amcintosh\FreshBooks\Model\Business;

/**
 *
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/identity_model
 */
class Business extends DataTransferObject
{
    /**
     * @var int Unique id of the business.
     */
    public ?int $id;

    /**
     * @var string UUID of the business. FreshBooks will be moving from id to business_uuid in future API calls.
     */
    #[MapFrom('business_uuid')]
    public ?string $businessUUID;

    /**
     * @var string Name of the business.
     */
    public ?string $name;

    /**
     * @var string Unique identifier of the accounting system the business is associated with.
     */
    #[MapFrom('account_id')]
    public ?string $accountId;

    /**
     * @var string Date format used by the business in FreshBooks.
     */
    #[MapFrom('date_format')]
    public ?string $dateFormat;
}
