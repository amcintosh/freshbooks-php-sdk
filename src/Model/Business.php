<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\DataTransferObject;
use amcintosh\FreshBooks\Model\BusinessAddress;
use amcintosh\FreshBooks\Model\BusinessPhone;

/**
 * Each FreshBooks user is associated with a business.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/identity_model
 */
class Business extends DataTransferObject
{
    /**
     * @var int|null Unique id of the business.
     */
    public ?int $id;

    /**
     * @var string|null UUID of the business. FreshBooks will be moving from id to business_uuid in future API calls.
     */
    #[MapFrom('business_uuid')]
    public ?string $businessUUID;

    /**
     * @var string|null Name of the business.
     */
    public ?string $name;

    /**
     * @var string|null Unique identifier of the accounting system the business is associated with.
     */
    #[MapFrom('account_id')]
    public ?string $accountId;

    /**
     * @var BusinessAddress|null The business address.
     */
    public ?BusinessAddress $address;

    /**
     * @var string|null Date format used by the business in FreshBooks.
     */
    #[MapFrom('date_format')]
    public ?string $dateFormat;

    /**
     * @var BusinessPhone|null The phone number object of the business.
     */
    #[MapFrom('phone_number')]
    public ?BusinessPhone $phoneNumber;
}
