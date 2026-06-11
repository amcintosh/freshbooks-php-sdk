<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use DateInterval;
use DateTimeImmutable;
use Exception;
use amcintosh\FreshBooks\Model\DataModel;
use amcintosh\FreshBooks\Util;

/**
 * Authorization data including the OAuth bearer token, expiry, and refresh token.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/authentication
 */
class AuthorizationToken implements DataModel
{
    /**
     * @var string|null The authorized bearer token from the OAuth2 token response.
     */
    public ?string $accessToken;

    /**
     * @var string|null The authorized refresh token from the OAuth2 token response.
     */
    public ?string $refreshToken;

    /**
     * @var DateTimeImmutable|null Time the bearer token was created.
     */
    public ?DateTimeImmutable $createdAt;

    /**
     * @var int|null Number of seconds since creation that the token will expire at.
     *
     * Please note {@see AuthorizationToken::getExpiresAt()} which calculates the expiry time
     * from this and createdAt.
     */
    public ?int $expiresIn;

    /**
     * @var string|null The scopes that the token is authorized for.
     */
    public ?string $scopes;

    /**
     * @return DateTimeImmutable Time the bearer token expires at.
     */
    public function getExpiresAt(): DateTimeImmutable
    {
        try {
            return $this->createdAt->add(new DateInterval('PT' . $this->expiresIn . 'S'));
        } catch (Exception) {
            return $this->createdAt;
        }
    }

    public function __construct(array $data = [])
    {
        $this->accessToken = $data['access_token'] ?? null;
        $this->refreshToken = $data['refresh_token'] ?? null;
        if (isset($data['created_at'])) {
            $this->createdAt = Util::getDateTimeFromTimestamp($data['created_at']);
        }
        $this->expiresIn = $data['expires_in'] ?? null;
        $this->scopes = $data['scope'] ?? null;
    }

    /**
     * Get the data as an array to POST or PUT to FreshBooks, removing any read-only fields.
     *
     * @return array
     */
    public function getContent(): array
    {
        return array();
    }
}
