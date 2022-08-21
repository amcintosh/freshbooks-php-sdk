<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\DataTransferObject;

/**
 * Invitation to join a project.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/project
 */
class ProjectGroupInvitation extends DataTransferObject
{
    /**
     * @var int The unique identifier of this invitation within this business.
     */
    public ?int $id;

    #[MapFrom('group_id')]
    public ?int $groundId;

    public ?string $capacity;

    #[MapFrom('to_email')]
    public ?string $toEmail;
}
