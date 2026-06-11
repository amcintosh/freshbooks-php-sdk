<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

/**
 * The phone number of the business.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/identity_model
 */
class BusinessPhone
{
    /**
     * @var int|null Unique id of the phone number.
     */
    public ?int $id;

    /**
     * @var string|null The phone number
     */
    public ?string $phoneNumber;

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->phoneNumber = $data['phone_number'] ?? null;
    }
}
