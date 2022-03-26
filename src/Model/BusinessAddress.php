<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\Attributes\MapTo;
use Spatie\DataTransferObject\Caster;
use Spatie\DataTransferObject\DataTransferObject;
use amcintosh\FreshBooks\Model\Business;

/**
 * The address of the business.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/identity_model
 */
class BusinessAddress extends DataTransferObject
{
    /**
     * @var int Unique id of the address.
     */
    public ?int $id;

    /**
     * @var string Street address of business.
     */
    public ?string $street;

    /**
     * @var string City for address of business.
     */
    public ?string $city;

    /**
     * @var string Province/state for address of business.
     */
    public ?string $province;

    /**
     * @var string Country for address of business.
     */
    public ?string $country;

    /**
     * @var string Postal/ZIP code for address of business.
     */
    #[MapFrom('postal_code')]
    #[MapTo('postal_code')]
    public ?string $postalCode;
}
