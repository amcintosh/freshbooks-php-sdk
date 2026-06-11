<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use amcintosh\FreshBooks\Model\BusinessAddress;
use amcintosh\FreshBooks\Model\BusinessPhone;

/**
 * Each FreshBooks user is associated with a business.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/identity_model
 */
class Business
{
    /**
     * @var int|null Unique id of the business.
     */
    public ?int $id;

    /**
     * @var string|null UUID of the business. FreshBooks will be moving from id to business_uuid in future API calls.
     */
    public ?string $businessUUID;

    /**
     * @var string|null Name of the business.
     */
    public ?string $name;

    /**
     * @var string|null Unique identifier of the accounting system the business is associated with.
     */
    public ?string $accountId;

    /**
     * @var BusinessAddress|null The business address.
     */
    public ?BusinessAddress $address;

    /**
     * @var string|null Date format used by the business in FreshBooks.
     */
    public ?string $dateFormat;

    /**
     * @var BusinessPhone|null The phone number object of the business.
     */
    public ?BusinessPhone $phoneNumber = null;

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->businessUUID = $data['business_uuid'] ?? null;
        $this->name = $data['name'] ?? null;
        $this->accountId = $data['account_id'] ?? null;
        if (isset($data['address']) && is_array($data['address'])) {
            $this->address = new BusinessAddress($data['address']);
        } else {
            $this->address = null;
        }
        $this->dateFormat = $data['date_format'] ?? null;
        if (isset($data['phone_number']) && is_array($data['phone_number'])) {
            $this->phoneNumber = new BusinessPhone($data['phone_number']);
        }
    }
}
