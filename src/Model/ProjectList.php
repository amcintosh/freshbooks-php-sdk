<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

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

    public array $projects;

    public function __construct(array $data = [])
    {
        parent::__construct($data);
        $this->projects = $this->constructList($data[ProjectList::RESPONSE_FIELD], Project::class);
    }
}
