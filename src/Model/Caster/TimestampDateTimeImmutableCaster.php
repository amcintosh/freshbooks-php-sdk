<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model\Caster;

use DateTimeImmutable;
use DateTimeZone;
use Spatie\DataTransferObject\Caster;

class TimestampDateTimeImmutableCaster implements Caster
{
    public function __construct(
        private array $types,
    ) {
    }

    /**
     * @param string|mixed $value
     *
     * @return DateTimeImmutable
     */
    public function cast(mixed $value): DateTimeImmutable
    {
        return (new \DateTimeImmutable())->setTimestamp($value)->setTimezone(new DateTimeZone('UTC'));
    }
}
