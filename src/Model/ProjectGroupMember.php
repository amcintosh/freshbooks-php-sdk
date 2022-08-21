<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\DataTransferObject;

/**
 * Member of a project.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/project
 */
class ProjectGroupMember extends DataTransferObject
{
    /**
     * @var int The unique identifier of this group member within this business.
     */
    public ?int $id;

    public ?bool $active;

    public ?string $company;

    public ?string $email;

    #[MapFrom('first_name')]
    public ?string $firstName;

    #[MapFrom('identity_id')]
    public ?int $identityId;

    #[MapFrom('last_name')]
    public ?string $lastName;

    public ?string $role;
}
