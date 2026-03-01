<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use DateInterval;
use DateTimeImmutable;
use Exception;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\DataTransferObject;
use amcintosh\FreshBooks\Model\Caster\TimestampDateTimeImmutableCaster;

/**
 * Authorization data including the OAuth bearer token, expiry, and refresh token.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/authentication
 */
class AuthorizationToken extends DataTransferObject
{
    /**
     * @var string|null The authorized bearer token from the OAuth2 token response.
     */
    #[MapFrom('access_token')]
    public ?string $accessToken;

    /**
     * @var string|null The authorized refresh token from the OAuth2 token response.
     */
    #[MapFrom('refresh_token')]
    public ?string $refreshToken;

    /**
     * @var DateTimeImmutable|null Time the bearer token was created.
     */
    #[MapFrom('created_at')]
    #[CastWith(TimestampDateTimeImmutableCaster::class)]
    public ?DateTimeImmutable $createdAt;

    /**
     * @var int|null Number of seconds since creation that the token will expire at.
     *
     * Please note {@see AuthorizationToken::getExpiresAt()} which calculates the expiry time
     * from this and createdAt.
     */
    #[MapFrom('expires_in')]
    public ?int $expiresIn;

    /**
     * @var string|null The scopes that the token is authorized for.
     */
    #[MapFrom('scope')]
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
}
