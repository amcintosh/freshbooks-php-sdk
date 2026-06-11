<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use DateTimeImmutable;
use amcintosh\FreshBooks\Model\BusinessMembership;
use amcintosh\FreshBooks\Util;

/**
 * Users are uniquely identified by their email across all of FreshBooks, so if
 * `leafy@example.com` is an Owner of one account and gets added as a Client on another,
 * they will have some access to both. They could then open a second business of their
 * own, or be added as an employee of another person’s business.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/identity_model
 */
class Identity
{
    /**
     * @var int|null The identity's unique id.
     */
    public ?int $identityId;

    /**
     * @var string|null UUID of the identity.
     *
     * FreshBooks will be moving from id to identity_uuid in future API calls.
     */
    public ?string $identityUUID;

    /**
     * @var string|null The identity's first name.
     */
    public ?string $firstName;

    /**
     * @var string|null The identity's last name.
     */
    public ?string $lastName;

    /**
     * @var string|null The identity's email.
     */
    public ?string $email;

    /**
     * @var string|null The language used by the identity in FreshBooks.
     */
    public ?string $language;

    /**
     * @var DateTimeImmutable|null Date the identity confirmed their email.
     */
    public ?DateTimeImmutable $confirmedAt;

    /**
     * @var DateTimeImmutable|null Date the identity was created.
     */
    public ?DateTimeImmutable $createdAt;

    public ?array $businessMemberships = null;

    public function __construct(array $data = [])
    {
        $this->identityId = $data['identity_id'] ?? null;
        $this->identityUUID = $data['identity_uuid'] ?? null;
        $this->firstName = $data['first_name'] ?? null;
        $this->lastName = $data['last_name'] ?? null;
        $this->email = $data['email'] ?? null;
        $this->language = $data['language'] ?? null;
        if (isset($data['confirmed_at'])) {
            $this->confirmedAt = Util::getProjectDateTimeFromISO($data['confirmed_at']);
        }
        if (isset($data['created_at'])) {
            $this->createdAt = Util::getProjectDateTimeFromISO($data['created_at']);
        }
        if (isset($data['business_memberships']) && is_array($data['business_memberships'])) {
            $this->businessMemberships = array_map(function ($membership) {
                return new BusinessMembership($membership);
            }, $data['business_memberships']);
        }
    }
}
