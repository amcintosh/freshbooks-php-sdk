<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use DateTimeImmutable;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\Caster;
use Spatie\DataTransferObject\Casters\ArrayCaster;
use Spatie\DataTransferObject\DataTransferObject;
use amcintosh\FreshBooks\Model\BusinessMembership;
use amcintosh\FreshBooks\Model\Caster\ISODateTimeImmutableCaster;

/**
 * Users are uniquely identified by their email across all of FreshBooks, so if
 * `leafy@example.com` is an Owner of one account and gets added as a Client on another,
 * they will have some access to both. They could then open a second business of their
 * own, or be added as an employee of another person’s business.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/identity_model
 */
class Identity extends DataTransferObject
{
    /**
     * @var int The identity's unique id.
     */
    #[MapFrom('identity_id')]
    public ?int $identityId;

    /**
     * @var string UUID of the identity.
     *
     * FreshBooks will be moving from id to identity_uuid in future API calls.
     */
    #[MapFrom('identity_uuid')]
    public ?string $identityUUID;

    /**
     * @var string The identity's first name.
     */
    #[MapFrom('first_name')]
    public ?string $firstName;

    /**
     * @var string The identity's last name.
     */
    #[MapFrom('last_name')]
    public ?string $lastName;

    /**
     * @var string The identity's email.
     */
    public ?string $email;

    /**
     * @var string The language used by the identity in FreshBooks.
     */
    public ?string $language;

    /**
     * @var DateTimeImmutable Date the identity confirmed their email.
     */
    #[CastWith(ISODateTimeImmutableCaster::class)]
    #[MapFrom('confirmed_at')]
    public ?DateTimeImmutable $confirmedAt;

    /**
     * @var DateTimeImmutable Date the identity was created.
     */
    #[CastWith(ISODateTimeImmutableCaster::class)]
    #[MapFrom('created_at')]
    public ?DateTimeImmutable $createdAt;

    #[CastWith(ArrayCaster::class, itemType: BusinessMembership::class)]
    #[MapFrom('business_memberships')]
    public ?array $businessMemberships;
}
