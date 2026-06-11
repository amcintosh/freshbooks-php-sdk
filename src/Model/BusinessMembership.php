<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use amcintosh\FreshBooks\Model\Business;

/**
 * Business/Identity relationship
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/identity_model
 */
class BusinessMembership
{
    /**
     * @var int|null Membership Id.
     */
    public ?int $id;

    /**
     * @var string|null Identity's role in this business.
     */
    public ?string $role;

    /**
     * @var Business|null The business details.
     */
    public ?Business $business = null;

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->role = $data['role'] ?? null;
        if (isset($data['business']) && is_array($data['business'])) {
            $this->business = new Business($data['business']);
        }
    }
}
