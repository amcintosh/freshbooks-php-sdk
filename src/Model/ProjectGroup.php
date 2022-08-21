<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\Caster;
use Spatie\DataTransferObject\Casters\ArrayCaster;
use Spatie\DataTransferObject\DataTransferObject;
use amcintosh\FreshBooks\Model\ProjectGroupInvitation;
use amcintosh\FreshBooks\Model\ProjectGroupMember;

/**
 * ProjectGroups contain of the members and pending invitations to become a member of a project.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/project
 */
class ProjectGroup extends DataTransferObject
{
    /**
     * @var int The unique identifier of this project group within this business.
     */
    public ?int $id;

    #[CastWith(ArrayCaster::class, itemType: ProjectGroupMember::class)]
    public array $members;

    #[CastWith(ArrayCaster::class, itemType: ProjectGroupInvitation::class)]
    #[MapFrom('pending_invitations')]
    public array $pendingInvitations;
}
