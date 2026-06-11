<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

/**
 * The address of the business.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/identity_model
 */
class BusinessAddress
{
    /**
     * @var int|null Unique id of the address.
     */
    public ?int $id;

    /**
     * @var string|null Street address of business.
     */
    public ?string $street;

    /**
     * @var string|null City for address of business.
     */
    public ?string $city;

    /**
     * @var string|null Province/state for address of business.
     */
    public ?string $province;

    /**
     * @var string|null Country for address of business.
     */
    public ?string $country;

    /**
     * @var string|null Postal/ZIP code for address of business.
     */
    public ?string $postalCode;

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->street = $data['street'] ?? null;
        $this->city = $data['city'] ?? null;
        $this->province = $data['province'] ?? null;
        $this->country = $data['country'] ?? null;
        $this->postalCode = $data['postal_code'] ?? null;
    }
}
