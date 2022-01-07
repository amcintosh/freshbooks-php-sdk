<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use DateInterval;
use DateTimeImmutable;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\Attributes\MapTo;
use Spatie\DataTransferObject\Caster;
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
     * @var string The authorized bearer token from the OAuth2 token response.
     */
    #[MapFrom('access_token')]
    public ?string $accessToken;

    /**
     * @var string The authorized refresh token from the OAuth2 token response.
     */
    #[MapFrom('refresh_token')]
    public ?string $refreshToken;

    /**
     * @var DateTimeImmutable Time the bearer token was created.
     */
    #[MapFrom('created_at')]
    #[CastWith(TimestampDateTimeImmutableCaster::class)]
    public ?DateTimeImmutable $createdAt;

    /**
     * @var string Number of seconds since creation that the token will expire at.
     *
     * Please note {@see AuthorizationToken::getExpiresAt()} which calculates the expiry time
     * from this and createdAt.
     */
    #[MapFrom('expires_in')]
    public ?int $expiresIn;

    /**
     * @var string The scopes that the token is authorized for.
     */
    #[MapFrom('scope')]
    public ?string $scopes;

    /**
     * @return DateTimeImmutable Time the bearer token expires at.
     *
     * @see InvoiceStatus for a value constants.
     */
    public function getExpiresAt(): DateTimeImmutable
    {

        return $this->createdAt->add(new DateInterval('PT' . $this->expiresIn . 'S'));
    }
}
