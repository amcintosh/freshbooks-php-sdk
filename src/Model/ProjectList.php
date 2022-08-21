<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Casters\ArrayCaster;
use amcintosh\FreshBooks\Model\Project;
use amcintosh\FreshBooks\Model\ProjectLikeList;

/**
 * Results of Projects list call containing list of projects and pagination data.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/payments
 */
class ProjectList extends ProjectLikeList
{
    public const RESPONSE_FIELD = 'projects';

    #[CastWith(ArrayCaster::class, itemType: Project::class)]
    public array $projects;
}
