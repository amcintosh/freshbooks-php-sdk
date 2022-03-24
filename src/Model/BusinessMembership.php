<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use Spatie\DataTransferObject\DataTransferObject;
use amcintosh\FreshBooks\Model\Business;

/**
 * Business/Identity relationship
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/identity_model
 */
class BusinessMembership extends DataTransferObject
{
    /**
     * @var int Membership Id.
     */
    public ?int $id;

    /**
     * @var string Identity's role in this business.
     */
    public ?string $role;

    /**
     * @var Business The business details.
     */
    public ?Business $business;
}
