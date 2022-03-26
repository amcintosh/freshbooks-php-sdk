<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\Attributes\MapTo;
use Spatie\DataTransferObject\Caster;
use Spatie\DataTransferObject\DataTransferObject;
use amcintosh\FreshBooks\Model\Business;

/**
 * The phone number of the business.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/identity_model
 */
class BusinessPhone extends DataTransferObject
{
    /**
     * @var int Unique id of the phone number.
     */
    public ?int $id;

    /**
     * @var string The phone number
     */
    #[MapFrom('phone_number')]
    #[MapTo('phone_number')]
    public ?string $phoneNumber;
}
